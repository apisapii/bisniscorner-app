<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Umkm;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class TenantController extends Controller
{
    public function index()
    {
        $tenants = User::with('umkm')->where('role', 'admin_umkm')->orderBy('name', 'asc')->get();

        return view('admin.tenants.index', compact('tenants'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        $umkm = Umkm::create([
            'name' => $request->name,
            'description' => '',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'admin_umkm',
            'umkm_id' => $umkm->id,
        ]);

        return redirect()->back()->with('success', 'Akun penjual UMKM berhasil dibuat.');
    }

    public function destroy($id)
    {
        $tenant = User::where('id', $id)->where('role', 'admin_umkm')->firstOrFail();

        $umkmId = $tenant->umkm_id;
        $tenant->delete();

        if ($umkmId && ! User::where('umkm_id', $umkmId)->exists()) {
            Umkm::where('id', $umkmId)->delete();
        }

        return redirect()->back()->with('success', 'Tenant dan data UMKM terkait telah dihapus.');
    }
}
