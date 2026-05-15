<?php

namespace App\Notifications;

use App\Models\Borrowing;
use App\Models\BorrowingRequest;
use App\Models\Setting;
use Illuminate\Bus\Queueable;

use Illuminate\Notifications\Notification;

class BorrowingCompletedNotification extends Notification
{
    use Queueable;

    protected BorrowingRequest|Borrowing $borrowing;

    public function __construct(BorrowingRequest|Borrowing $borrowing)
    {
        $this->borrowing = $borrowing;
    }

    public function via($notifiable): array
    {
        $channels = ['database'];

        if (Setting::get('enable_whatsapp_notifications', true) && $notifiable->whatsapp_number) {
            $channels[] = \App\Channels\WhatsAppChannel::class;
        }

        return $channels;
    }

    public function toMail($notifiable): MailMessage
    {
        $companyName    = Setting::get('company_name', config('app.name'));
        $companyTagline = Setting::get('company_tagline', '');
        $companyEmail   = Setting::get('company_email', '');
        $companyAddress = Setting::get('company_address', '');
        $logoPath       = Setting::get('company_logo', '');
        $companyLogo    = $logoPath ? url($logoPath) : null;

        $itemName = $this->borrowing->item->nama ?? 'Barang';

        return (new MailMessage)
            ->subject("🎉 Peminjaman Selesai — {$itemName}")
            ->view('emails.notifications.borrowing_completed', [
                'notifiable'     => $notifiable,
                'borrowing'      => $this->borrowing,
                'actionUrl'      => route('user.dashboard'),
                'companyName'    => $companyName,
                'companyTagline' => $companyTagline,
                'companyEmail'   => $companyEmail,
                'companyAddress' => $companyAddress,
                'companyLogo'    => $companyLogo,
                'appUrl'         => config('app.url'),
            ]);
    }

    public function toArray($notifiable): array
    {
        $isRequest = $this->borrowing instanceof BorrowingRequest;
        $itemName  = $this->borrowing->item->nama ?? 'Barang';

        return [
            'type'                 => 'completed',
            'message'              => 'Peminjaman barang "' . $itemName . '" telah selesai.',
            'borrowing_request_id' => $isRequest ? $this->borrowing->id : null,
            'borrowing_id'         => $isRequest ? null : $this->borrowing->id,
            'item_name'            => $itemName,
            'status'               => 'completed',
            'url'                  => route('user.dashboard'),
        ];
    }

    public function toWhatsApp($notifiable): string
    {
        $itemName = $this->borrowing->item->nama ?? 'Barang';
        $app      = config('app.name');

        return "🎉 *Peminjaman Selesai* — {$app}\n\n"
             . "Halo {$notifiable->name},\n"
             . "Peminjaman barang *{$itemName}* telah *selesai*. Terima kasih telah mengembalikan tepat waktu!\n\n"
             . "— Tim {$app}";
    }
}