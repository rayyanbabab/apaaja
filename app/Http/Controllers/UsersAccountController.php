<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UsersAccountController extends Controller
{
    public function list(Request $request)
    {
        $query = User::query();

        // Search functionality
        if ($request->has('search') && ! empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter by role
        if ($request->has('role') && ! empty($request->role)) {
            $query->where('role', $request->role);
        }

        // Filter by status
        if ($request->has('status') && ! empty($request->status)) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        $users = $query->orderBy('name', 'asc')->paginate(10);

        return view('admin.contents.user.ListUsers', compact('users'));
    }

    public function create()
    {
        return view('admin.contents.user.CreateUsers');
    }

    public function show($id)
    {
        $user = User::findOrFail($id);

        return view('admin.contents.user.ShowUser', compact('user'));
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string|min:8|confirmed',
                'role' => 'required|in:admin,user',
                'bio' => 'nullable|string|max:500',
                'is_active' => 'required|in:0,1',
                'profil' => 'nullable|image|mimes:jpeg,png,jpg|max:5120|dimensions:min_width=100,min_height=100',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        }

        $userData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'bio' => $validated['bio'] ?? null,
            'is_active' => (bool) $validated['is_active'],
        ];

        // Handle profile photo upload
        if ($request->hasFile('profil')) {
            $profil = $request->file('profil');
            $fileName = time().'_'.uniqid().'.jpg';

            // Create directory if it doesn't exist
            $profilePath = public_path('images/profiles');
            if (! file_exists($profilePath)) {
                mkdir($profilePath, 0755, true);
            }

            // Resize and compress image
            $image = imagecreatefromstring(file_get_contents($profil->getPathname()));
            $resized = imagecreatetruecolor(200, 200);

            // Get original dimensions
            $originalWidth = imagesx($image);
            $originalHeight = imagesy($image);

            // Calculate crop dimensions for square aspect ratio
            $cropSize = min($originalWidth, $originalHeight);
            $cropX = ($originalWidth - $cropSize) / 2;
            $cropY = ($originalHeight - $cropSize) / 2;

            // Resize with crop to square
            imagecopyresampled($resized, $image, 0, 0, $cropX, $cropY, 200, 200, $cropSize, $cropSize);

            // Save with compression
            imagejpeg($resized, $profilePath.'/'.$fileName, 85);

            // Clean up memory
            imagedestroy($image);
            imagedestroy($resized);

            $userData['profil'] = 'images/profiles/'.$fileName;
        }

        try {
            $user = User::create($userData);

            return redirect()->route('admin.content.listusers')->with('success', 'User created successfully');
        } catch (\Exception $e) {
            Log::error('User creation failed: '.$e->getMessage());

            return redirect()->back()->withErrors(['error' => 'Failed to create user: '.$e->getMessage()])->withInput();
        }
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);

        return view('admin.contents.user.EditUsers', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$id,
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:admin,user',
            'bio' => 'nullable|string|max:500',
            'is_active' => 'required|in:0,1',
            'profil' => 'nullable|image|mimes:jpeg,png,jpg|max:5120|dimensions:min_width=100,min_height=100',
        ]);

        $user = User::findOrFail($id);
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->role = $validated['role'];
        $user->bio = $validated['bio'] ?? null;
        $user->is_active = (bool) $validated['is_active'];

        if (! empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        // Handle profile photo upload
        if ($request->hasFile('profil')) {
            // Delete old profile photo if exists
            if ($user->profil && file_exists(public_path($user->profil))) {
                unlink(public_path($user->profil));
            }

            // Upload new profile photo
            $profil = $request->file('profil');
            $fileName = time().'_'.uniqid().'.jpg';

            // Create directory if it doesn't exist
            $profilePath = public_path('images/profiles');
            if (! file_exists($profilePath)) {
                mkdir($profilePath, 0755, true);
            }

            // Resize and compress image
            $image = imagecreatefromstring(file_get_contents($profil->getPathname()));
            $resized = imagecreatetruecolor(200, 200);

            // Get original dimensions
            $originalWidth = imagesx($image);
            $originalHeight = imagesy($image);

            // Calculate crop dimensions for square aspect ratio
            $cropSize = min($originalWidth, $originalHeight);
            $cropX = ($originalWidth - $cropSize) / 2;
            $cropY = ($originalHeight - $cropSize) / 2;

            // Resize with crop to square
            imagecopyresampled($resized, $image, 0, 0, $cropX, $cropY, 200, 200, $cropSize, $cropSize);

            // Save with compression
            imagejpeg($resized, $profilePath.'/'.$fileName, 85);

            // Clean up memory
            imagedestroy($image);
            imagedestroy($resized);

            $user->profil = 'images/profiles/'.$fileName;
        }

        $user->save();

        return redirect()->route('admin.content.listusers')->with('success', 'User updated successfully');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // Delete profile photo if exists
        if ($user->profil && file_exists(public_path($user->profil))) {
            unlink(public_path($user->profil));
        }

        $user->delete();

        return redirect()->route('admin.content.listusers')->with('success', 'User deleted successfully');
    }

    public function bulkDelete(Request $request)
    {
        $userIds = $request->input('user_ids', []);

        if (empty($userIds)) {
            return redirect()->back()->with('error', 'No users selected for deletion.');
        }

        $users = User::whereIn('id', $userIds)->get();

        foreach ($users as $user) {
            // Delete profile photo if exists
            if ($user->profil && file_exists(public_path($user->profil))) {
                unlink(public_path($user->profil));
            }
            $user->delete();
        }

        return redirect()->route('admin.content.listusers')->with('success', count($userIds).' users deleted successfully');
    }

    public function toggleStatus($id)
    {
        $user = User::findOrFail($id);
        $user->is_active = !$user->is_active;
        $user->save();

        $status = $user->is_active ? 'activated' : 'deactivated';

        return redirect()->back()->with('success', "User {$status} successfully");
    }
}
