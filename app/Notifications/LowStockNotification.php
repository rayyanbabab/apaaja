<?php

namespace App\Notifications;

use App\Models\Item;
use App\Models\Setting;
use Illuminate\Bus\Queueable;

use Illuminate\Notifications\Notification;

class LowStockNotification extends Notification
{
    use Queueable;

    protected Item $item;
    protected int $threshold;

    public function __construct(Item $item, int $threshold)
    {
        $this->item      = $item;
        $this->threshold = $threshold;
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
            ->subject("⚠️ Stok Hampir Habis — {$this->item->nama} ({$this->item->stok_total} unit tersisa)")
            ->view('emails.notifications.low_stock', [
                'notifiable'     => $notifiable,
                'item'           => $this->item,
                'threshold'      => $this->threshold,
                'actionUrl'      => route('admin.inventory.show', $this->item->id),
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
            'type'      => 'low_stock',
            'message'   => 'Stok item "' . $this->item->nama . '" hampir habis! Stok saat ini: '
                           . $this->item->stok_total . ' unit (batas peringatan: ' . $this->threshold . ' unit).',
            'item_id'   => $this->item->id,
            'item_name' => $this->item->nama,
            'stok'      => $this->item->stok_total,
            'threshold' => $this->threshold,
            'url'       => route('admin.inventory.show', $this->item->id),
        ];
    }

    public function toWhatsApp($notifiable): string
    {
        $nama      = $this->item->nama;
        $stok      = $this->item->stok_total;
        $threshold = $this->threshold;
        $app       = config('app.name');

        return "⚠️ *Stok Hampir Habis!* — {$app}\n\n"
             . "Halo Admin,\n"
             . "Stok barang *{$nama}* hampir habis.\n\n"
             . "📦 Stok saat ini: *{$stok} unit*\n"
             . "🚨 Batas peringatan: {$threshold} unit\n\n"
             . "Segera lakukan restok untuk menghindari kekurangan.\n— Tim {$app}";
    }
}
