<?php

namespace App\Notifications;

use App\Models\BorrowRecord;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BorrowOverdue extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public BorrowRecord $borrowRecord
    ) {}

    public function via(object $notifiable): array
    {
        $channels = ['database'];

        if ($notifiable->wantsEmailNotification('borrow_overdue')) {
            $channels[] = 'mail';
        }

        return $channels;
    }

    public function toMail(object $notifiable): MailMessage
    {
        $daysOverdue = now()->diffInDays($this->borrowRecord->expected_return_date);

        return (new MailMessage)
            ->subject('Nhac nho: Phieu muon qua han')
            ->greeting('Xin chao ' . $notifiable->name . '!')
            ->line('Phieu muon #' . $this->borrowRecord->id . ' cua ban da qua han ' . $daysOverdue . ' ngay.')
            ->line('Ngay muon: ' . $this->borrowRecord->borrow_date->format('d/m/Y'))
            ->line('Han tra: ' . $this->borrowRecord->expected_return_date->format('d/m/Y'))
            ->action('Xem chi tiet', url('/borrow/' . $this->borrowRecord->id))
            ->line('Vui long tra thiet bi som nhat co the.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'borrow_overdue',
            'borrow_record_id' => $this->borrowRecord->id,
            'message' => 'Phieu muon #' . $this->borrowRecord->id . ' da qua han tra.',
            'expected_return_date' => $this->borrowRecord->expected_return_date->format('d/m/Y'),
            'days_overdue' => now()->diffInDays($this->borrowRecord->expected_return_date),
        ];
    }
}
