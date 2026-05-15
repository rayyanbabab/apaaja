<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserProfileController extends Controller
{
    public function index()
    {
        return view('user.profile.index', ['user' => auth()->user()]);
    }

    public function updateEmail(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', Rule::unique('users')->ignore(auth()->id())],
            'current_password' => 'required',
        ]);

        $user = auth()->user();

        if (! Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password saat ini tidak benar.']);
        }

        $user->email = $request->email;
        $user->save();

        return back()->with('success', 'Email berhasil diperbarui.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password'         => 'required|min:8|confirmed',
        ]);

        $user = auth()->user();

        if (! Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password saat ini tidak benar.']);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return back()->with('success', 'Password berhasil diperbarui.');
    }

    public function updateWhatsapp(Request $request)
    {
        $request->validate([
            'whatsapp_number' => 'nullable|string|max:20|regex:/^[0-9+\-\s\(\)]+$/',
        ]);

        $user = auth()->user();
        $user->whatsapp_number = $request->whatsapp_number ?: null;
        $user->save();

        return back()->with('success', 'Nomor WhatsApp berhasil diperbarui.');
    }

    public function updatePhoto(Request $request)
    {
        $request->validate([
            'profil' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $user = auth()->user();
        if ($user->profil && file_exists(public_path($user->profil))) {
            unlink(public_path($user->profil));
        }
        $file = $request->file('profil');
        $filename = time().'_'.uniqid().'.jpg';
        $profileDir = public_path('images/profiles');
        if (! file_exists($profileDir)) {
            mkdir($profileDir, 0755, true);
        }
        $this->resizeImage($file->getPathname(), $profileDir.'/'.$filename, 200, 200);

        $user->profil = 'images/profiles/'.$filename;
        $user->save();

        return back()->with('success', 'Foto profil berhasil diperbarui.');
    }

    private function resizeImage($sourcePath, $destinationPath, $width, $height)
    {
        $imageInfo = getimagesize($sourcePath);
        $sourceWidth = $imageInfo[0];
        $sourceHeight = $imageInfo[1];
        $imageType = $imageInfo[2];
        switch ($imageType) {
            case IMAGETYPE_JPEG:
                $sourceImage = imagecreatefromjpeg($sourcePath);
                break;
            case IMAGETYPE_PNG:
                $sourceImage = imagecreatefrompng($sourcePath);
                break;
            case IMAGETYPE_GIF:
                $sourceImage = imagecreatefromgif($sourcePath);
                break;
            default:
                return false;
        }

        $destinationImage = imagecreatetruecolor($width, $height);
        if ($imageType == IMAGETYPE_PNG) {
            imagealphablending($destinationImage, false);
            imagesavealpha($destinationImage, true);
            $transparent = imagecolorallocatealpha($destinationImage, 255, 255, 255, 127);
            imagefilledrectangle($destinationImage, 0, 0, $width, $height, $transparent);
        }

        imagecopyresampled(
            $destinationImage, $sourceImage,
            0, 0, 0, 0,
            $width, $height, $sourceWidth, $sourceHeight
        );

        imagejpeg($destinationImage, $destinationPath, 80);

        imagedestroy($sourceImage);
        imagedestroy($destinationImage);

        return true;
    }
}
