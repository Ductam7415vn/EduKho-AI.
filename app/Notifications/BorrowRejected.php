<?php

namespace App\Notifications;

use App\Models\BorrowRecord;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BorrowRejected extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public BorrowRecord $borrowRecord
    ) {}

    public function via(object $notifiable): array
    {
        $channels = ['database'];

        if ($notifiable->wantsEmailNotification('borrow_rejected')) {
            $channels[] = 'mail';
        }

        return $channels;
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Phieu muon bi tu choi')
            ->greeting('Xin chao ' . $notifiable->name . '!')
            ->line('Phieu muon #' . $this->borrowRecord->id . ' cua ban da bi tu choi.')
            ->line('Ly do: ' . $this->borrowRecord->rejection_reason)
            ->action('Xem chi tiet', url('/borrow/' . $this->borrowRecord->id))
            ->line('Vui long lien he admin de biet them chi tiet.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'borrow_rejected',
            'borrow_record_id' => $this->borrowRecord->id,
            'message' => 'Phieu muon #' . $this->borrowRecord->id . ' bi tu choi: ' . $this->borrowRecord->rejection_reason,
        ];
    }
}
