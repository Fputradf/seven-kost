<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Wishlist;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $user = $request->user();

        if ($user && $user->hasRole('penghuni')) {
            $activeBooking = $user->bookings()
                ->with(['kost', 'room'])
                ->whereIn('status', ['active', 'approved'])
                ->latest()
                ->first();

            $totalBookings = $user->bookings()->count();
            $totalWishlists = Wishlist::where('user_id', $user->id)->count();

            return view('tenant.profile', [
                'user' => $user,
                'activeBooking' => $activeBooking,
                'totalBookings' => $totalBookings,
                'totalWishlists' => $totalWishlists,
            ]);
        }

        if ($user && $user->hasRole('pemilik')) {
            return view('owner.profile', [
                'user' => $user,
            ]);
        }

        return view('profile.edit', [
            'user' => $user,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        if ($request->hasFile('avatar')) {
            if ($request->user()->avatar && ! str_starts_with($request->user()->avatar, 'http')) {
                Storage::disk('public')->delete($request->user()->avatar);
            }
            $validated['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $request->user()->fill($validated);

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
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
