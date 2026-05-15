<?php

namespace App\Notifications;

use App\Models\ProcurementRequest;
use App\Models\Setting;
use Illuminate\Bus\Queueable;

use Illuminate\Notifications\Notification;

class ProcurementReviewedNotification extends Notification
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
        $isApproved = $this->procurement->status === 'approved';
        $icon       = $isApproved ? '✅' : '❌';
        $action     = $isApproved ? 'disetujui' : 'ditolak';

        $mail = (new MailMessage)
            ->subject("{$icon} Permintaan Pengadaan {$action} — {$this->procurement->nama_barang}")
            ->greeting("Halo, {$notifiable->name}!")
            ->line("Permintaan pengadaan Anda untuk **\"{$this->procurement->nama_barang}\"** telah **{$action}**.");

        if ($isApproved && $this->procurement->item) {
            $mail->line("Barang telah ditambahkan ke inventaris: **{$this->procurement->item->nama}**.");
        }

        if ($this->procurement->admin_notes) {
            $mail->line("**Catatan Admin:** {$this->procurement->admin_notes}");
        }

        return $mail
            ->action('Lihat Detail', url("/user/procurement/{$this->procurement->id}"))
            ->line('Terima kasih telah menggunakan sistem Artilia.');
    }

    public function toArray($notifiable): array
    {
        $isApproved = $this->procurement->status === 'approved';
        $action     = $isApproved ? 'disetujui' : 'ditolak';

        return [
            'message'        => "Permintaan pengadaan Anda untuk \"{$this->procurement->nama_barang}\" telah {$action}.",
            'procurement_id' => $this->procurement->id,
            'nama_barang'    => $this->procurement->nama_barang,
            'status'         => $this->procurement->status,
            'admin_notes'    => $this->procurement->admin_notes,
            'url'            => url("/user/procurement/{$this->procurement->id}"),
        ];
    }

    public function toWhatsApp($notifiable): string
    {
        $isApproved  = $this->procurement->status === 'approved';
        $icon        = $isApproved ? '✅' : '❌';
        $action      = $isApproved ? 'Disetujui' : 'Ditolak';
        $nama        = $this->procurement->nama_barang;
        $catatan     = $this->procurement->admin_notes ?: 'Tidak ada catatan.';
        $app         = config('app.name');

        return "{$icon} *Permintaan Pengadaan {$action}* — {$app}\n\n"
             . "Halo {$notifiable->name},\n"
             . "Permintaan pengadaan barang *{$nama}* Anda telah *{$action}*.\n\n"
             . "📝 Catatan admin: {$catatan}\n\n"
             . "— Tim {$app}";
    }
}
