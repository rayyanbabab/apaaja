<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function index()
    {
        $user = auth()->user(); // ambil data user yang sedang login
        return view('admin.pages.profile', compact('user'));
    }

    public function edit()
    {
        $user = auth()->user();
        return view('admin.pages.profile', compact('user'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'bio' => 'nullable|string',
            'password' => 'nullable|min:6|confirmed',
        ]);

        $user = auth()->user();
        $user->name = $data['name'];
        $user->bio = $data['bio'] ?? $user->bio;

        if (!empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }

        $user->save();

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    public function updateEmail(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', Rule::unique('users')->ignore(auth()->id())],
            'current_password' => 'required',
        ]);

        $user = auth()->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password saat ini tidak benar.']);
        }

        $user->email = $request->email;
        $user->save();

        return back()->with('success', 'Email berhasil diperbarui.');
    }

    public function updatePhoto(Request $request)
    {
        $request->validate([
            'profil' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = auth()->user();
        if ($user->profil && file_exists(public_path($user->profil))) {
            unlink(public_path($user->profil));
        }
        $file = $request->file('profil');
        $filename = time().'_'.uniqid().'.jpg';

        $profileDir = public_path('images/profiles');
        if (!file_exists($profileDir)) {
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

    public function destroy()
    {
        $user = auth()->user();
        auth()->logout();
        $user->delete();

        return redirect('/')->with('success', 'Your account has been deleted.');
    }
}
