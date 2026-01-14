<?php

namespace App\Observers;

use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

class OrderObserver
{
    public function created(Order $order): void
    {
        // 1. Global Totals
        Cache::forget(Order::CACHE_TOTAL_ORDERS);
        Cache::forget(Order::CACHE_TOTAL_VALUE);

        // 2. Status Counts (New orders are usually pending)
        Cache::forget(Order::CACHE_COUNT_PENDING); 

        // 3. User Activity
        Cache::forget(User::CACHE_ACTIVE_USERS); 
    }

    public function updated(Order $order): void
    {
        // If Status OR Amount changes, we have work to do.
        if ($order->isDirty('status') || $order->isDirty('total_amount')) {
            
            // 1. Money Stats
            Cache::forget(Order::CACHE_REVENUE);
            Cache::forget(Order::CACHE_TOTAL_VALUE);

            // 2. Status Stats
            // Since the status changed (e.g. Pending -> Delivered), 
            // BOTH the old status count and the new status count are wrong.
            // It is safest and easiest to just clear all three status counters.
            Cache::forget(Order::CACHE_COUNT_PENDING);
            Cache::forget(Order::CACHE_COUNT_DELIVERED);
            Cache::forget(Order::CACHE_COUNT_CANCELLED);
        }
    }

    public function deleted(Order $order): void
    {
        // Nuke everything
        Cache::forget(Order::CACHE_TOTAL_ORDERS);
        Cache::forget(Order::CACHE_TOTAL_VALUE);
        Cache::forget(Order::CACHE_REVENUE);
        
        Cache::forget(Order::CACHE_COUNT_PENDING);
        Cache::forget(Order::CACHE_COUNT_DELIVERED);
        Cache::forget(Order::CACHE_COUNT_CANCELLED);
        
        Cache::forget(User::CACHE_ACTIVE_USERS);
    }
}