<?php

namespace App\Notifications;

use App\Models\BorrowingRequest;
use App\Models\Setting;
use Illuminate\Bus\Queueable;

use Illuminate\Notifications\Notification;

class BorrowingRejectedNotification extends Notification
{
    use Queueable;

    protected $borrowingRequest;

    public function __construct(BorrowingRequest $borrowingRequest)
    {
        $this->borrowingRequest = $borrowingRequest;
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

        return (new MailMessage)
            ->subject("❌ Peminjaman Ditolak — {$this->borrowingRequest->item->nama}")
            ->view('emails.notifications.borrowing_rejected', [
                'notifiable'     => $notifiable,
                'borrowing'      => $this->borrowingRequest,
                'actionUrl'      => route('user.borrowing.history'),
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
        return [
            'message'              => 'Permintaan peminjaman Anda untuk barang "' . $this->borrowingRequest->item->nama . '" telah ditolak.',
            'borrowing_request_id' => $this->borrowingRequest->id,
            'item_name'            => $this->borrowingRequest->item->nama,
            'status'               => 'rejected',
            'admin_notes'          => $this->borrowingRequest->admin_notes,
            'url'                  => route('user.borrowing.show', $this->borrowingRequest->id),
        ];
    }

    public function toWhatsApp($notifiable): string
    {
        $nama    = $this->borrowingRequest->item->nama;
        $catatan = $this->borrowingRequest->admin_notes ?: 'Tidak ada.';
        $app     = config('app.name');

        return "❌ *Peminjaman Ditolak* — {$app}\n\n"
             . "Halo {$notifiable->name},\n"
             . "Maaf, peminjaman barang *{$nama}* Anda *ditolak*.\n\n"
             . "📝 Alasan: {$catatan}\n\n"
             . "Hubungi admin untuk informasi lebih lanjut.\n— Tim {$app}";
    }
}