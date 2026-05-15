<?php

namespace App\Notifications;

use App\Models\Maintenance;
use App\Models\Setting;
use Illuminate\Bus\Queueable;

use Illuminate\Notifications\Notification;

/**
 * Dikirim ke semua Admin ketika Operator menyelesaikan atau meng-scrap maintenance.
 */
class MaintenanceCompletedNotification extends Notification
{
    use Queueable;

    public function __construct(
        protected Maintenance $maintenance,
        protected string $operatorName,
        protected string $action   // 'completed' | 'scrapped'
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
        $subject  = $this->action === 'completed'
            ? "🏁 Maintenance Selesai — {$itemName}"
            : "🗑️ Barang Di-scrap — {$itemName}";

        return (new MailMessage)
            ->subject($subject)
            ->view('emails.notifications.maintenance_completed', [
                'notifiable'     => $notifiable,
                'maintenance'    => $this->maintenance,
                'operatorName'   => $this->operatorName,
                'action'         => $this->action,
                'actionUrl'      => url('/admin/maintenance/' . $this->maintenance->id),
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
        $item    = $this->maintenance->item;
        $nama    = $item?->nama ?? 'Barang tidak dikenal';
        $jumlah  = $this->maintenance->jumlah;

        $message = $this->action === 'completed'
            ? "Operator {$this->operatorName} menyelesaikan servis \"{$nama}\" ({$jumlah} unit). Stok telah dikembalikan."
            : "Operator {$this->operatorName} meng-scrap \"{$nama}\" ({$jumlah} unit). Barang tidak dapat diperbaiki.";

        return [
            'message' => $message,
            'type'    => 'maintenance',
            'url'     => url('/admin/maintenance/' . $this->maintenance->id),
        ];
    }

    public function toWhatsApp($notifiable): string
    {
        $item   = $this->maintenance->item;
        $nama   = $item?->nama ?? 'Barang tidak dikenal';
        $jumlah = $this->maintenance->jumlah;
        $app    = config('app.name');

        if ($this->action === 'completed') {
            return "🏁 *Servis Selesai* — {$app}\n\n"
                 . "Halo Admin,\n"
                 . "Operator *{$this->operatorName}* telah menyelesaikan servis:\n\n"
                 . "📦 Barang: *{$nama}*\n"
                 . "🔢 Jumlah: {$jumlah} unit\n\n"
                 . "Stok telah dikembalikan ke inventaris.\n— Tim {$app}";
        }

        return "🗑️ *Barang Di-scrap* — {$app}\n\n"
             . "Halo Admin,\n"
             . "Operator *{$this->operatorName}* meng-scrap:\n\n"
             . "📦 Barang: *{$nama}*\n"
             . "🔢 Jumlah: {$jumlah} unit\n\n"
             . "Barang tidak dapat diperbaiki dan telah dihapus dari inventaris.\n— Tim {$app}";
    }
}
