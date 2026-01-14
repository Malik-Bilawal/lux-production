<?php

    namespace App\Http\Controllers\Admin\StoreSettings;
    
    use App\Http\Controllers\Controller;
    use Illuminate\Http\Request;
    use App\Models\PromoCode;
    
    class PromoCodeController extends Controller
    {
        public function store(Request $request)
        {
            $request->validate([
                'code' => 'required|unique:promo_codes,code',
                'discount_percent' => 'required|integer|min:0|max:100',
                'usage_limit' => 'nullable|integer|min:0',
                'valid_days' => 'required|integer|min:1',
                'status' => 'required|in:active,inactive',
            ]);
    
            $start = now();
            $end = now()->addDays((int) $request->valid_days);
    
            PromoCode::create([
                'code' => $request->code,
                'discount_percent' => (int) $request->discount_percent,
                'usage_limit' => $request->usage_limit ?? 0,
                'used_count' => 0,
                'start_date' => $start,
                'end_date' => $end,
                'status' => $request->status,
            ]);
    
            return redirect()->back()->with('success', 'Promo code created successfully!');
        }
    
        // Update existing promo code
        public function update(Request $request, $id)
        {
            $promo = PromoCode::findOrFail($id);
    
            $request->validate([
                'code' => 'required|unique:promo_codes,code,' . $promo->id,
                'discount_percent' => 'required|integer|min:0|max:100',
                'usage_limit' => 'nullable|integer|min:0',
                'valid_days' => 'required|integer|min:1',
                'status' => 'required|in:active,inactive',
            ]);
    
            $promo->update([
                'code' => $request->code,
                'discount_percent' => (int) $request->discount_percent,
                'usage_limit' => $request->usage_limit ?? 0,
                'end_date' => $promo->start_date->copy()->addDays((int) $request->valid_days),
                'status' => $request->status,
            ]);
    
            return redirect()->back()->with('success', 'Promo code updated successfully!');
        }
    
        public function destroy($id)
        {
            $promo = PromoCode::findOrFail($id);
            $promo->delete();
    
            return redirect()->back()->with('success', 'Promo code deleted successfully!');
        }
    }
    


