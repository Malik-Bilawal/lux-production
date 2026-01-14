<?php

namespace App\Http\Controllers\Admin;

use App\Models\Review;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class ReviewsManagementController extends Controller
{
    public function index()
    {
        $reviews = Review::with('images', 'product', 'order.addresses')->latest()->paginate(5);
        return view('admin.reviews', compact('reviews'));
    }

    // Approve review
    public function approve($id)
    {
        $review = Review::findOrFail($id);
        $review->update(['status' => 'approved']);
        return back()->with('success', 'Review approved successfully!');
    }

    // Reject review
    public function reject($id)
    {
        $review = Review::findOrFail($id);
        $review->update(['status' => 'rejected']);
        return back()->with('error', 'Review rejected successfully!');
    }


    public function reply(Request $request)
{
    $request->validate([
        'review_id' => 'required|exists:reviews,id',
        'response'  => 'required|string',
    ]);

    $review = Review::findOrFail($request->review_id);
    $review->response = $request->response;
    $review->status = 'responded'; // or whatever value you use
    $review->responded_at = now();
    $review->save();

    return redirect()->back()->with('success', 'Reply sent successfully!');
}

public function destroy($id)
{
    $review = Review::with('images')->findOrFail($id);

    // Delete the entire folder with all images
    $reviewFolder = "reviews/{$review->id}";
    if (Storage::disk('public')->exists($reviewFolder)) {
        Storage::disk('public')->deleteDirectory($reviewFolder);
    }

    // Delete the image records from review_images table
    if ($review->images && $review->images->count() > 0) {
        $review->images()->delete();
    }

    // Delete the review itself
    $review->delete();

    return back()->with('success', 'Review and its images deleted successfully!');
}

}
