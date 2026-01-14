<?php

namespace App\Http\Controllers\User\CustomerSupport;

use App\Models\Sale;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PromoCode;
use Illuminate\Support\Str;
use App\Models\OrderAddress;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\OrderCancellation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

class OrderTrackingController extends Controller
{

    private function resolveUser(Request $request): array
{
    if (Auth::check()) {
        return [Auth::id(), null];
    }

    $guestToken = $request->cookie('guest_token');

    if (!$guestToken) {
        $guestToken = Str::uuid()->toString();
        Cookie::queue('guest_token', $guestToken, 60 * 24 * 30); 
    }

    return [null, $guestToken];
}


    public function orderTracking()
    {
        return view("user.customer-support.track-order", );
    }

    public function trackOrder(Request $request)
    {
        try {
    
            try {
                $request->validate([
                    'tracking_code' => 'required|string',
                    'contact' => 'required|string',
                ]);
            } catch (\Illuminate\Validation\ValidationException $e) {
                Log::warning('❌ Validation failed for TrackOrder', [
                    'errors' => $e->errors(),
                    'payload' => $request->all(),
                ]);
                return response()->json(['success' => false, 'errors' => $e->errors()], 422);
            }
    
  
    
            // Fetch the order
            $order = Order::with(['items', 'addresses', 'shippingMethod', 'paymentMethod', 'promoCode', 'items.product'])
                ->where('tracking_code', $request->tracking_code)
                ->whereHas('addresses', function ($q) use ($request) {
                    $q->where('email', $request->contact)
                      ->orWhere('phone', $request->contact);
                })
                ->first();
    
            if (!$order) {             
                return response()->json(['success' => false, 'message' => 'No order found.']);
            }
    
            // Log found order details
    
    
            // Calculate subtotal safely
            $subtotal = $order->items->sum(fn($item) => $item->price * $item->quantity);
    
            // Build response with null-safe checks
            $response = [
                'success' => true,
                'order' => [
                    'id' => $order->id,
                    'tracking_code' => $order->tracking_code,
                    'order_code' => $order->order_code,
                    'status' => $order->status,
                    'placed_on' => $order->placed_at?->format('M d, Y'),
                    'delivered_at' => $order->delivered_at?->format('M d, Y'),
                    'delivery_estimate' => $order->estimated_delivery_time?->format('M d, Y'),
                    'subtotal' => $subtotal,
                    'shipping' => $order->shippingMethod?->cost ?? 0,
                    'promo_discount' => $order->promoCode?->discount_percent
                        ? $order->promoCode->discount_percent . '%'
                        : 'N/A',
                    'tax' => (float) $order->tax,
                    'total' => (float) $order->total_amount,
                    'shipping_method' => $order->shippingMethod?->name,
                    'payment_method' => $order->paymentMethod?->name,
                    'addresses' => $order->addresses ? [
    'type' => $order->addresses->type,
    'first_name' => $order->addresses->first_name,
    'last_name' => $order->addresses->last_name,
    'email' => $order->addresses->email,
    'phone' => $order->addresses->phone,
    'address_1' => $order->addresses->address_1,
    'address_2' => $order->addresses->address_2,
    'city' => $order->addresses->city,
    'state' => $order->addresses->state,
    'zip' => $order->addresses->zip,
    'country' => $order->addresses->country,
] : [],
                    'items' => $order->items->map(fn($item) => [
                        'name' => $item->product?->name ?? 'Unnamed Product',
                        'description' => $item->product?->description ?? '',
                        'image' => $item->product?->image_url ?? '/images/no-image.png',
                        'qty' => $item->quantity,
                        'price' => $item->price,
                        'total' => $item->price * $item->quantity,
                    ]),
                ],
            ];
    
    
            return response()->json($response);
    
        } catch (\Exception $e) {
            // Catch all exceptions and log full stack trace
     
    
            return response()->json([
                'success' => false,
                'message' => 'Server error occurred.',
            ], 500);
        }
    }
    
    public function cancel(Request $request)
    {
        \Log::info('🧾 Cancel request received', [
            'order_id' => $request->order_id,
            'reason' => $request->reason,
            'comment' => $request->comment,
            'user_authenticated' => auth()->check(),
            'auth_user_id' => auth()->id(),
        ]);
    
        // Fetch order
        $order = Order::find($request->order_id);
    
        if (!$order) {
            \Log::warning('❌ Order not found', [
                'order_id' => $request->order_id,
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Order not found',
            ], 404);
        }
    
        // Resolve user/guest identity
        [$userId, $guestToken] = $this->resolveUser($request);
    
        \Log::info('👤 User resolution', [
            'userId' => $userId,
            'guestToken' => $guestToken,
            'order_user_id' => $order->user_id,
            'order_guest_token' => $order->guest_token,
        ]);
    
        // Check authorization
        if ($userId) {
            if ($order->user_id !== $userId) {
                \Log::warning('🚫 Unauthorized: user_id mismatch', [
                    'expected_user_id' => $order->user_id,
                    'received_user_id' => $userId,
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized (user_id mismatch)'
                ], 403);
            }
        } else {
            if ($order->guest_token !== $guestToken) {
                \Log::warning('🚫 Unauthorized: guest_token mismatch', [
                    'expected_guest_token' => $order->guest_token,
                    'received_guest_token' => $guestToken,
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized (guest_token mismatch)'
                ], 403);
            }
        }
    
        // Status check
        if (!in_array($order->status, ['pending', 'processing'])) {
            \Log::info('⚠️ Order cannot be cancelled', ['status' => $order->status]);
            return response()->json([
                'success' => false,
                'message' => 'This order cannot be cancelled.'
            ]);
        }
    
        $request->validate([
            'reason' => 'required|string|max:255',
            'comment' => 'nullable|string|max:2000'
        ]);
    
        try {
            DB::transaction(function () use ($order, $request, $userId, $guestToken) {
                $order->update([
                    'status' => 'cancelled',
                    'notes' => ($order->notes ? $order->notes . "\n\n" : '') .
                               "Cancelled by user: {$request->reason}"
                ]);
    
                OrderCancellation::create([
                    'order_id' => $order->id,
                    'user_id' => $userId,
                    'guest_token' => $userId ? null : $guestToken,
                    'reason' => $request->reason,
                    'comment' => $request->comment,
                    'cancelled_by' => 'user',
                ]);
            });
    
            \Log::info('✅ Order cancelled successfully', [
                'order_id' => $order->id,
                'cancelled_by' => $userId ? "user_id: {$userId}" : "guest_token: {$guestToken}"
            ]);
    
            return response()->json([
                'success' => true,
                'message' => 'Your order has been cancelled.'
            ]);
    
        } catch (\Exception $e) {
            \Log::error('💥 Order cancellation failed', [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
    
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong: ' . $e->getMessage()
            ], 500);
        }
    }
    

    public function downloadInvoice(Order $order)
    {
        Log::info('downloadInvoice called', ['order_id' => $order->id]);
    
        try {
            if (in_array(strtolower($order->status), ['cancelled', 'refunded', 'cancellation requested'])) {
                Log::warning('Invoice not allowed', ['order_status' => $order->status]);
                return response()->json([
                    'success' => false,
                    'message' => 'Invoice not available for canceled/refunded orders.'
                ], 403);
            }
    
            $items = $order->items ?? [];
            Log::info('Order items', ['count' => count($items)]);
    
            $pdf = Pdf::loadView('user.customer-support.invoices.order-invoice', [
                'order' => $order,
                'items' => $items
            ]);
    
            Log::info('PDF generated successfully');
    
            return $pdf->download('invoice-'.$order->id.'.pdf');
        }catch (\Exception $e) {
            Log::error('Invoice generation failed', [
                'order_id' => $order->id,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
        
            // Temporarily return the actual exception message
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ], 500);
        }
        
    }
    


    public function reorder(Request $request, $order_code)
{
    $order = Order::with(['items.product', 'addresses'])
        ->where('order_code', $order_code)
        ->firstOrFail();

    [$userId, $guestToken] = $this->resolveUser($request);

    if ($userId) {
        if ($order->user_id !== $userId) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }
    } else {
        if ($order->guest_token !== $guestToken) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }
    }

    if (!in_array(strtolower($order->status), ['delivered', 'completed'])) {
        return response()->json([
            'success' => false,
            'message' => 'You can only reorder delivered or completed orders.'
        ], 403);
    }

    return DB::transaction(function () use ($order, $userId, $guestToken) {

        $lastOrderId  = Order::max('id') ?? 0;
        $orderCode    = 'ORD-' . str_pad($lastOrderId + 1, 6, '0', STR_PAD_LEFT);
        $trackingCode = 'LUX-' . strtoupper(Str::random(10));

        $newOrder = Order::create([
            'user_id'                 => $userId,
            'guest_token'             => $guestToken,
            'order_code'              => $orderCode,
            'tracking_code'           => $trackingCode,
            'total_amount'            => 0,
            'status'                  => 'pending',
            'payment_method_id'       => $order->payment_method_id, 
            'shipping_method_id'      => $order->shipping_method_id,
            'promo_code_id'           => $order->promo_code_id,
            'ip_address'              => request()->ip(),
            'placed_at'               => now(),
            'estimated_delivery_time' => $order->estimated_delivery_time,
        ]);

        if ($order->addresses) {
            $address = $order->addresses;
        
            OrderAddress::create([
                'order_id'   => $newOrder->id,
                'type'       => $address->type,
                'first_name' => $address->first_name,
                'last_name'  => $address->last_name,
                'email'      => $address->email,
                'phone'      => $address->phone,
                'address_1'  => $address->address_1,
                'address_2'  => $address->address_2,
                'city'       => $address->city,
                'state'      => $address->state,
                'zip'        => $address->zip,
                'country'    => $address->country,
            ]);
        }
        
        

      //  Clone items with current product price
$total = 0;
foreach ($order->items as $item) {
    $product = $item->product;
    if (!$product) continue;

    if ($product->stock_quantity < $item->quantity) {
        throw new \Exception("Product {$product->name} is out of stock.");
    }

    $currentPrice = $product->price;

    OrderItem::create([
        'order_id'   => $newOrder->id,
        'product_id' => $product->id,
        'quantity'   => $item->quantity,
        'price'      => $currentPrice,
        'subtotal'   => $currentPrice * $item->quantity,
    ]);

    $total += $currentPrice * $item->quantity;

    // Decrement stock
    $product->decrement('stock_quantity', $item->quantity);
}

-
$saleDiscount = 0;
if ($activeSale = Sale::getActiveSale()) {
    $saleDiscount = ($total * $activeSale->discount) / 100;
}

$amountAfterSale = $total - $saleDiscount;

$promoDiscount = 0;
if ($order->promo_code_id) {
    $promo = PromoCode::find($order->promo_code_id);
    if ($promo && $promo->isActive()) {
        $promoDiscount = $promo->calculateDiscount($amountAfterSale);

       

        if ($promoDiscount > 0) {
            $promo->increment('used_count');
        }
    } else {
       
        $newOrder->promo_code_id = null;
    }
}


// Amount after promo
$amountAfterDiscounts = max(0, $amountAfterSale - $promoDiscount);


$shippingCost = $order->shippingMethod?->cost ?? 0;

// Final total
$finalAmount = $amountAfterDiscounts + $shippingCost;

//update total
$newOrder->update([
    'total_amount'    => $finalAmount,
    'discount_amount' => $saleDiscount + $promoDiscount,
    'sale_discount'   => $saleDiscount,
    'promo_discount'  => $promoDiscount,
]);

       

        return response()->json([
            'success'   => true,
            'message'   => 'Your reorder has been placed successfully!',
            'order'     => $newOrder,
        ]);
    });
}

    
    
}
