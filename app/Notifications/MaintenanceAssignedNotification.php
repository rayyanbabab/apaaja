<?php

namespace App\Notifications;

use App\Models\Maintenance;
use App\Models\Setting;
use Illuminate\Bus\Queueable;

use Illuminate\Notifications\Notification;

/**
 * Dikirim ke semua Operator ketika Admin menambahkan barang maintenance.
 */
class MaintenanceAssignedNotification extends Notification
{
    use Queueable;

    public function __construct(
        protected Maintenance $maintenance,
        protected string $adminName
    ) {}

    public function via(object $notifiable): array
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

        $itemName = $this->maintenance->item->nama ?? 'Barang tidak dikenal';

        return (new MailMessage)
            ->subject("🔧 Tugas Servis Baru — {$itemName}")
            ->view('emails.notifications.maintenance_assigned', [
                'notifiable'     => $notifiable,
                'maintenance'    => $this->maintenance,
                'adminName'      => $this->adminName,
                'actionUrl'      => url('/staff/maintenance/' . $this->maintenance->id),
                'companyName'    => $companyName,
                'companyTagline' => $companyTagline,
                'companyEmail'   => $companyEmail,
                'companyAddress' => $companyAddress,
                'companyLogo'    => $companyLogo,
                'appUrl'         => config('app.url'),
            ]);
    }

    public function toDatabase(object $notifiable): array
    {
        $item   = $this->maintenance->item;
        $nama   = $item?->nama ?? 'Barang tidak dikenal';
        $jumlah = $this->maintenance->jumlah;

        return [
            'message' => "Admin {$this->adminName} mengirim \"{$nama}\" ({$jumlah} unit) untuk diservis. Segera tindaklanjuti.",
            'type'    => 'maintenance',
            'url'     => url('/staff/maintenance/' . $this->maintenance->id),
        ];
    }

    public function toWhatsApp($notifiable): string
    {
        $item   = $this->maintenance->item;
        $nama   = $item?->nama ?? 'Barang tidak dikenal';
        $jumlah = $this->maintenance->jumlah;
        $app    = config('app.name');

        return "🔧 *Tugas Servis Baru* — {$app}\n\n"
             . "Halo {$notifiable->name},\n"
             . "Admin *{$this->adminName}* mengirimkan tugas servis baru.\n\n"
             . "📦 Barang: *{$nama}*\n"
             . "🔢 Jumlah: {$jumlah} unit\n\n"
             . "Segera tindaklanjuti.\n— Tim {$app}";
    }
}
