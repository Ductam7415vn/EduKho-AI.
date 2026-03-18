<?php

namespace App\Notifications;

use App\Models\BorrowRecord;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BorrowApproved extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public BorrowRecord $borrowRecord
    ) {}

    public function via(object $notifiable): array
    {
        $channels = ['database'];

        if ($notifiable->wantsEmailNotification('borrow_approved')) {
            $channels[] = 'mail';
        }

        return $channels;
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Phieu muon da duoc phe duyet')
            ->greeting('Xin chao ' . $notifiable->name . '!')
            ->line('Phieu muon #' . $this->borrowRecord->id . ' cua ban da duoc phe duyet.')
            ->line('Ngay muon: ' . $this->borrowRecord->borrow_date->format('d/m/Y'))
            ->line('Tiet: ' . $this->borrowRecord->period)
            ->action('Xem chi tiet', url('/borrow/' . $this->borrowRecord->id))
            ->line('Vui long den kho de nhan thiet bi dung gio.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'borrow_approved',
            'borrow_record_id' => $this->borrowRecord->id,
            'message' => 'Phieu muon #' . $this->borrowRecord->id . ' da duoc phe duyet.',
            'borrow_date' => $this->borrowRecord->borrow_date->format('d/m/Y'),
        ];
    }
}
