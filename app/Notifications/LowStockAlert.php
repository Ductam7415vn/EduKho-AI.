<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Collection;

class LowStockAlert extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Collection $equipments
    ) {}

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $mail = (new MailMessage)
            ->subject('Canh bao: Thiet bi sap het hang')
            ->greeting('Xin chao Admin!')
            ->line('Cac thiet bi sau dang o muc ton kho thap:');

        foreach ($this->equipments->take(10) as $equipment) {
            $mail->line("- {$equipment->name}: {$equipment->availableCount()} kha dung (nguong: {$equipment->low_stock_threshold})");
        }

        if ($this->equipments->count() > 10) {
            $mail->line("... va " . ($this->equipments->count() - 10) . " thiet bi khac.");
        }

        return $mail
            ->action('Xem danh sach thiet bi', url('/equipment'))
            ->line('Vui long kiem tra va bo sung kho hang.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'low_stock_alert',
            'message' => 'Co ' . $this->equipments->count() . ' thiet bi sap het hang.',
            'equipment_ids' => $this->equipments->pluck('id')->toArray(),
            'equipment_names' => $this->equipments->take(5)->pluck('name')->toArray(),
        ];
    }
}
