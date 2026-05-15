<?php

namespace App\Notifications;

use App\Models\ProcurementRequest;
use App\Models\Setting;
use Illuminate\Bus\Queueable;

use Illuminate\Notifications\Notification;

class ProcurementSubmittedNotification extends Notification
{
    use Queueable;

    protected ProcurementRequest $procurement;

    public function __construct(ProcurementRequest $procurement)
    {
        $this->procurement = $procurement;
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
        return (new MailMessage)
            ->subject("📋 Permintaan Pengadaan Baru — {$this->procurement->nama_barang}")
            ->greeting("Halo, {$notifiable->name}!")
            ->line("User **{$this->procurement->user->name}** mengajukan permintaan pengadaan barang baru.")
            ->line("**Nama Barang:** {$this->procurement->nama_barang}")
            ->line("**Jumlah:** {$this->procurement->jumlah} unit")
            ->line("**Alasan:** " . ($this->procurement->alasan ?: '-'))
            ->action('Tinjau Permintaan', url("/admin/procurement-requests/{$this->procurement->id}"))
            ->line('Segera tinjau dan proses permintaan ini.');
    }

    public function toArray($notifiable): array
    {
        return [
            'message'            => "Permintaan pengadaan baru: \"{$this->procurement->nama_barang}\" x{$this->procurement->jumlah} dari {$this->procurement->user->name}.",
            'procurement_id'     => $this->procurement->id,
            'nama_barang'        => $this->procurement->nama_barang,
            'jumlah'             => $this->procurement->jumlah,
            'user_name'          => $this->procurement->user->name,
            'status'             => 'pending',
            'url'                => url("/admin/procurement-requests/{$this->procurement->id}"),
        ];
    }
    public function toWhatsApp($notifiable): string
    {
        $nama   = $this->procurement->nama_barang;
        $jumlah = $this->procurement->jumlah;
        $user   = $this->procurement->user->name ?? 'User';
        $app    = config('app.name');

        return "📋 *Permintaan Pengadaan Baru* — {$app}\n\n"
             . "Halo Admin,\n"
             . "User *{$user}* mengajukan permintaan pengadaan baru.\n\n"
             . "📦 Barang: *{$nama}*\n"
             . "🔢 Jumlah: {$jumlah} unit\n\n"
             . "Segera tinjau dan proses permintaan ini.\n— Tim {$app}";
    }
}
