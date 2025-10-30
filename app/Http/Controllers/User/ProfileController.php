<?php

declare(strict_types=1);

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

final class ProfileController extends Controller
{
    /**
     * Show the profile edit form
     */
    public function edit(): View
    {
        return view('user.profile.edit');
    }

    /**
     * Update the user profile
     */
    public function update(Request $request): RedirectResponse
    {
        $user = auth()->user();

        // Validate the input
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'photo' => 'nullable|image|max:2048000', // 2000MB max
            'current_password' => 'nullable|required_with:password',
            'password' => 'nullable|min:8|confirmed',
        ], [
            'name.required' => 'Namn är obligatoriskt.',
            'email.required' => 'E-post är obligatoriskt.',
            'email.email' => 'E-postadressen måste vara giltig.',
            'email.unique' => 'Denna e-postadress används redan.',
            'photo.image' => 'Filen måste vara en bild.',
            'photo.max' => 'Bilden får max vara 2MB.',
            'current_password.required_with' => 'Nuvarande lösenord krävs för att ändra lösenord.',
            'password.min' => 'Lösenordet måste vara minst 8 tecken.',
            'password.confirmed' => 'Lösenorden matchar inte.',
        ]);

        // Prepare update data
        $updateData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
        ];

        // Handle photo upload
        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($user->photo) {
                Storage::disk('public')->delete($user->photo);
            }

            $photoPath = $request->file('photo')->store('user-photos', 'public');
            $updateData['photo'] = $photoPath;
        }

        // Handle password change
        if ($request->filled('password')) {
            // Verify current password
            if (!Hash::check($request->current_password, $user->password)) {
                return back()
                    ->withErrors(['current_password' => 'Det nuvarande lösenordet är felaktigt.'])
                    ->withInput();
            }

            $updateData['password'] = Hash::make($validated['password']);
        }

        // Update the user
        $user->update($updateData);

        return redirect()
            ->route('user.profile')
            ->with('success', '✅ Din profil har uppdaterats!');
    }
}
