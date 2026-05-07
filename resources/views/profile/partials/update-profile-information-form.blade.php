<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        @if($user->role === 'admin_umkm' && $user->umkm)
            <div class="rounded-xl border border-blue-100 bg-blue-50 p-4 space-y-4">
                <p class="text-sm font-bold text-blue-900">Profil Toko UMKM (tampil di halaman toko)</p>
                <div>
                    <x-input-label for="umkm_description" value="Deskripsi toko" />
                    <textarea id="umkm_description" name="umkm_description" rows="3"
                              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('umkm_description', $user->umkm->description) }}</textarea>
                    <x-input-error class="mt-2" :messages="$errors->get('umkm_description')" />
                </div>
                <div>
                    <x-input-label for="umkm_contact_name" value="Nama kontak" />
                    <x-text-input id="umkm_contact_name" name="umkm_contact_name" type="text" class="mt-1 block w-full"
                                  :value="old('umkm_contact_name', $user->umkm->contact_name)" />
                    <x-input-error class="mt-2" :messages="$errors->get('umkm_contact_name')" />
                </div>
                <div>
                    <x-input-label for="umkm_contact_phone" value="No. WhatsApp / Telepon" />
                    <x-text-input id="umkm_contact_phone" name="umkm_contact_phone" type="text" class="mt-1 block w-full"
                                  :value="old('umkm_contact_phone', $user->umkm->contact_phone)" />
                    <p class="mt-1 text-xs text-gray-500">Format bebas (contoh: 0812..., +62812..., atau 62...). Nanti otomatis dibuat link WhatsApp.</p>
                    <x-input-error class="mt-2" :messages="$errors->get('umkm_contact_phone')" />
                </div>
            </div>
        @endif

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
