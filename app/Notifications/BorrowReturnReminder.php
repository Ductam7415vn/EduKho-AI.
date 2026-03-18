<?php

namespace App\Notifications;

use App\Models\BorrowRecord;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BorrowReturnReminder extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public BorrowRecord $borrowRecord,
        public int $daysUntilDue = 1
    ) {}

    public function via(object $notifiable): array
    {
        $channels = ['database'];

        if ($notifiable->wantsEmailNotification('borrow_reminder')) {
            $channels[] = 'mail';
        }

        return $channels;
    }

    public function toMail(object $notifiable): MailMessage
    {
        $dueDate = $this->borrowRecord->expected_return_date->format('d/m/Y');

        return (new MailMessage)
            ->subject('Nhac nho: Tra thiet bi vao ngay ' . $dueDate)
            ->greeting('Xin chao ' . $notifiable->name . '!')
            ->line('Day la loi nhac nho ve phieu muon #' . $this->borrowRecord->id . ' cua ban.')
            ->line('Han tra: ' . $dueDate . ' (con ' . $this->daysUntilDue . ' ngay)')
            ->line('Lop: ' . $this->borrowRecord->class_name)
            ->line('Mon: ' . $this->borrowRecord->subject)
            ->action('Xem chi tiet phieu muon', url('/borrow/' . $this->borrowRecord->id))
            ->line('Vui long tra thiet bi dung han de tranh bi qua han.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'borrow_return_reminder',
            'borrow_record_id' => $this->borrowRecord->id,
            'message' => 'Nhac nho: Phieu muon #' . $this->borrowRecord->id . ' se den han tra vao ' . $this->borrowRecord->expected_return_date->format('d/m/Y'),
            'days_until_due' => $this->daysUntilDue,
            'expected_return_date' => $this->borrowRecord->expected_return_date->format('d/m/Y'),
        ];
    }
}
