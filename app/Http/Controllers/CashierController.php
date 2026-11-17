<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class CashierController extends Controller
{
    /**
     * Tampilkan halaman pengelolaan kasir (redirect ke tab kasir di settings)
     */
    public function index()
    {
        return redirect()->route('settings.index', ['tab' => 'cashiers']);
    }

    /**
     * Simpan akun kasir baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:100', 'unique:users,email'],
            'store_branch' => ['required', 'string', 'max:100'],
            'phone' => ['nullable', 'string', 'max:30'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'is_active' => ['nullable', 'boolean'],
        ], [
            'name.required' => 'Nama lengkap kasir wajib diisi.',
            'email.required' => 'Email kasir wajib diisi.',
            'email.unique' => 'Email ini sudah digunakan oleh akun lain.',
            'store_branch.required' => 'Nama toko/cabang wajib diisi.',
            'password.required' => 'Password akun kasir wajib diisi.',
            'password.min' => 'Password minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        $kasirRole = Role::firstOrCreate(['name' => 'Kasir']);

        $cashier = User::create([
            'role_id' => $kasirRole->id,
            'name' => $validated['name'],
            'store_branch' => $validated['store_branch'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'password' => Hash::make($validated['password']),
            'is_active' => $request->boolean('is_active', true),
        ]);

        ActivityLog::record(
            'CREATE_CASHIER',
            "Admin menambahkan akun kasir baru: {$cashier->name} untuk cabang {$cashier->store_branch}.",
            $cashier,
            [
                'email' => $cashier->email,
                'store_branch' => $cashier->store_branch,
                'is_active' => $cashier->is_active,
            ]
        );

        return redirect()->route('settings.index', ['tab' => 'cashiers'])
            ->with('success', "Akun kasir '{$cashier->name}' untuk cabang '{$cashier->store_branch}' berhasil ditambahkan.");
    }

    /**
     * Perbarui data akun kasir
     */
    public function update(Request $request, User $cashier)
    {
        if (optional($cashier->role)->name !== 'Kasir') {
            return back()->with('error', 'Hanya akun dengan role Kasir yang dapat dikelola di menu ini.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:100', 'unique:users,email,' . $cashier->id],
            'store_branch' => ['required', 'string', 'max:100'],
            'phone' => ['nullable', 'string', 'max:30'],
            'password' => ['nullable', 'string', 'min:6', 'confirmed'],
            'is_active' => ['nullable', 'boolean'],
        ], [
            'name.required' => 'Nama lengkap kasir wajib diisi.',
            'email.required' => 'Email kasir wajib diisi.',
            'email.unique' => 'Email ini sudah digunakan oleh akun lain.',
            'store_branch.required' => 'Nama toko/cabang wajib diisi.',
            'password.min' => 'Password baru minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi password baru tidak cocok.',
        ]);

        $updateData = [
            'name' => $validated['name'],
            'store_branch' => $validated['store_branch'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'is_active' => $request->boolean('is_active', true),
        ];

        if (!empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $cashier->update($updateData);

        ActivityLog::record(
            'UPDATE_CASHIER',
            "Admin memperbarui data akun kasir: {$cashier->name} ({$cashier->store_branch}).",
            $cashier,
            [
                'email' => $cashier->email,
                'store_branch' => $cashier->store_branch,
                'is_active' => $cashier->is_active,
                'password_changed' => !empty($validated['password']),
            ]
        );

        return redirect()->route('settings.index', ['tab' => 'cashiers'])
            ->with('success', "Data akun kasir '{$cashier->name}' berhasil diperbarui.");
    }

    /**
     * Toggle status aktif / non-aktif akun kasir
     */
    public function toggleStatus(User $cashier)
    {
        if (optional($cashier->role)->name !== 'Kasir') {
            return back()->with('error', 'Hanya akun Kasir yang dapat diubah statusnya di sini.');
        }

        $newStatus = !$cashier->is_active;
        $cashier->update(['is_active' => $newStatus]);

        $statusText = $newStatus ? 'diaktifkan' : 'dinonaktifkan';

        ActivityLog::record(
            'UPDATE_CASHIER',
            "Status akun kasir {$cashier->name} {$statusText}.",
            $cashier,
            ['is_active' => $newStatus]
        );

        return redirect()->route('settings.index', ['tab' => 'cashiers'])
            ->with('success', "Akun kasir '{$cashier->name}' berhasil {$statusText}.");
    }

    /**
     * Hapus akun kasir
     */
    public function destroy(User $cashier)
    {
        if (optional($cashier->role)->name !== 'Kasir') {
            return back()->with('error', 'Hanya akun Kasir yang dapat dihapus di menu ini.');
        }

        $userName = $cashier->name;

        // Cek jika kasir memiliki riwayat transaksi
        if ($cashier->transactions()->exists()) {
            $cashier->update(['is_active' => false]);

            ActivityLog::record(
                'UPDATE_CASHIER',
                "Akun kasir {$userName} dinonaktifkan (memiliki riwayat transaksi dan tidak dihapus permanen demi audit trail).",
                $cashier,
                ['action' => 'deactivated_instead_of_delete']
            );

            return redirect()->route('settings.index', ['tab' => 'cashiers'])
                ->with('success', "Akun kasir '{$userName}' memiliki riwayat transaksi, sehingga statusnya dinonaktifkan demi integritas data laporan.");
        }

        $cashier->delete();

        ActivityLog::record(
            'DELETE_CASHIER',
            "Admin menghapus akun kasir: {$userName}.",
            null,
            ['name' => $userName]
        );

        return redirect()->route('settings.index', ['tab' => 'cashiers'])
            ->with('success', "Akun kasir '{$userName}' berhasil dihapus secara permanen.");
    }
}
