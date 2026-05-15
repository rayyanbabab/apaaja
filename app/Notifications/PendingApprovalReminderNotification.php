<?php

namespace App\Notifications;

use App\Models\Setting;
use Illuminate\Bus\Queueable;

use Illuminate\Notifications\Notification;

class PendingApprovalReminderNotification extends Notification
{
    use Queueable;

    protected $pendingCount;
    protected $oldestPendingHours;

    public function __construct(int $pendingCount, int $oldestPendingHours)
    {
        $this->pendingCount       = $pendingCount;
        $this->oldestPendingHours = $oldestPendingHours;
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
            ->subject("⏳ {$this->pendingCount} Pengajuan Menunggu Persetujuan Anda")
            ->view('emails.notifications.pending_reminder', [
                'notifiable'         => $notifiable,
                'pendingCount'       => $this->pendingCount,
                'oldestPendingHours' => $this->oldestPendingHours,
                'actionUrl'          => route('admin.borrowing-requests.index'),
                'companyName'        => $companyName,
                'companyTagline'     => $companyTagline,
                'companyEmail'       => $companyEmail,
                'companyAddress'     => $companyAddress,
                'companyLogo'        => $companyLogo,
                'appUrl'             => config('app.url'),
            ]);
    }

    public function toArray($notifiable): array
    {
        return [
            'type'                 => 'pending_reminder',
            'message'              => 'Ada ' . $this->pendingCount . ' pengajuan peminjaman yang belum disetujui. Pengajuan tertua sudah menunggu ' . $this->oldestPendingHours . ' jam.',
            'pending_count'        => $this->pendingCount,
            'oldest_pending_hours' => $this->oldestPendingHours,
            'url'                  => route('admin.borrowing-requests.index'),
        ];
    }

    public function toWhatsApp($notifiable): string
    {
        $app = config('app.name');

        return "⏳ *{$this->pendingCount} Pengajuan Menunggu Persetujuan* — {$app}\n\n"
             . "Halo Admin,\n"
             . "Ada *{$this->pendingCount} pengajuan peminjaman* yang belum disetujui.\n\n"
             . "🕒 Pengajuan tertua sudah menunggu *{$this->oldestPendingHours} jam*.\n\n"
             . "Segera masuk ke panel admin untuk meninjau.\n— Tim {$app}";
    }
}
