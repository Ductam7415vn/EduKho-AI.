<?php

namespace App\Notifications;

use App\Models\BorrowRecord;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BorrowPendingApproval extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public BorrowRecord $borrowRecord
    ) {}

    public function via(object $notifiable): array
    {
        $channels = ['database'];

        if ($notifiable->wantsEmailNotification('pending_approval')) {
            $channels[] = 'mail';
        }

        return $channels;
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Phieu muon moi can phe duyet')
            ->greeting('Xin chao Admin!')
            ->line('Co phieu muon moi can phe duyet.')
            ->line('Giao vien: ' . $this->borrowRecord->user->name)
            ->line('Ngay muon: ' . $this->borrowRecord->borrow_date->format('d/m/Y'))
            ->action('Phe duyet ngay', url('/admin/approvals'))
            ->line('Vui long phe duyet som de giao vien co the nhan thiet bi.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'pending_approval',
            'borrow_record_id' => $this->borrowRecord->id,
            'message' => 'Phieu muon #' . $this->borrowRecord->id . ' tu ' . $this->borrowRecord->user->name . ' can phe duyet.',
            'teacher_name' => $this->borrowRecord->user->name,
        ];
    }
}
