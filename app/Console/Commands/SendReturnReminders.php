<?php

namespace App\Console\Commands;

use App\Models\BorrowRecord;
use App\Notifications\BorrowReturnReminder;
use Illuminate\Console\Command;

class SendReturnReminders extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'borrow:send-reminders {--days=1 : Days before due date}';

    /**
     * The console command description.
     */
    protected $description = 'Send reminders to users about upcoming equipment returns';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $days = (int) $this->option('days');
        $targetDate = now()->addDays($days)->toDateString();

        $records = BorrowRecord::query()
            ->where('status', 'active')
            ->whereIn('approval_status', ['auto_approved', 'approved'])
            ->whereDate('expected_return_date', $targetDate)
            ->with('user')
            ->get();

        $count = 0;

        foreach ($records as $record) {
            // Check if user wants to receive reminder emails
            if ($record->user->wantsEmailNotification('borrow_reminder')) {
                $record->user->notify(new BorrowReturnReminder($record, $days));
                $count++;
            }
        }

        $this->info("Sent {$count} return reminder(s) for equipment due in {$days} day(s).");

        return Command::SUCCESS;
    }
}
