<?php

namespace App\Notifications;

use App\Models\BorrowingRequest;
use App\Models\Setting;
use Illuminate\Bus\Queueable;

use Illuminate\Notifications\Notification;

class BorrowingExpiredNotification extends Notification
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

        $daysPast = (int) ceil($this->borrowingRequest->tanggal_pinjam->diffInDays(now(), true));

        return (new MailMessage)
            ->subject("⏰ Permintaan Peminjaman Kedaluwarsa — {$this->borrowingRequest->item->nama}")
            ->view('emails.notifications.borrowing_expired', [
                'notifiable'     => $notifiable,
                'borrowing'      => $this->borrowingRequest,
                'daysPast'       => $daysPast,
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
        $daysPast = (int) ceil($this->borrowingRequest->tanggal_pinjam->diffInDays(now(), true));

        return [
            'type'                 => 'expired',
            'message'              => 'Permintaan peminjaman "' . $this->borrowingRequest->item->nama . '" telah otomatis dibatalkan karena tanggal pinjam (' . $this->borrowingRequest->tanggal_pinjam->format('d M Y') . ') sudah terlewat ' . $daysPast . ' hari tanpa persetujuan admin.',
            'borrowing_request_id' => $this->borrowingRequest->id,
            'item_name'            => $this->borrowingRequest->item->nama,
            'borrow_date'          => $this->borrowingRequest->tanggal_pinjam->format('Y-m-d'),
            'days_past'            => $daysPast,
            'url'                  => route('user.borrowing.history'),
        ];
    }

    public function toWhatsApp($notifiable): string
    {
        $nama     = $this->borrowingRequest->item->nama;
        $daysPast = (int) ceil($this->borrowingRequest->tanggal_pinjam->diffInDays(now(), true));
        $tanggal  = $this->borrowingRequest->tanggal_pinjam->format('d M Y');
        $app      = config('app.name');

        return "⏰ *Peminjaman Kedaluwarsa* — {$app}\n\n"
             . "Halo {$notifiable->name},\n"
             . "Permintaan peminjaman *{$nama}* pada tanggal {$tanggal} telah *otomatis dibatalkan* karena sudah terlewat {$daysPast} hari tanpa persetujuan.\n\n"
             . "Silakan ajukan permintaan baru jika masih diperlukan.\n— Tim {$app}";
    }
}
