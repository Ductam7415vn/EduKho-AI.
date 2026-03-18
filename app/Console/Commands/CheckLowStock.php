<?php

namespace App\Console\Commands;

use App\Models\Equipment;
use App\Models\User;
use App\Notifications\LowStockAlert;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;

class CheckLowStock extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'equipment:check-low-stock';

    /**
     * The console command description.
     */
    protected $description = 'Check for equipment with low stock and notify admins';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $lowStockEquipments = Equipment::physical()
            ->get()
            ->filter(function ($equipment) {
                return $equipment->availableCount() <= $equipment->low_stock_threshold;
            });

        if ($lowStockEquipments->isEmpty()) {
            $this->info('No low stock equipment found.');
            return Command::SUCCESS;
        }

        // Send notification to all admins
        $admins = User::where('role', 'admin')->get();

        if ($admins->isEmpty()) {
            $this->warn('No admins found to notify.');
            return Command::SUCCESS;
        }

        Notification::send($admins, new LowStockAlert($lowStockEquipments));

        $this->info("Found {$lowStockEquipments->count()} equipment(s) with low stock. Notified {$admins->count()} admin(s).");

        return Command::SUCCESS;
    }
}
