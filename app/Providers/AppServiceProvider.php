<?php

namespace App\Providers;

use App\Models\Sale;
use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use App\Observers\UserObserver;
use App\Observers\OrderObserver;
use App\Observers\CachingObserver;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Spatie\Activitylog\Models\Activity;
use App\Observers\NewsLetter\NewsletterSaleObserver;
use App\Observers\NewsLetter\NewsletterProductObserver;

class AppServiceProvider extends ServiceProvider
{

    public function register(): void
    {
        
    }


    public function boot(): void
    {

        Order::observe(OrderObserver::class);
        User::observe(UserObserver::class);

        Product::observe(NewsletterProductObserver::class);
        Sale::observe(NewsletterSaleObserver::class);
    

        View::addNamespace('auth', resource_path('views/users/auth'));
        Activity::saving(function ($activity) {
            if (request()) {
                $activity->ip = request()->ip(); 
            }
        });

        Paginator::useTailwind();
    }
}
