<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::all()->keyBy('key');
        return view('admin.contents.settings.index', compact('settings'));
    }

    /**
     * Update borrowing & notification settings.
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'max_borrow_days'              => 'required|integer|min:1|max:365',
            'max_items_per_user'           => 'required|integer|min:1|max:20',
            'low_stock_threshold'          => 'required|integer|min:1|max:1000',
            'enable_overdue_reminder'         => 'sometimes|boolean',
            'enable_pending_reminder'          => 'sometimes|boolean',
            'enable_low_stock_alert'           => 'sometimes|boolean',
            'enable_whatsapp_notifications'    => 'sometimes|boolean',
        ]);

        Setting::set('max_borrow_days',             $validated['max_borrow_days']);
        Setting::set('max_items_per_user',           $validated['max_items_per_user']);
        Setting::set('low_stock_threshold',          $validated['low_stock_threshold']);
        Setting::set('enable_overdue_reminder',       $request->has('enable_overdue_reminder')         ? 1 : 0);
        Setting::set('enable_pending_reminder',       $request->has('enable_pending_reminder')         ? 1 : 0);
        Setting::set('enable_low_stock_alert',        $request->has('enable_low_stock_alert')          ? 1 : 0);
        Setting::set('enable_whatsapp_notifications', $request->has('enable_whatsapp_notifications')   ? 1 : 0);

        return back()->with('success', 'Pengaturan sistem berhasil disimpan.');
    }

    /**
     * Update company profile settings (name, address, phone, email, logo).
     */
    public function updateCompany(Request $request)
    {
        $validated = $request->validate([
            'company_name'     => 'required|string|max:150',
            'company_tagline'  => 'nullable|string|max:200',
            'company_address'  => 'nullable|string|max:300',
            'company_phone'    => 'nullable|string|max:50',
            'company_email'    => 'nullable|email|max:150',
            'company_logo'     => 'nullable|image|mimes:png,jpg,jpeg,svg|max:2048',
        ]);

        Setting::set('company_name',    $validated['company_name']);
        Setting::set('company_tagline', $validated['company_tagline'] ?? '');
        Setting::set('company_address', $validated['company_address'] ?? '');
        Setting::set('company_phone',   $validated['company_phone'] ?? '');
        Setting::set('company_email',   $validated['company_email'] ?? '');

        // Handle logo upload
        if ($request->hasFile('company_logo') && $request->file('company_logo')->isValid()) {
            // Delete old logo if exists
            $oldLogo = Setting::get('company_logo');
            if ($oldLogo && file_exists(public_path($oldLogo))) {
                @unlink(public_path($oldLogo));
            }

            $ext      = $request->file('company_logo')->getClientOriginalExtension();
            $filename = 'company-logo.' . $ext;
            $request->file('company_logo')->move(public_path('images'), $filename);
            Setting::set('company_logo', 'images/' . $filename);
        }

        return back()->with('success_company', 'Profil perusahaan berhasil disimpan.');
    }

    /**
     * Test WhatsApp connection using Fonnte API.
     */
    public function testWhatsapp(Request $request)
    {
        $request->validate([
            'test_number' => 'required|string',
        ]);

        $token = config('fonnte.token');
        if (empty($token)) {
            return back()->with('error', 'Token Fonnte belum dikonfigurasi di file .env.');
        }

        $number = preg_replace('/[\s\-\(\)]+/', '', $request->test_number);
        if (str_starts_with($number, '08')) {
            $number = '62' . substr($number, 1);
        }

        try {
            $response = \Illuminate\Support\Facades\Http::timeout(10)
                ->withHeaders(['Authorization' => $token])
                ->post(config('fonnte.url', 'https://api.fonnte.com/send'), [
                    'target'  => $number,
                    'message' => "Halo! Ini adalah pesan uji coba dari sistem Artilia.\n\nJika Anda menerima pesan ini, berarti koneksi WhatsApp Gateway (Fonnte) telah berjalan dengan baik. ✅",
                ]);

            if ($response->successful()) {
                $body = $response->json();
                if (isset($body['status']) && $body['status'] == true) {
                    return back()->with('success', 'Pesan uji coba berhasil dikirim ke ' . $number);
                } else {
                    return back()->with('error', 'Gagal mengirim pesan: ' . ($body['reason'] ?? 'Unknown error from Fonnte'));
                }
            }

            return back()->with('error', 'Gagal mengirim pesan (HTTP ' . $response->status() . ').');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
