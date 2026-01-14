<?php

namespace App\Http\Controllers\User;

use App\Models\Sale;
use App\Models\Order;
use App\Models\Product;

use App\Models\OrderItem;
use App\Models\PromoCode;
use Illuminate\Support\Str;
use App\Models\OrderAddress;
use Illuminate\Http\Request;
use App\Models\OrderCancellation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf; // make sure to import

class OrderHistoryController extends Controller
{ 
    
    public function index(Request $request)
    {
        $query = Order::with(['items.product', 'paymentMethod'])
            ->where('user_id', auth()->id());
    
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('order_code', 'like', "%{$search}%")
                  ->orWhereHas('items.product', function($q2) use ($search) {
                      $q2->where('name', 'like', "%{$search}%");
                  });
            });
        }
    
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
    
        if ($request->filled('days')) {
            $query->where('placed_at', '>=', now()->subDays($request->days));
        }
    
        $orders = $query->latest()->paginate(5);
    
        $totalOrders   = Order::where('user_id', auth()->id())->count();
        $delivered     = Order::where('user_id', auth()->id())->where('status', 'delivered')->count();
        $processing    = Order::where('user_id', auth()->id())->where('status', 'processing')->count();
        $cancelled     = Order::where('user_id', auth()->id())->where('status', 'cancelled')->count(); // optional
    
        return view('user.customer-support.order-history', compact(
            'orders', 'totalOrders', 'delivered', 'processing', 'cancelled'
        ));
    }
        public function downloadInvoice($order_code)
    {
        $order = Order::where('order_code', $order_code)->firstOrFail();
    
        if ($order->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }
    
        if (in_array($order->status, ['cancelled', 'refunded'])) {
            return redirect()->back()->with('error', 'Invoice not available for cancelled or refunded orders.');
        }
    
        $pdf = Pdf::loadView('user.customer-support.invoices.order-invoice', compact('order'));
        return $pdf->download('Invoice-Order-'.$order->id.'.pdf');
    }

    
    
    public function cancel(Request $request)
    {
       
    
        $order = Order::findOrFail($request->order_id);
    
        // ensure owner
        if ($order->user_id !== auth()->id()) {
           
            abort(403, 'Unauthorized');
        }
    
        // only allow cancelling if current status is pending or processing
        if (!in_array($order->status, ['pending', 'processing'])) {
            
            return back()->with('error', 'This order cannot be cancelled.');
        }
    
        $request->validate([
            'reason' => 'required|string|max:255',
            'comment' => 'nullable|string|max:2000'
        ]);
    
        DB::transaction(function () use ($order, $request) {
    
            // update order status
            $order->update([
                'status' => 'cancelled',
                'notes' => ($order->notes ? $order->notes . "\n\n" : '') .
                           "Cancelled by user: {$request->reason}"
            ]);
    
            OrderCancellation::create([
                'order_id' => $order->id,
                'user_id' => auth()->id(),
                'reason' => $request->reason,
                'comment' => $request->comment,
                'cancelled_by' => 'user',
            ]);
        });
    
    
        return redirect()->back()->with('success', 'Your order has been cancelled.');
    }
    
    public function reorder($order_code)
    {
        try {
            $order = Order::with(['items.product', 'addresses'])
                ->where('order_code', $order_code)
                ->firstOrFail();
    
            if ($order->user_id !== auth()->id()) {
                abort(403, 'Unauthorized action.');
            }
    
            if (!in_array($order->status, ['delivered', 'completed'])) {
                return back()->with('error', 'You can only reorder completed or delivered orders.');
            }
    
            // Log immediately after fetching the order
            \Log::info('Reorder started', ['order_id' => $order->id, 'order_code' => $order_code]);
    
            return DB::transaction(function () use ($order, $order_code) {
                try {
                    $lastOrderId  = Order::max('id') ?? 0;
                    $orderCode    = 'ORD-' . str_pad($lastOrderId + 1, 6, '0', STR_PAD_LEFT);
                    $trackingCode = 'LUX-' . strtoupper(Str::random(10));
    
                    $newOrder = Order::create([
                        'user_id'                 => $order->user_id,
                        'guest_token'             => $order->guest_token,
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
    
                    // Safely process addresses
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
                    \Log::info('Addresses processed', ['count' => $order->addresses->count()]);
    
                    // Clone items
                    $total = 0;
                    foreach ($order->items ?? [] as $item) {
                        $product = $item->product;
                        if (!is_object($product)) {
                            \Log::warning('Invalid product in items', ['item_id' => $item->id ?? null]);
                            continue;
                        }
    
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
    
                        $product->decrement('stock_quantity', $item->quantity);
                    }
    
                    \Log::info('Items processed', ['total' => $total]);
    
                    return redirect()
                        ->route('user.checkout.success', $newOrder->order_code)
                        ->with('success', 'Your reorder has been placed successfully!');
    
                } catch (\Throwable $e) {
                    \Log::error('Transaction failed', [
                        'order_code' => $order_code,
                        'message'    => $e->getMessage(),
                        'trace'      => $e->getTraceAsString(),
                    ]);
                    throw $e; // rethrow to rollback
                }
            });
    
        } catch (\Throwable $e) {
            \Log::error('Reorder failed', [
                'order_code' => $order_code,
                'message'    => $e->getMessage(),
                'trace'      => $e->getTraceAsString(),
            ]);
    
            return response()->json([
                'success' => false,
                'message' => 'Reorder failed: ' . $e->getMessage()
            ], 500);
        }
    }
    


}
