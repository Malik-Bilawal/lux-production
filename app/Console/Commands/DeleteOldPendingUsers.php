<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\PendingUser;
use Carbon\Carbon;

class DeleteOldPendingUsers extends Command
{
    protected $signature = 'pending-users:cleanup';
    protected $description = 'Delete pending users older than 24 hours';

    public function handle()
    {
        $cutoff = Carbon::now()->subHours(24);

        $deleted = PendingUser::where('created_at', '<', $cutoff)->delete();

        $this->info("Deleted {$deleted} pending users older than 24 hours.");
    }
}
