<?php

namespace App\Notifications;

use App\Models\Borrowing;
use App\Models\BorrowingRequest;
use App\Models\Setting;
use Illuminate\Bus\Queueable;

use Illuminate\Notifications\Notification;

class ItemOverdueNotification extends Notification
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

        $itemName    = $this->borrowing->item->nama ?? 'Barang';
        $dueDateObj  = $this->borrowing->tanggal_kembali_rencana;
        $daysOverdue = $dueDateObj ? max(1, (int) ceil($dueDateObj->diffInDays(now(), true))) : 1;

        return (new MailMessage)
            ->subject("🚨 Barang Terlambat Dikembalikan — {$itemName}")
            ->view('emails.notifications.item_overdue', [
                'notifiable'     => $notifiable,
                'borrowing'      => $this->borrowing,
                'daysOverdue'    => $daysOverdue,
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
        $isRequest   = $this->borrowing instanceof BorrowingRequest;
        $itemName    = $this->borrowing->item->nama ?? 'Barang';
        $dueDateObj  = $this->borrowing->tanggal_kembali_rencana;
        $dueDate     = $dueDateObj?->format('d M Y') ?? '-';
        $daysOverdue = $dueDateObj ? max(1, (int) ceil($dueDateObj->diffInDays(now(), true))) : 1;

        return [
            'type'                 => 'overdue',
            'message'              => 'Barang "' . $itemName . '" belum dikembalikan. Sudah terlambat ' . $daysOverdue . ' hari dari tanggal ' . $dueDate . '.',
            'borrowing_request_id' => $isRequest ? $this->borrowing->id : null,
            'borrowing_id'         => $isRequest ? null : $this->borrowing->id,
            'item_name'            => $itemName,
            'due_date'             => $dueDateObj?->format('Y-m-d'),
            'days_overdue'         => $daysOverdue,
            'url'                  => route('user.dashboard'),
        ];
    }

    public function toWhatsApp($notifiable): string
    {
        $itemName    = $this->borrowing->item->nama ?? 'Barang';
        $dueDateObj  = $this->borrowing->tanggal_kembali_rencana;
        $dueDate     = $dueDateObj?->format('d M Y') ?? '-';
        $daysOverdue = $dueDateObj ? max(1, (int) ceil($dueDateObj->diffInDays(now(), true))) : 1;
        $app         = config('app.name');

        return "🚨 *Barang Terlambat Dikembalikan!* — {$app}\n\n"
             . "Halo {$notifiable->name},\n"
             . "Barang *{$itemName}* belum dikembalikan.\n\n"
             . "📅 Jatuh tempo: {$dueDate}\n"
             . "⏳ Keterlambatan: *{$daysOverdue} hari*\n\n"
             . "Harap segera kembalikan barang. Hubungi admin jika ada kendala.\n— Tim {$app}";
    }
}
