<?php

namespace App\Http\Controllers\Admin\StoreSettings;

use Illuminate\Http\Request;
use App\Models\PaymentMethod;
use App\Http\Controllers\Controller;

class PaymentMethodsController extends Controller
{
    public function store(Request $request)
    {
        PaymentMethod::create($request->all());
        return back()->with('success', 'Payment Method added!');
    }

    public function update(Request $request, $id)
    {
        $method = PaymentMethod::findOrFail($id);
        $method->update($request->all());
        return back()->with('success', 'Payment Method updated!');
    }

    public function destroy($id)
    {
        PaymentMethod::destroy($id);
        return back()->with('success', 'Payment Method deleted!');
    }

    public function toggle($id)
    {
        $method = PaymentMethod::findOrFail($id);
        $method->status = $method->status === 'active' ? 'inactive' : 'active';
        $method->save();
        return back()->with('success', 'Payment Method status updated!');
    }

}
