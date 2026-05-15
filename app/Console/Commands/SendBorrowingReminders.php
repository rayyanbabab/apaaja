<?php

namespace App\Console\Commands;

use App\Models\Borrowing;
use App\Models\BorrowingRequest;
use App\Models\Item;
use App\Models\Setting;
use App\Models\User;
use App\Notifications\BorrowingExpiredNotification;
use App\Notifications\ItemOverdueNotification;
use App\Notifications\LowStockNotification;
use App\Notifications\PendingApprovalReminderNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SendBorrowingReminders extends Command
{
    protected $signature = 'artilia:send-reminders';
    protected $description = 'Kirim notifikasi pengingat untuk peminjaman terlambat dan pengajuan pending';

    public function handle(): void
    {
        $this->autoExpirePendingRequests();
        $this->sendOverdueReminders();
        $this->sendPendingApprovalReminders();
        $this->sendLowStockAlerts();
        $this->info('✅ Reminder notifications sent.');
    }

    /**
     * Auto-batalkan permintaan pending yang tanggal pinjamnya sudah lewat.
     * Kirim notifikasi ke user yang bersangkutan.
     */
    private function autoExpirePendingRequests(): void
    {
        $expiredRequests = BorrowingRequest::with(['user', 'item'])
            ->where('status', 'pending')
            ->whereDate('tanggal_pinjam', '<', now()->toDateString())
            ->get();

        if ($expiredRequests->isEmpty()) {
            $this->info('No expired pending requests found.');
            return;
        }

        foreach ($expiredRequests as $request) {
            // Ubah status menjadi cancelled
            $request->update(['status' => 'cancelled']);

            // Kirim notifikasi ke user
            $request->user->notify(new BorrowingExpiredNotification($request));

            $this->line("🗑️  Expired request auto-cancelled: #{$request->id} ({$request->user->name} - {$request->item->nama}, tanggal pinjam: {$request->tanggal_pinjam->format('d M Y')})");
        }

        $this->info("✅ {$expiredRequests->count()} expired pending request(s) cancelled.");
    }

    /**
     * Kirim notifikasi ke user yang terlambat mengembalikan barang.
     */
    private function sendOverdueReminders(): void
    {
        if (! Setting::get('enable_overdue_reminder', true)) {
            $this->info('Overdue reminders disabled.');
            return;
        }

        $overdueRequests = BorrowingRequest::with(['user', 'item'])
            ->where('status', 'approved')
            ->whereDate('tanggal_kembali_rencana', '<', now()->toDateString())
            ->get();

        foreach ($overdueRequests as $request) {
            // Cek apakah sudah kirim hari ini
            if ($request->overdue_notified_at &&
                $request->overdue_notified_at->isToday()) {
                continue;
            }

            $request->user->notify(new ItemOverdueNotification($request));

            $request->update(['overdue_notified_at' => now()]);

            $this->line("🔔 Overdue reminder sent to: {$request->user->name} for item: {$request->item->nama}");
        }

        // Also handle direct borrowings created by admin/staff in borrowings table.
        $overdueBorrowings = Borrowing::with(['user', 'item'])
            ->whereIn('status', ['dipinjam', 'terlambat'])
            ->whereDate('tanggal_kembali_rencana', '<', now()->toDateString())
            ->get();

        foreach ($overdueBorrowings as $borrowing) {
            if (! $borrowing->user) {
                continue;
            }

            // Avoid duplicate notification for same borrowing in the same day.
            $alreadySentToday = DB::table('notifications')
                ->where('type', ItemOverdueNotification::class)
                ->where('notifiable_type', User::class)
                ->where('notifiable_id', $borrowing->user_id)
                ->where('data->borrowing_id', $borrowing->id)
                ->whereDate('created_at', now()->toDateString())
                ->exists();

            if ($alreadySentToday) {
                continue;
            }

            $borrowing->user->notify(new ItemOverdueNotification($borrowing));
            $this->line("🔔 Direct borrowing overdue reminder sent to: {$borrowing->user->name} for item: {$borrowing->item->nama}");
        }
    }

    /**
     * Kirim notifikasi ke admin untuk pengajuan yang pending > 24 jam.
     */
    private function sendPendingApprovalReminders(): void
    {
        if (! Setting::get('enable_pending_reminder', true)) {
            $this->info('Pending reminders disabled.');
            return;
        }

        $pendingRequests = BorrowingRequest::where('status', 'pending')
            ->where('created_at', '<', now()->subHours(24))
            ->get();

        if ($pendingRequests->isEmpty()) {
            return;
        }

        $pendingCount       = $pendingRequests->count();
        $oldestPendingHours = (int) $pendingRequests->min('created_at')->diffInHours(now());

        // Pre-load admin IDs yang sudah dapat notif dalam 12 jam terakhir (single query)
        $alreadyNotifiedAdminIds = DB::table('notifications')
            ->where('type', PendingApprovalReminderNotification::class)
            ->where('created_at', '>=', now()->subHours(12))
            ->pluck('notifiable_id')
            ->all();

        $staffApprovers = User::whereIn('role', ['admin', 'operator'])->get();

        foreach ($staffApprovers as $staff) {
            if (in_array($staff->id, $alreadyNotifiedAdminIds)) {
                continue;
            }

            $staff->notify(new PendingApprovalReminderNotification($pendingCount, $oldestPendingHours));
            $this->line("🔔 Pending reminder sent to staff: {$staff->name}");
        }
    }

    /**
     * Daily sweep: cek semua item yang stoknya di bawah threshold.
     * Sebagai safety net selain trigger otomatis di Item::reduceStok().
     */
    private function sendLowStockAlerts(): void
    {
        if (! Setting::get('enable_low_stock_alert', true)) {
            $this->info('Low stock alerts disabled.');
            return;
        }

        $threshold = (int) Setting::get('low_stock_threshold', 5);

        $lowStockItems = Item::where('stok_total', '<=', $threshold)
            ->where('stok_total', '>', 0)
            ->get();

        if ($lowStockItems->isEmpty()) {
            return;
        }

        // Pre-load item IDs yang sudah dapat notif dalam 24 jam terakhir
        $alreadyNotifiedItemIds = DB::table('notifications')
            ->where('type', LowStockNotification::class)
            ->where('created_at', '>=', now()->subHours(24))
            ->pluck('data')
            ->map(fn($d) => json_decode($d, true)['item_id'] ?? null)
            ->filter()
            ->unique()
            ->all();

        $staffReceivers = User::whereIn('role', ['admin', 'operator'])->get();

        foreach ($lowStockItems as $item) {
            if (in_array($item->id, $alreadyNotifiedItemIds)) {
                continue;
            }

            foreach ($staffReceivers as $staff) {
                $staff->notify(new LowStockNotification($item, $threshold));
            }

            $this->line("⚠️ Low stock alert sent for: {$item->nama} (stok: {$item->stok_total})");
        }
    }
}
