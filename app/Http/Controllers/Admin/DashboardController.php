<?php



namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use App\Models\Referral;
use Carbon\CarbonPeriod;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller; 
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Request;

class DashboardController extends Controller
{
   
    public function index()
    {
        $ttl = 86400; 
        $totalUsers = Cache::remember(User::CACHE_TOTAL_USERS, $ttl, fn() => User::count());
        $totalOrders = Cache::remember(Order::CACHE_TOTAL_ORDERS, $ttl, fn() => Order::count());
        
        $totalProducts = Cache::remember('stats:products:count', $ttl, fn() => Product::count()); 
        
        $totalValue = Cache::remember(Order::CACHE_TOTAL_VALUE, $ttl, fn() => Order::sum('total_amount'));
        $totalRevenue = Cache::remember(Order::CACHE_REVENUE, $ttl, fn() => Order::where('status', 'delivered')->sum('total_amount'));
        
        $activeUsers = Cache::remember(User::CACHE_ACTIVE_USERS, $ttl, function() {
            return User::has('orders')->count();
        });
    
        $referralUsers = Cache::remember('stats:referrals:approved', $ttl, fn() => Referral::where('status', 'approved')->count());
    
    
        $now = Carbon::now();
        $startOfLastMonth = $now->copy()->subMonth()->startOfMonth();
        $endOfLastMonth   = $now->copy()->subMonth()->endOfMonth();
        $startOfCurrentMonth = $now->copy()->startOfMonth();
    
        $stats = Order::toBase()
            ->selectRaw("
                count(case when status = 'pending' then 1 end) as pending,
                count(case when status = 'deliverd' then 1 end) as completed,
                count(case when status = 'cancelled' then 1 end) as cancelled,
                count(case when status = 'return' then 1 end) as returned,
                sum(case when status = 'return' then total_amount else 0 end) as refund_amount
            ")
            ->whereBetween('created_at', [$startOfLastMonth, $endOfLastMonth])
            ->first();
    
        $pendingOrders   = $stats->pending ?? 0;
        $completedOrders = $stats->completed ?? 0;
        $cancelledOrders = $stats->cancelled ?? 0;
        $returnedOrders  = $stats->returned ?? 0;
        $refunds         = $stats->refund_amount ?? 0;
    
        $newUsersThisMonth = User::where('created_at', '>=', $startOfCurrentMonth)->count();
    
    
        $usersGrowth    = $this->calculateGrowth(User::class);
        $ordersGrowth   = $this->calculateGrowth(Order::class);
        $revenueGrowth  = $this->calculateGrowth(Order::class, 'total_amount', 'delivered');
        $productsGrowth = $this->calculateGrowth(Product::class);
        $refundGrowth   = $this->calculateGrowth(Order::class, 'total_amount', 'return');
        $referralGrowth = $this->calculateGrowth(Referral::class, null, 'approved');
    
    
        $recentOrders = Order::with('addresses', 'user')->orderByDesc('created_at')->take(5)->get();
    
        $topCustomers = User::withSum('orders', 'total_amount')
            ->withCount('orders')
            ->orderByDesc('orders_sum_total_amount')
            ->take(3)->get();
    
        $lowStockProducts = Product::select('id', 'name', 'stock_quantity', 'image')
            ->where('stock_quantity', '<=', 5)->take(5)->get();
    
        $topSellingProducts = Product::withCount('orderItems')
            ->orderByDesc('order_items_count')->take(3)->get();
    
        $recentProducts = Product::latest()->take(5)->get();
    
        return view('admin.dashboard', compact(
            'totalUsers', 'usersGrowth', 'totalOrders', 'ordersGrowth', 'totalValue', 
            'pendingOrders', 'completedOrders', 'cancelledOrders', 'returnedOrders',
            'totalRevenue', 'revenueGrowth', 'totalProducts', 'productsGrowth',
            'refunds', 'refundGrowth', 'referralUsers', 'referralGrowth',
            'recentOrders', 'newUsersThisMonth', 'activeUsers',
            'topCustomers', 'lowStockProducts', 'topSellingProducts', 'recentProducts'
        ));
    }

    public function chartData(Request $request)
    {
        $days = (int) $request->input('days', 7);
        $endDate = Carbon::now()->endOfDay();
        $startDate = Carbon::now()->subDays($days - 1)->startOfDay();

        $period = CarbonPeriod::create($startDate, $endDate);
        $dates = [];
        foreach ($period as $date) {
            $dates[$date->format('Y-m-d')] = 0; 
        }
    
        $orderStats = Order::toBase()
            ->selectRaw("
                DATE(created_at) as date,
                COUNT(*) as total_orders,
                SUM(CASE WHEN status = 'delivered' THEN total_amount ELSE 0 END) as total_revenue
            ")
            ->where('created_at', '>=', $startDate)
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy('date'); 
            
        $ordersFilled = $dates;
        $revenueFilled = $dates;
    
        foreach ($orderStats as $stat) {
            $ordersFilled[$stat->date] = $stat->total_orders;
            $revenueFilled[$stat->date] = $stat->total_revenue;
        }
    
        $usersData = User::toBase()
            ->selectRaw("DATE(created_at) as date, COUNT(*) as total")
            ->where('created_at', '>=', $startDate)
            ->groupBy('date')
            ->pluck('total', 'date');
    
        return response()->json([
            'orders'    => $ordersFilled,  
            'revenue'   => $revenueFilled, 
            'users'     => $usersData,    
            'referrals' => Referral::where('status', 'approved')->count(), 
        ]);
    }

    private function calculateGrowth($model, $columnToSum = null, $status = null)
{
    $now = now();
    $currentStart = $now->copy()->startOfMonth();
    $lastStart    = $now->copy()->subMonth()->startOfMonth();
    $lastEnd      = $now->copy()->subMonth()->endOfMonth();

    $query = $model::toBase();

    if ($status) {
        $query->where('status', $status);
    }

    $currentQuery = clone $query;
    $currentVal = $columnToSum 
        ? $currentQuery->where('created_at', '>=', $currentStart)->sum($columnToSum)
        : $currentQuery->where('created_at', '>=', $currentStart)->count();

    $lastQuery = clone $query;
    $lastVal = $columnToSum
        ? $lastQuery->whereBetween('created_at', [$lastStart, $lastEnd])->sum($columnToSum)
        : $lastQuery->whereBetween('created_at', [$lastStart, $lastEnd])->count();

    if ($lastVal == 0) {
        return $currentVal > 0 ? 100 : 0;
    }

    return round((($currentVal - $lastVal) / $lastVal) * 100, 1);
}
}