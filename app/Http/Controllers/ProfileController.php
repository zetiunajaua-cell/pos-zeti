<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\ActivityLog;
use App\Models\Expense;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\User;
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
        $user = $request->user()->load('role');
        $roleName = optional($user->role)->name ?? 'User';

        $stats = [];
        $recentTransactions = collect();
        $recentActivities = collect();

        if ($roleName === 'Kasir') {
            $stats = [
                'total_transactions' => Transaction::where('user_id', $user->id)->where('status', 'completed')->count(),
                'total_sales' => (float) Transaction::where('user_id', $user->id)->where('status', 'completed')->sum('total_amount'),
                'today_transactions' => Transaction::where('user_id', $user->id)->where('status', 'completed')->whereDate('created_at', today())->count(),
                'today_sales' => (float) Transaction::where('user_id', $user->id)->where('status', 'completed')->whereDate('created_at', today())->sum('total_amount'),
            ];

            $recentTransactions = Transaction::with(['customer', 'paymentMethod'])
                ->where('user_id', $user->id)
                ->latest()
                ->take(5)
                ->get();
        } else {
            // Admin atau peran lainnya
            $stats = [
                'total_expenses' => Expense::where('user_id', $user->id)->count(),
                'total_activities' => ActivityLog::where('user_id', $user->id)->count(),
                'total_products' => Product::count(),
                'total_users' => User::count(),
            ];

            $recentActivities = ActivityLog::where('user_id', $user->id)
                ->latest()
                ->take(5)
                ->get();
        }

        return view('profile.edit', [
            'user' => $user,
            'roleName' => $roleName,
            'stats' => $stats,
            'recentTransactions' => $recentTransactions,
            'recentActivities' => $recentActivities,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $validated = $request->validated();

        // Handle delete avatar / reset ke foto inisial default
        if ($request->boolean('delete_avatar')) {
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }
            $user->avatar = null;
        }

        // Handle upload avatar baru
        if ($request->hasFile('avatar')) {
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }
            $path = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $path;
        }

        $user->name = $validated['name'];
        $user->email = $validated['email'];

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        ActivityLog::record(
            'UPDATE_PROFILE',
            "Pengguna {$user->name} memperbarui informasi profil akun" . ($request->hasFile('avatar') ? ' dan mengunggah foto profil baru.' : '.'),
            $user,
            ['name' => $user->name, 'email' => $user->email]
        );

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

        ActivityLog::record(
            'DELETE_ACCOUNT',
            "Pengguna {$user->name} ({$user->email}) menghapus akun miliknya.",
            null
        );

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
