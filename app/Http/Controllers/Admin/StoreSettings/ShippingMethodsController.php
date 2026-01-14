<?php

namespace App\Http\Controllers\Admin\StoreSettings;

use Illuminate\Http\Request;
use App\Models\ShippingMethod;
use App\Models\ShippingCountry;
use App\Http\Controllers\Controller;

class ShippingMethodsController extends Controller
{

    public function index(Request $request)
    {
        $countries = ShippingCountry::where('status', 'active')
            ->orderBy('name')
            ->get(['id', 'name', 'code']); 

        return response()->json([
            'success' => true,
            'data' => $countries,
        ]);
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'delivery_time' => 'required|string|max:255',
            'delivery_time_days' => 'required|integer|min:1',
            'cost' => 'required|numeric|min:0',
            'free_threshold' => 'nullable|numeric|min:0',
            'status' => 'required|in:active,inactive',
            'countries' => 'nullable|array',           // new field
            'countries.*' => 'exists:shipping_countries,id', // validate each ID
        ]);
    
        $method = ShippingMethod::create($request->only([
            'name', 'delivery_time', 'delivery_time_days', 'cost', 'free_threshold', 'status'
        ]));
    
        // Sync countries if provided
        if ($request->has('countries')) {
            $method->countries()->sync($request->countries);
        }
    
        return back()->with('success', 'Shipping Method added!');
    }
    

    public function update(Request $request, $id)
    {
        $method = ShippingMethod::findOrFail($id);
    
        $request->validate([
            'name' => 'required|string|max:255',
            'delivery_time' => 'required|string|max:255',
            'delivery_time_days' => 'required|integer|min:1',
            'cost' => 'required|numeric|min:0',
            'free_threshold' => 'nullable|numeric|min:0',
            'status' => 'required|in:active,inactive',
            'countries' => 'nullable|array',
            'countries.*' => 'exists:shipping_countries,id',
        ]);
    
        $method->update($request->only([
            'name', 'delivery_time', 'delivery_time_days', 'cost', 'free_threshold', 'status'
        ]));
    
        // Sync countries
        $method->countries()->sync($request->countries ?? []);
    
        return back()->with('success', 'Shipping Method updated!');
    }
    

    public function destroy($id)
    {
        ShippingMethod::destroy($id);
        return back()->with('success', 'Shipping Method deleted!');
    }
}
