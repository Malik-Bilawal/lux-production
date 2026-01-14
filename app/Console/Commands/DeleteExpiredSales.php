<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Sale;
use Carbon\Carbon;

class DeleteExpiredSales extends Command
{
    protected $signature = 'sales:delete-expired';
    protected $description = 'Delete sales whose end time has passed';

    public function handle()
    {
        $now = Carbon::now();
        $deleted = Sale::where('end_time', '<=', $now)->delete();

        $this->info("Deleted $deleted expired sales.");
    }
}