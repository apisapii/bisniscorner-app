<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - {{ $portal === 'admin' ? 'Admin & Super Admin' : 'Customer' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen flex flex-col {{ $portal === 'admin' ? 'bg-slate-950' : 'bg-slate-50' }} antialiased text-gray-900">
    <div class="flex-1 flex items-center justify-center p-4 w-full min-h-0">
        <div class="w-full max-w-md rounded-3xl overflow-hidden shadow-2xl border {{ $portal === 'admin' ? 'border-slate-800 bg-slate-900 text-slate-100' : 'border-white bg-white text-gray-900' }}">
            <div class="px-6 py-6 {{ $portal === 'admin' ? 'bg-gradient-to-r from-indigo-700 to-violet-700' : 'bg-gradient-to-r from-blue-600 to-indigo-600' }} text-white">
                <p class="text-xs uppercase tracking-widest font-semibold opacity-90">{{ $portal === 'admin' ? 'Portal Admin' : 'Portal Customer' }}</p>
                <h1 class="text-2xl font-black mt-1">{{ $portal === 'admin' ? 'Masuk Admin / Super Admin' : 'Masuk untuk Belanja' }}</h1>
                <p class="text-sm mt-1 {{ $portal === 'admin' ? 'text-indigo-100' : 'text-blue-100' }}">
                    {{ $portal === 'admin' ? 'Gunakan akun admin UMKM atau super admin.' : 'Akses katalog, riwayat pesanan, dan checkout.' }}
                </p>
            </div>

            <div class="p-6">
                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}" class="space-y-4">
                    @csrf

                    <div>
                        <label for="email" class="block text-xs font-bold uppercase tracking-wide {{ $portal === 'admin' ? 'text-slate-300' : 'text-gray-500' }}">Email</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                               class="mt-1 w-full rounded-xl border {{ $portal === 'admin' ? 'border-slate-700 bg-slate-800 text-slate-100 placeholder:text-slate-400' : 'border-gray-200 bg-white text-gray-900' }} px-3 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500">
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <div>
                        <label for="password" class="block text-xs font-bold uppercase tracking-wide {{ $portal === 'admin' ? 'text-slate-300' : 'text-gray-500' }}">Password</label>
                        <input id="password" type="password" name="password" required autocomplete="current-password"
                               class="mt-1 w-full rounded-xl border {{ $portal === 'admin' ? 'border-slate-700 bg-slate-800 text-slate-100 placeholder:text-slate-400' : 'border-gray-200 bg-white text-gray-900' }} px-3 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500">
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <label for="remember_me" class="inline-flex items-center gap-2 text-sm {{ $portal === 'admin' ? 'text-slate-300' : 'text-gray-600' }}">
                        <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                        Ingat saya
                    </label>

                    <button type="submit" class="w-full rounded-xl {{ $portal === 'admin' ? 'bg-indigo-600 hover:bg-indigo-700' : 'bg-blue-600 hover:bg-blue-700' }} text-white font-bold py-3 transition">
                        Masuk
                    </button>
                </form>

                <div class="mt-4 text-center text-sm space-y-2">
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="{{ $portal === 'admin' ? 'text-indigo-300 hover:text-indigo-200' : 'text-blue-600 hover:text-blue-700' }} font-semibold">
                            Lupa password?
                        </a>
                    @endif

                    @if($portal === 'customer')
                        <p class="text-gray-500">Belum punya akun?
                            <a href="{{ route('register') }}" class="font-bold text-blue-600 hover:text-blue-700">Daftar customer</a>
                        </p>
                        <p class="text-gray-500">Akun admin?
                            <a href="{{ route('login.admin') }}" class="font-bold text-indigo-600 hover:text-indigo-700">Masuk portal admin</a>
                        </p>
                    @else
                        <p class="text-slate-400">Akun customer?
                            <a href="{{ route('login') }}" class="font-bold text-indigo-300 hover:text-indigo-200">Masuk portal customer</a>
                        </p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <x-site-footer />
</body>
</html>
