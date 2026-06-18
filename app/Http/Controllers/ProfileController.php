<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function index()
    {
        return view(
            'profile.index',
            [
                'user' => auth()->user()
            ]
        );
    }

    /**
     * Update the user's profile information.
     */
    public function updateInformasi(
        Request $request
    )
    {
        $request->validate([

            'email' =>
                'nullable|email',

            'telepon' =>
                'nullable|string|max:30',

            'alamat' =>
                'nullable|string|max:1000',
        ]);

        $user = auth()->user();

        $user->update([

            'email' =>
                $request->email,

            'telepon' =>
                $request->telepon,

            'alamat' =>
                $request->alamat,
        ]);

        return back()->with(
            'success',
            'Profil berhasil diperbarui'
        );
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
