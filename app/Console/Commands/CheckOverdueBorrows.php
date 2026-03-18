<?php

namespace App\Console\Commands;

use App\Models\BorrowRecord;
use App\Notifications\BorrowOverdue;
use Illuminate\Console\Command;

class CheckOverdueBorrows extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'borrow:check-overdue';

    /**
     * The console command description.
     */
    protected $description = 'Check for overdue borrow records and send notifications';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $overdueRecords = BorrowRecord::query()
            ->where('status', 'active')
            ->whereIn('approval_status', ['auto_approved', 'approved'])
            ->where('expected_return_date', '<', now())
            ->with('user')
            ->get();

        $count = 0;

        foreach ($overdueRecords as $record) {
            // Update status to overdue
            $record->update(['status' => 'overdue']);

            // Send notification to borrower
            $record->user->notify(new BorrowOverdue($record));

            $count++;
        }

        $this->info("Processed {$count} overdue borrow records.");

        return Command::SUCCESS;
    }
}
