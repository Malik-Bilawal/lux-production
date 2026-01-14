<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\PromoCode;
use Carbon\Carbon;

class DeleteExpiredPromoCodes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'promo:delete-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete all expired promo codes automatically';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $deleted = PromoCode::where('end_date', '<', Carbon::now())->delete();

        $this->info("$deleted expired promo codes deleted.");
    }
}
