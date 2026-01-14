<?php

namespace App\Http\Controllers\Admin\StoreSettings;

use Illuminate\Http\Request;
use App\Models\ShippingCountry;
use App\Http\Controllers\Controller;

class CountriesController extends Controller
{
  
    // 🔹 Store new country
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:10|unique:shipping_countries,code',
            'shipping_rate' => 'required|numeric|min:0',
            'free_shipping_threshold' => 'required|numeric|min:0',
            'status' => 'required|in:active,inactive',
        ]);

        ShippingCountry::create($request->all());
        return redirect()->back()->with('success', 'Country added successfully!');
    }

    public function update(Request $request, $id)
    {
        $country = ShippingCountry::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:10|unique:shipping_countries,code,' . $country->id,
            'shipping_rate' => 'required|numeric|min:0',
            'free_shipping_threshold' => 'required|numeric|min:0',
            'status' => 'required|in:active,inactive',
        ]);

        $country->update($request->all());
        return redirect()->back()->with('success', 'Country updated successfully!');
    }

    public function destroy($id)
    {
        $country = ShippingCountry::findOrFail($id);
        $country->delete();
        return redirect()->back()->with('success', 'Country deleted successfully!');
    }
}


