<?php

namespace App\Http\Controllers\Admin\StoreSettings;

use App\Models\PromoCode;
use Illuminate\Http\Request;
use App\Models\PaymentMethod;
use App\Models\ShippingMethod;
use App\Models\ShippingCountry;
use App\Http\Controllers\Controller;

class StoreSettingsController extends Controller
{
    public function index()
    {
        $countries = ShippingCountry::all(); 
        $totalCountries = $countries->count(); 
        $activeCountries = $countries->where('status', 'active')->count(); 
        $inactiveCountries = $countries->where('status', 'inactive')->count();

        $paymentMethods = PaymentMethod::all(); 
        $shippingMethods = ShippingMethod::all(); 
        $promos = PromoCode::orderBy('created_at', 'desc')->get();


        return view('admin.store-settings',compact('countries', 'promos', 'totalCountries', 'activeCountries', 'inactiveCountries','paymentMethods','shippingMethods'));
    }
}
