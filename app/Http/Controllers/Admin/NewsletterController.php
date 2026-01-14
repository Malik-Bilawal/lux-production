<?php

namespace App\Http\Controllers\Admin;

use Excel;
use Carbon\Carbon;
use App\Models\Sale;
use App\Models\Offer;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\NewsletterLog;
use App\Models\NewsletterCampaign;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Jobs\SendNewsletterCampaign;
use App\Models\NewsletterSubscriber;

class NewsletterController extends Controller
{
    public function index(Request $request)
    {
        $activeSubscribers = NewsletterSubscriber::where('is_unsubscribed', 0)->count();
        
        $emailsQueued = DB::table('jobs')
            ->where('queue', 'default')
            ->count();
            
        $avgOpenRate = NewsletterCampaign::where('status', 'sent')
            ->where('sent_count', '>', 0)
            ->avg('open_rate') ?? 0;
            
        $campaignsToday = NewsletterCampaign::whereDate('created_at', today())->count();
        
        $query = NewsletterCampaign::with(['product', 'sale', 'offer'])
            ->orderBy('created_at', 'desc');
            
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->has('type')) {
            $query->where('type', $request->type);
        }
        
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%");
            });
        }
        
        $campaigns = $query->paginate(15);
        
        $performanceData = $this->getPerformanceData();
        
        return view('admin.newsletter.index', [
            'campaigns' => $campaigns,
            'stats' => [
                'activeSubscribers' => $activeSubscribers,
                'emailsQueued' => $emailsQueued,
                'avgOpenRate' => round($avgOpenRate, 1),
                'campaignsToday' => $campaignsToday,
                'totalSent' => NewsletterCampaign::where('status', 'sent')->sum('sent_count'),
                'totalOpens' => NewsletterCampaign::where('status', 'sent')->sum('opens'),
                'totalClicks' => NewsletterCampaign::where('status', 'sent')->sum('clicks'),
            ],
            'performanceData' => $performanceData,
            'filters' => $request->only(['status', 'type', 'search'])
        ]);
    }
    
    private function getPerformanceData()
    {
        $last30Days = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $last30Days[$date->format('Y-m-d')] = [
                'date' => $date->format('M d'),
                'sent' => 0,
                'opens' => 0,
                'clicks' => 0,
            ];
        }
        
        // Get campaign data for last 30 days
        $campaigns = NewsletterCampaign::where('status', 'sent')
            ->whereDate('completed_at', '>=', Carbon::now()->subDays(30))
            ->select(
                DB::raw('DATE(completed_at) as date'),
                DB::raw('SUM(sent_count) as sent'),
                DB::raw('SUM(opens) as opens'),
                DB::raw('SUM(clicks) as clicks')
            )
            ->groupBy(DB::raw('DATE(completed_at)'))
            ->get()
            ->keyBy('date');
        
        foreach ($last30Days as $date => $data) {
            if (isset($campaigns[$date])) {
                $last30Days[$date]['sent'] = (int) $campaigns[$date]->sent;
                $last30Days[$date]['opens'] = (int) $campaigns[$date]->opens;
                $last30Days[$date]['clicks'] = (int) $campaigns[$date]->clicks;
            }
        }
        
        return array_values($last30Days);
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'content' => 'required|string',
            'preview_text' => 'nullable|string|max:150',
            'type' => 'required|in:custom,product,sale,offer',
            'scheduled_at' => 'nullable|date',
        ]);
        
        $campaign = NewsletterCampaign::create([
            'name' => $request->name,
            'subject' => $request->subject,
            'preview_text' => $request->preview_text,
            'content' => $request->content,
            'type' => $request->type,
            'status' => $request->scheduled_at ? 'scheduled' : 'draft',
            'scheduled_at' => $request->scheduled_at,
            'total_recipients' => NewsletterSubscriber::where('is_unsubscribed', false)->count(),
            'created_by' => auth()->id(),
        ]);
        
        return redirect()->back()->with('success', 'Campaign created successfully.');
    }
    
    public function update(Request $request, NewsletterCampaign $campaign)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'content' => 'required|string',
            'preview_text' => 'nullable|string|max:150',
            'scheduled_at' => 'nullable|date',
        ]);
        
        $campaign->update($request->only(['name', 'subject', 'content', 'preview_text', 'scheduled_at']));
        
        return redirect()->back()->with('success', 'Campaign updated successfully.');
    }
    
    public function send(NewsletterCampaign $campaign)
    {
        if (!$campaign->canSend()) {
            return redirect()->back()->with('error', 'Campaign cannot be sent.');
        }
        
        // Dispatch job to send campaign
        SendNewsletterCampaign::dispatch($campaign);
        
        return redirect()->back()->with('success', 'Campaign is being sent.');
    }
    
    public function schedule(Request $request, NewsletterCampaign $campaign)
    {
        $request->validate([
            'scheduled_at' => 'required|date|after:now',
        ]);
        
        $campaign->schedule($request->scheduled_at);
        
        return redirect()->back()->with('success', 'Campaign scheduled successfully.');
    }
    
    public function cancel(NewsletterCampaign $campaign)
    {
        $campaign->cancel();
        
        return redirect()->back()->with('success', 'Campaign cancelled.');
    }
    
    public function destroy(NewsletterCampaign $campaign)
    {
        if ($campaign->status === 'sending') {
            return redirect()->back()->with('error', 'Cannot delete campaign while sending.');
        }
        
        $campaign->delete();
        
        return redirect()->back()->with('success', 'Campaign deleted.');
    }
    
    public function duplicate(NewsletterCampaign $campaign)
    {
        $newCampaign = $campaign->replicate();
        $newCampaign->name = $campaign->name . ' (Copy)';
        $newCampaign->status = 'draft';
        $newCampaign->sent_count = 0;
        $newCampaign->opens = 0;
        $newCampaign->clicks = 0;
        $newCampaign->scheduled_at = null;
        $newCampaign->started_at = null;
        $newCampaign->completed_at = null;
        $newCampaign->save();
        
        return redirect()->back()->with('success', 'Campaign duplicated.');
    }
    
    public function subscribers(Request $request)
    {
        $query = NewsletterSubscriber::orderBy('created_at', 'desc');
        
        if ($request->has('status')) {
            $query->where('is_unsubscribed', $request->status === 'unsubscribed');
        }
        
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('email', 'like', "%{$search}%")
                  ->orWhere('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%");
            });
        }
        
        $subscribers = $query->paginate(20);
        
        return view('admin.newsletter.subscribers', compact('subscribers'));
    }
    
    public function toggleUnsubscribe(Request $request, NewsletterSubscriber $subscriber)
    {
        if ($subscriber->is_unsubscribed) {
            $subscriber->resubscribe();
            $message = 'Subscriber re-subscribed successfully.';
        } else {
            $subscriber->unsubscribe();
            $message = 'Subscriber unsubscribed successfully.';
        }
        
        if ($request->ajax()) {
            return response()->json([
                'status' => 'success',
                'is_unsubscribed' => $subscriber->is_unsubscribed,
                'message' => $message,
            ]);
        }
        
        return redirect()->back()->with('success', $message);
    }
    
    public function campaignReport(NewsletterCampaign $campaign)
    {
        $campaign->load(['logs' => function($query) {
            $query->latest()->take(100);
        }, 'logs.subscriber']);
    
        $engagementData = $this->getEngagementData($campaign);
    
        $campaignScore = $this->calculateCampaignScore($campaign);
    
        // Pass $campaignScore to the view
        return view('admin.newsletter.report', compact('campaign', 'engagementData', 'campaignScore'));
    }
    
    private function getEngagementData(NewsletterCampaign $campaign)
    {
        $logs = $campaign->logs()
            ->whereNotNull('opened_at')
            ->selectRaw('HOUR(opened_at) as hour, COUNT(*) as count')
            ->groupBy(DB::raw('HOUR(opened_at)'))
            ->orderBy('hour')
            ->get();
            
        $hours = array_fill(0, 24, 0);
        foreach ($logs as $log) {
            $hours[$log->hour] = $log->count;
        }
        
        return [
            'hours' => $hours,
            'devices' => $campaign->logs()
                ->whereNotNull('device_type')
                ->select('device_type', DB::raw('COUNT(*) as count'))
                ->groupBy('device_type')
                ->pluck('count', 'device_type')
                ->toArray(),
            'top_links' => $campaign->logs()
                ->whereNotNull('clicked_url')
                ->select('clicked_url', DB::raw('COUNT(*) as clicks'))
                ->groupBy('clicked_url')
                ->orderByDesc('clicks')
                ->take(10)
                ->get(),
        ];
    }
    
    // Tracking routes
    public function trackOpen($logId)
    {
        $log = NewsletterLog::findOrFail($logId);
        $log->markAsOpened(request()->ip(), request()->userAgent());
        
        // Return 1x1 transparent GIF
        $gif = base64_decode('R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7');
        return response($gif, 200, [
            'Content-Type' => 'image/gif',
            'Content-Length' => strlen($gif),
            'Cache-Control' => 'private, no-cache, no-store, must-revalidate',
            'Expires' => 'Sat, 01 Jan 2000 00:00:00 GMT',
            'Pragma' => 'no-cache',
        ]);
    }
    
    public function trackClick($logId, Request $request)
    {
        $log = NewsletterLog::findOrFail($logId);
        $url = urldecode($request->url);
        
        $log->markAsClicked($url, request()->ip(), request()->userAgent());
        
        return redirect($url);
    }
    
    public function unsubscribe($token, Request $request)
    {
        $subscriber = NewsletterSubscriber::where('token', $token)->firstOrFail();
        
        if (!$subscriber->is_unsubscribed) {
            $subscriber->unsubscribe();
            
            // Log which campaign triggered unsubscribe if provided
            if ($request->has('campaign')) {
                NewsletterLog::where('campaign_id', $request->campaign)
                    ->where('subscriber_id', $subscriber->id)
                    ->update(['unsubscribed_at' => now()]);
            }
        }
        
        return view('emails.newsletter.unsubscribed', [
            'email' => $subscriber->email
        ]);
    }
    
    public function subscribe(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:newsletter_subscribers,email',
            'first_name' => 'nullable|string|max:50',
            'last_name' => 'nullable|string|max:50',
        ]);
        
        $subscriber = NewsletterSubscriber::create([
            'email' => $request->email,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'source' => $request->get('source', 'website'),
            'subscribed_at' => now(),
        ]);
        
        // Send welcome email if needed
        // Mail::to($subscriber->email)->send(new NewsletterWelcomeMail($subscriber));
        
        if ($request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Successfully subscribed to newsletter!',
            ]);
        }
        
        return redirect()->back()->with('success', 'Successfully subscribed!');
    }





    public function edit(NewsletterCampaign $campaign)
{
    return response()->json([
        'id' => $campaign->id,
        'name' => $campaign->name,
        'subject' => $campaign->subject,
        'type' => $campaign->type,
        'preview_text' => $campaign->preview_text,
        'content' => $campaign->content,
    ]);
}

public function refresh(Request $request)
{
    $activeSubscribers = NewsletterSubscriber::where('is_unsubscribed', 0)->count();
    $emailsQueued = DB::table('jobs')->where('queue', 'default')->count();
    $campaignsToday = NewsletterCampaign::whereDate('created_at', today())->count();
    
    return response()->json([
        'activeSubscribers' => $activeSubscribers,
        'emailsQueued' => $emailsQueued,
        'campaignsToday' => $campaignsToday,
        'updated_at' => now()->format('H:i:s'),
    ]);
}


public function exportReport(Request $request, NewsletterCampaign $campaign)
{
    $request->validate([
        'format' => 'required|in:pdf,excel',
        'sections' => 'array'
    ]);

    $campaign->load(['logs' => function($query) {
        $query->with('subscriber');
    }]);

    $engagementData = $this->getEngagementData($campaign);
    
    if ($request->format === 'pdf') {
        return $this->exportPdfReport($campaign, $engagementData, $request->sections);
    } else {
        return $this->exportExcelReport($campaign, $engagementData, $request->sections);
    }
}

private function exportPdfReport($campaign, $engagementData, $sections)
{

    
    $pdf = \PDF::loadView('admin.newsletter.exports.pdf', [
        'campaign' => $campaign,
        'engagementData' => $engagementData,
        'sections' => $sections
    ]);
    
    return $pdf->download("campaign-report-{$campaign->id}.pdf");
}

private function exportExcelReport($campaign, $engagementData, $sections)
{

    
    return Excel::download(new CampaignReportExport($campaign, $engagementData, $sections), 
                           "campaign-report-{$campaign->id}.xlsx");
}

// Add this method to calculate campaign score
public function calculateCampaignScore($campaign)
{
    $deliveryRate = $campaign->total_recipients > 0 ? 
        ($campaign->sent_count / $campaign->total_recipients) * 100 : 0;
    $openRate = $campaign->open_rate ?? 0;
    $clickRate = $campaign->click_rate ?? 0;
    
    return min(100, round(
        ($deliveryRate * 0.3) + 
        ($openRate * 0.4) + 
        ($clickRate * 0.3)
    ));
}


}