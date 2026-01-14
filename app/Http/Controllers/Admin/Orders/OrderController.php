<?php
namespace App\Http\Controllers\Admin\Orders;

use Carbon\Carbon;
use App\Models\Sale;
use App\Models\Order;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

class OrderController extends Controller
{

    public function index(Request $request)
    {

        $totalOrders     = Cache::remember(Order::CACHE_TOTAL_ORDERS, 300, fn() => Order::count());
        $pendingOrders   = Cache::remember(Order::CACHE_COUNT_PENDING, 300, fn() => Order::where('status', 'pending')->count());
        $completedOrders = Cache::remember('stats:orders:delivered', 300, fn() => Order::where('status', 'delivered')->count());
        $cancelledOrders = Cache::remember('stats:orders:cancelled', 300, fn() => Order::where('status', 'cancelled')->count());


        $lastMonthStart = Carbon::now()->subMonth()->startOfMonth();
        $lastMonthEnd   = Carbon::now()->subMonth()->endOfMonth();

        $lastMonthStats = Order::toBase()
            ->selectRaw("
                count(*) as total,
                count(case when status = 'pending' then 1 end) as pending,
                count(case when status = 'delivered' then 1 end) as completed,
                count(case when status = 'cancelled' then 1 end) as cancelled
            ")
            ->whereBetween('created_at', [$lastMonthStart, $lastMonthEnd])
            ->first();

        // Calculate Percentages (Clean Helper Logic)
        $totalOrdersChange     = $this->calcChange($totalOrders, $lastMonthStats->total);
        $pendingOrdersChange   = $this->calcChange($pendingOrders, $lastMonthStats->pending);
        $completedOrdersChange = $this->calcChange($completedOrders, $lastMonthStats->completed);
        $cancelledOrdersChange = $this->calcChange($cancelledOrders, $lastMonthStats->cancelled);



        $query = Order::query()->select('orders.*'); 

        // Eager Load relations for the View
        $query->with(['user:id,name,email', 'items.product', 'shippingMethod', 'paymentMethod']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('orders.order_code', 'like', "$search%") 
                  ->orWhere('orders.id', $search); 

                $q->orWhereHas('user', function($q2) use ($search) {
                    $q2->where('name', 'like', "$search%")
                       ->orWhere('email', 'like', "$search%");
                });

                $q->orWhereHas('addresses', function($q3) use ($search) {
                    $q3->where('first_name', 'like', "$search%")
                       ->orWhere('last_name', 'like', "$search%")
                       ->orWhere('phone', 'like', "$search%")
                       ->orWhere('city', 'like', "$search%");
                });
            });
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        switch ($request->sort) {
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            case 'price-high':
                $query->orderBy('total_amount', 'desc');
                break;
            case 'price-low':
                $query->orderBy('total_amount', 'asc');
                break;
            case 'customer':
        

                $query->join('users', 'orders.user_id', '=', 'users.id')
                      ->orderBy('users.name', 'asc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
        }

        $orders = $query->paginate(15)->appends($request->all());

        return view('admin.orders.orders', compact(
            'orders',
            'totalOrders', 'totalOrdersChange',
            'pendingOrders', 'pendingOrdersChange',
            'completedOrders', 'completedOrdersChange',
            'cancelledOrders', 'cancelledOrdersChange'
        ));
    }

    public function show(Order $order)
    {
        $sale = Cache::remember('active_sale', 3600, fn() => Sale::where('status', 'active')->first());

        $order->load([
            'addresses', 
            'items.product', 
            'paymentMethod:id,name', 
            'shippingMethod:id,name', 
            'promoCode', 
            'user:id,name,email,'
        ]);

        return view('admin.orders.order-detail', compact('order', 'sale'));
    }


    private function calcChange($current, $previous)
    {
        if (!$previous || $previous == 0) {
            return $current > 0 ? 100 : 0;
        }
        return round((($current - $previous) / $previous) * 100);
    }


    // app/Http/Controllers/Admin/OrderController.php
public function updateStatus(Request $request, Order $order)
{
    $request->validate([
        'status' => 'required|in:pending,processing,shipped,delivered,cancelled,return',
    ]);

    $order->status = $request->status;
    $order->save();

    return response()->json([
        'success' => true,
        'message' => 'Order status updated successfully.',
        'status' => $order->status
    ]);
}

public function downloadInvoice($id)
{
    $order = Order::with(['items.product', 'user', 'shippingMethod', 'paymentMethod'])->findOrFail($id);

    $pdf = Pdf::loadView('admin.orders.invoice', compact('order'));

    return $pdf->download('invoice-'.$order->order_code.'.pdf');
}

    
    

public function updateEstimatedDelivery(Request $request, Order $order)
{
    $data = $request->validate([
        'days' => 'required|integer|min:0|max:30',
        'hours' => 'required|integer|min:0|max:23',
    ]);

    // Cast to int
    $days = (int) $data['days'];
    $hours = (int) $data['hours'];

    // Calculate end datetime
    $endTime = now()
        ->addDays($days)
        ->addHours($hours);

    $order->update([
        'estimated_delivery_time' => $endTime->format('Y-m-d H:i:s'),
    ]);

    return redirect()->back()->with('success', 'Estimated delivery updated!');
}




}