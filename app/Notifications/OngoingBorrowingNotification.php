<?php

namespace App\Notifications;

use App\Models\Borrowing;
use App\Models\BorrowingRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class OngoingBorrowingNotification extends Notification
{
    use Queueable;

    protected BorrowingRequest|Borrowing $borrowing;

    public function __construct(BorrowingRequest|Borrowing $borrowing)
    {
        $this->borrowing = $borrowing;
    }

    public function via($notifiable)
    {
        $channels = ['database'];

        if (\App\Models\Setting::get('enable_whatsapp_notifications', true) && $notifiable->whatsapp_number) {
            $channels[] = \App\Channels\WhatsAppChannel::class;
        }

        return $channels;
    }

    public function toArray($notifiable)
    {
        $isRequest = $this->borrowing instanceof BorrowingRequest;
        $itemName = $this->borrowing->item->nama ?? 'Barang';
        $dueDate = $this->borrowing->tanggal_kembali_rencana?->format('d M Y') ?? '-';

        return [
            'type' => 'ongoing',
            'message' => 'Anda sedang meminjam barang "' . $itemName . '". Harap kembalikan sebelum tanggal ' . $dueDate,
            'borrowing_request_id' => $isRequest ? $this->borrowing->id : null,
            'borrowing_id' => $isRequest ? null : $this->borrowing->id,
            'item_name' => $itemName,
            'due_date' => $this->borrowing->tanggal_kembali_rencana?->format('Y-m-d'),
            'status' => 'ongoing',
            'url' => route('user.dashboard'),
        ];
    }

    public function toWhatsApp($notifiable): string
    {
        $itemName = $this->borrowing->item->nama ?? 'Barang';
        $dueDate  = $this->borrowing->tanggal_kembali_rencana?->format('d M Y') ?? '-';
        $app      = config('app.name');

        return "📌 *Pengingat Peminjaman* — {$app}\n\n"
             . "Halo {$notifiable->name},\n"
             . "Anda sedang meminjam barang *{$itemName}*.\n\n"
             . "📅 Kembalikan sebelum: *{$dueDate}*\n\n"
             . "Pastikan barang dikembalikan tepat waktu.\n— Tim {$app}";
    }
}