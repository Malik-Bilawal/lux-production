<?php

namespace App\Http\Controllers\Admin\offerSale;

use App\Models\Sale;
use App\Models\Offer;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\NewsletterLog;
use App\Models\NewsletterCampaign;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\NewsletterSubscriber;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;

class OfferController extends Controller
{

    public function index()
    {

    
        // --- Sales ---
        $sales = Sale::all();

    
        // --- Check if sales exist ---
        $saleExists = Sale::exists();
    
    
        return view('admin.sales-offers.index', compact( 'sales', 'saleExists'));
    }
    
    

    public function create(){
        $categories = Category::select('id', 'name')->get();
        return view('admin.sales-offers.offer-create', compact('categories')); 
    }

    public function getProductsByCategory($categoryId)
{
    $products = Product::where('category_id', $categoryId)
                ->select('id', 'name')
                ->get();
    return response()->json($products);
}

//STORE
public function store(Request $request)
{
    $request->validate([
        'product_id'   => 'required|exists:products,id',
        'title'        => 'required|string|max:255',
        'description'  => 'required|string',
        'caption'      => 'nullable|string|max:255',
        'tags'         => 'nullable|string|max:255',
        'days'         => 'nullable|integer|min:0',
        'hours'        => 'nullable|integer|min:0|max:23',
        'minutes'      => 'nullable|integer|min:0|max:59',
        'seconds'      => 'nullable|integer|min:0|max:59',
    ]);

    // Timer start
    $timerStart = now();

    $timerEnd = (clone $timerStart)
        ->addDays((int) $request->days)
        ->addHours((int) $request->hours)
        ->addMinutes((int) $request->minutes)
        ->addSeconds((int) $request->seconds);
// Save offer
$offer = Offer::create([
    'product_id'  => $request->product_id,
    'title'       => $request->title,
    'description' => $request->description,
    'caption'     => $request->caption,
    'tags'        => $request->tags,
    'timer_start' => $timerStart,
    'timer_end'   => $timerEnd,
    'status'      => 'active',
]);


try {
    $count = NewsletterSubscriber::where('is_unsubscribed', 0)->count();

    NewsletterCampaign::create([
        'type'             => 'offer',
        'offer_id'          => $offer->id, 
        'subject'          => "Big Offer: {$offer->title}",
        'total_recipients' => $count,
        'status'           => 'draft', 
        'sent_count'       => 0,
    ]);

} catch (\Exception $e) {
    Log::error("Draft creation failed for Sale #{$offer->id}");
}

    return redirect()->route('admin.sales-offers.index')
        ->with('success', 'Offer created successfully.');
}

public function edit($id)
{
    $offer = Offer::findOrFail($id);
    $categories = Category::all();
    $products = Product::where('category_id', $offer->product->category_id)->get();

    return view('admin.sales-offers.offer-edit', compact('offer', 'categories', 'products'));
}
public function update(Request $request, $id)
{
    $request->validate([
        'product_id'   => 'required|exists:products,id',
        'title'        => 'required|string|max:255',
        'description'  => 'required|string',
        'caption'      => 'nullable|string|max:255',
        'tags'         => 'nullable|string|max:255',
        'days'         => 'nullable|integer|min:0',
        'hours'        => 'nullable|integer|min:0|max:23',
        'minutes'      => 'nullable|integer|min:0|max:59',
        'seconds'      => 'nullable|integer|min:0|max:59',
    ]);

    $offer = Offer::findOrFail($id);

    // Timer start
    $timerStart = now();
    $timerEnd = (clone $timerStart)
        ->addDays((int) $request->days)
        ->addHours((int) $request->hours)
        ->addMinutes((int) $request->minutes)
        ->addSeconds((int) $request->seconds);

    // Update record
    $offer->update([
        'product_id'  => $request->product_id,
        'title'       => $request->title,
        'description' => $request->description,
        'caption'     => $request->caption,
        'tags'        => $request->tags,
        'timer_start' => $timerStart,
        'timer_end'   => $timerEnd,
        'status'      => 'active',
    ]);

    return redirect()->route('admin.sales-offers.index')
        ->with('success', 'Offer updated successfully.');
}


public function destroy($id)
{
    $offer = Offer::findOrFail($id);
    $offer->delete();

    return redirect()->route('admin.sales-offers.index')
                     ->with('success', 'Offer deleted successfully.');
}


}
