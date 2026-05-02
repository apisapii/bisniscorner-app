<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class TenantController extends Controller
{
    public function index()
    {
        // Menampilkan semua user yang rolenya 'seller' (tenant UMKM)
        $tenants = User::where('role', 'seller')->orderBy('name', 'asc')->get();
        return view('admin.tenants.index', compact('tenants'));
    }

    public function store(Request $request)
    {
        // Validasi input form pendaftaran tenant
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        // Buat user baru dengan role 'seller'
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'seller',
        ]);

        return redirect()->back()->with('success', 'Tenant baru berhasil didaftarkan!');
    }

    public function destroy($id)
    {
        // Hapus tenant berdasarkan ID
        $tenant = User::where('id', $id)->where('role', 'seller')->firstOrFail();
        $tenant->delete();

        return redirect()->back()->with('success', 'Tenant berhasil dihapus!');
    }
}