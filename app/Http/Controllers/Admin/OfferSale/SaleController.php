<?php
namespace App\Http\Controllers\Admin\offersale;

use App\Models\Sale;
use App\Mail\NewSaleMail;
use Illuminate\Http\Request;
use App\Models\NewsletterLog;
use App\Models\NewsletterCampaign;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\NewsletterSubscriber;
use Illuminate\Support\Facades\Mail;

class SaleController extends Controller
{


    public function create()
    {
        $sale = Sale::first();
        $saleExists = Sale::exists(); 

        return view('admin.sales-offers.sale-create', compact( 'saleExists'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'discount' => 'required|numeric|min:0|max:100',
            'description' => 'required|string|max:255',
            'status'   => 'required|in:active,inactive',
            'days'     => 'nullable|integer|min:0|max:30',
            'hours'    => 'nullable|integer|min:0|max:23',
            'minutes'  => 'nullable|integer|min:0|max:59',
            'seconds'  => 'nullable|integer|min:0|max:59',
        ]);

        $startTime = now();
        $endTime = (clone $startTime)
            ->addDays((int) ($data['days'] ?? 0))
            ->addHours((int) ($data['hours'] ?? 0))
            ->addMinutes((int) ($data['minutes'] ?? 0))
            ->addSeconds((int) ($data['seconds'] ?? 0));

            $sale = Sale::create([
                'title'       => $data['title'], 
                'description' => $data['description'], 
                'discount'    => $data['discount'],
                'status'      => $data['status'],
                'start_time'  => $startTime,
                'end_time'    => $endTime,
            ]);
            

            try {
                $count = NewsletterSubscriber::where('is_unsubscribed', 0)->count();
        
                NewsletterCampaign::create([
                    'type'             => 'sale',
                    'sale_id'          => $sale->id, 
                    'subject'          => "Big Sale: {$sale->title}",
                    'total_recipients' => $count,
                    'status'           => 'draft', 
                    'sent_count'       => 0,
                ]);
        
            } catch (\Exception $e) {
                Log::error("Draft creation failed for Sale #{$sale->id}");
            }
           
            
        return redirect()->route('admin.sales-offers.index')
            ->with('success', 'Sale created successfully!');
    }

    public function edit($id)
    {
        $sale = Sale::findOrFail($id);
        return view('admin.sales-offers.sale-edit', compact('sale'));
    }

    public function update(Request $request, $id)
    {
        $sale = Sale::findOrFail($id);
    
        $data = $request->validate([
            'title'       => 'required|string|max:255',         
            'description' => 'required|string|max:255',         
            'discount'    => 'required|numeric|min:0|max:100',
            'status'      => 'required|in:active,inactive',
            'days'        => 'nullable|integer|min:0|max:30',
            'hours'       => 'nullable|integer|min:0|max:23',
            'minutes'     => 'nullable|integer|min:0|max:59',
            'seconds'     => 'nullable|integer|min:0|max:59',
        ]);
    
        $startTime = now();
        $endTime = (clone $startTime)
            ->addDays((int) ($data['days'] ?? 0))
            ->addHours((int) ($data['hours'] ?? 0))
            ->addMinutes((int) ($data['minutes'] ?? 0))
            ->addSeconds((int) ($data['seconds'] ?? 0));
    
        $sale->update([
            'title'       => $data['title'],         
            'description' => $data['description'],  
            'discount'    => $data['discount'],
            'status'      => $data['status'],
            'start_time'  => $startTime,
            'end_time'    => $endTime,
        ]);
    
        return redirect()->route('admin.sales-offers.index')
            ->with('success', 'Sale updated successfully!');
    }
    

    public function destroy($id)
{
    $offer = Sale::findOrFail($id);
    $offer->delete();

    return redirect()->route('admin.sales-offers.index')
                     ->with('success', 'Offer deleted successfully.');
}
    
}
