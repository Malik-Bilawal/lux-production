<?php

namespace App\Http\Controllers\User;

use App\Models\Category;
use App\Models\ContactInfo;
use Illuminate\Http\Request;
use App\Models\ContactMessage;
use App\Http\Controllers\Controller;

class ContactController extends Controller
{
  public function index(){

    $categories = Category::where('status', 1)->get();
    
    $infos = ContactInfo::all();

    return view("user.contact", compact('categories', 'infos'));
    
  }
  public function store(Request $request)
  {
      $validated = $request->validate([
          'name' => 'required|string|max:255',
          'email' => 'required|email|max:255',
          'subject' => 'nullable|string|max:255',
          'message' => 'required|string|max:2000',
          'product_id' => 'nullable|exists:categories,id',
      ]);
  
      ContactMessage::create([
          'name' => $validated['name'],
          'email' => $validated['email'],
          'subject' => $validated['subject'] ?? null,
          'message' => $validated['message'],
          'product_id' => $validated['product_id'] ?? null,
          'status' => 'pending',
      ]);
  
      if ($request->ajax()) {
          return response()->json([
              'success' => true,
              'message' => 'Your message has been sent successfully!'
          ]);
      }
  
      return back()->with('success', 'Your message has been sent successfully!');
  }
}
