<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Daftar Customer</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen flex flex-col bg-slate-50 antialiased text-gray-900">
    <div class="flex-1 flex items-center justify-center p-4 w-full min-h-0">
        <div class="w-full max-w-md rounded-3xl overflow-hidden shadow-2xl border border-white bg-white">
            <div class="px-6 py-6 bg-gradient-to-r from-blue-600 to-indigo-600 text-white">
                <p class="text-xs uppercase tracking-widest font-semibold opacity-90">Portal Customer</p>
                <h1 class="text-2xl font-black mt-1">Buat akun pembeli</h1>
                <p class="text-sm mt-1 text-blue-100">Daftar sekarang untuk checkout lebih cepat dan lihat riwayat pesanan.</p>
            </div>

            <div class="p-6">
                <form method="POST" action="{{ route('register') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label for="name" class="block text-xs font-bold uppercase tracking-wide text-gray-500">Nama</label>
                        <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name"
                               class="mt-1 w-full rounded-xl border border-gray-200 bg-white px-3 py-2.5 text-sm focus:ring-2 focus:ring-blue-500">
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>
                    <div>
                        <label for="email" class="block text-xs font-bold uppercase tracking-wide text-gray-500">Email</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username"
                               class="mt-1 w-full rounded-xl border border-gray-200 bg-white px-3 py-2.5 text-sm focus:ring-2 focus:ring-blue-500">
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>
                    <div>
                        <label for="password" class="block text-xs font-bold uppercase tracking-wide text-gray-500">Password</label>
                        <input id="password" type="password" name="password" required autocomplete="new-password"
                               class="mt-1 w-full rounded-xl border border-gray-200 bg-white px-3 py-2.5 text-sm focus:ring-2 focus:ring-blue-500">
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>
                    <div>
                        <label for="password_confirmation" class="block text-xs font-bold uppercase tracking-wide text-gray-500">Konfirmasi password</label>
                        <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                               class="mt-1 w-full rounded-xl border border-gray-200 bg-white px-3 py-2.5 text-sm focus:ring-2 focus:ring-blue-500">
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                    </div>

                    <button type="submit" class="w-full rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 transition">
                        Daftar
                    </button>
                </form>

                <div class="mt-4 text-center text-sm text-gray-500 space-y-1">
                    <p>Sudah punya akun?
                        <a href="{{ route('login') }}" class="font-bold text-blue-600 hover:text-blue-700">Masuk customer</a>
                    </p>
                    <p>Akun admin dibuat oleh super admin.
                        <a href="{{ route('login.admin') }}" class="font-bold text-indigo-600 hover:text-indigo-700">Masuk portal admin</a>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <x-site-footer />
</body>
</html>
