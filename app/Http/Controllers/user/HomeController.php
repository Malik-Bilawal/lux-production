<?php

namespace App\Http\Controllers\User;

use App\Models\Page;
use App\Models\Sale;
use App\Models\Offer;
use App\Models\Product;
use App\Models\Category;
use App\Models\PageHero;
use App\Models\HomeVideo;
use App\Models\HomeSlider;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\NewsletterSubscriber;

class HomeController extends Controller
{


    public function index()
    {

        $saleTimer = Sale::where('status', 'active')
            ->where('end_time', '>', Carbon::now()) // only future timers
            ->latest()
            ->first();

        $header = PageHero::where('page_type', 'home')->latest()->first();

        $video = HomeVideo::latest()->first();
        $topSellingProducts = Product::with('mainImage', 'subImage')->where('is_top_selling', 1)
            ->where('status', 'active')->latest()->take(10)->get();
        $featureProducts = Product::with('mainImage', 'subImage')->where('is_feature_card', 1)
            ->where('status', 'active')->latest()->take(20)->get();
        $trustPages = Page::orderBy('created_at', 'desc')->take(4)->get();

        // Get only **eligible categories** (status=1 & >=2 products)
        $categories = Category::where('status', 1)
            ->whereHas('homeProducts', fn($q) => $q->where('status', 'active'), '>=', 2)
            ->orderBy('home_sort_order', 'asc')
            ->get();

        $heroCategory = $categories->first();

        $stackedCategories = collect([]);

        return view('user.welcome', compact(
            'saleTimer',
            'video',
            'topSellingProducts',
            'featureProducts',
            'header',
            'trustPages',
            'categories',
            'heroCategory',
            'stackedCategories'
        ), ['activeSlug' => $heroCategory->slug ?? null]);
    }


    /**
     * Returns array with heroCategory and stackedCategories prepared for the blade.
     *
     * @param string|null $heroSlug
     * @return array
     */
    protected function getHomeLayoutData(?string $heroSlug): array
    {
        $heroCategory = null;
        if ($heroSlug) {
            $heroCategory = Category::where('slug', $heroSlug)
                ->where('status', 1)
                ->with(['homeProducts' => fn($q) => $q->where('status', 'active')->orderBy('sort_order')])
                ->first();
        }

        $stackedCategories = Category::where('status', 1)
            ->whereHas('homeProducts', fn($q) => $q->where('status', 'active'), '>=', 2)
            ->with(['homeProducts' => fn($q) => $q->where('status', 'active')->orderBy('sort_order')])
            ->when($heroCategory, fn($q) => $q->where('id', '!=', $heroCategory->id))
            ->orderBy('home_sort_order', 'asc')
            ->get();

        return [
            'heroCategory' => $heroCategory,
            'stackedCategories' => $stackedCategories,
        ];
    }


    public function fetchCategory(Request $request)
    {
        $slug = $request->query('category');
        if (!$slug) abort(400, 'Category is required');

        $heroCategory = Category::where('slug', $slug)
            ->where('status', 1)
            ->with(['homeProducts' => fn($q) => $q->where('status', 'active')->orderBy('sort_order')])
            ->first();

        // Only pass empty stacked categories
        $stackedCategories = collect([]);

        return view('user.components.home-product-partial', compact('heroCategory', 'stackedCategories'));
    }





    public function subscribe(Request $request)
    {
        Log::info('📩 Newsletter subscribe method called', [
            'request_email' => $request->email
        ]);

        try {
            $request->validate([
                'email' => 'required|email|unique:newsletter_subscribers,email',
            ]);

            $subscriber = NewsletterSubscriber::create([
                'email'           => $request->email,
                'token'           => Str::uuid(),
                'is_unsubscribed' => 0,
            ]);

            Log::info('✅ Subscriber created successfully', [
                'id'    => $subscriber->id,
                'email' => $subscriber->email,
                'token' => $subscriber->token,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Thanks for subscribing to our newsletter!',
                'email'   => $subscriber->email,
            ]);
        } catch (\Throwable $e) {
            Log::error('❌ Newsletter subscribe failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong while subscribing.',
            ], 500);
        }
    }



    public function unsubscribe($token)
    {
        $subscriber = NewsletterSubscriber::where('token', $token)->firstOrFail();

        $subscriber->update([
            'is_unsubscribed' => 1,
        ]);

        return view('user.welcome');
    }
}
