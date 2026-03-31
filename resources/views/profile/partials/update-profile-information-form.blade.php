<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Account Settings') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Update your account's profile information, avatar, and WhatsApp contact.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="profile_photo" :value="__('Profile Photo')" />
            
            <div class="flex items-center gap-4 mt-2 mb-4">
                <div class="shrink-0">
                    @if(Auth::user()->profile_photo)
                        <img id="avatar-preview" src="{{ asset('storage/' . Auth::user()->profile_photo) }}" 
                             class="w-24 h-24 rounded-full object-cover border-4 border-indigo-50 shadow-sm">
                    @else
                        <div id="avatar-placeholder" class="w-24 h-24 rounded-full bg-indigo-500 flex items-center justify-center text-white text-3xl font-black shadow-sm">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                        <img id="avatar-preview" src="#" class="hidden w-24 h-24 rounded-full object-cover border-4 border-indigo-50 shadow-sm">
                    @endif
                </div>

                <div class="flex-1">
                    <input type="file" name="profile_photo" id="profile_photo_input"
                           class="block w-full text-sm text-gray-500 
                                  file:mr-4 file:py-2 file:px-4 
                                  file:rounded-full file:border-0 
                                  file:text-sm file:font-semibold 
                                  file:bg-indigo-50 file:text-indigo-700 
                                  hover:file:bg-indigo-100 transition cursor-pointer"
                           onchange="previewImage(this)" />
                    <p class="text-[10px] text-gray-400 mt-2 uppercase font-bold tracking-widest">JPG, PNG or JPEG (Max 2MB)</p>
                </div>
            </div>
            <x-input-error class="mt-2" :messages="$errors->get('profile_photo')" />
        </div>

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

        <div>
            <x-input-label for="phone" :value="__('WhatsApp Phone Number')" />
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <span class="text-gray-400 text-sm">📱</span>
                </div>
                <x-text-input id="phone" name="phone" type="text" class="mt-1 block w-full pl-10" 
                    :value="old('phone', $user->phone)" 
                    placeholder="+923001234567" />
            </div>
            <p class="text-[10px] text-gray-400 mt-2 uppercase font-bold tracking-widest">Include country code for notifications (e.g., +92)</p>
            <x-input-error class="mt-2" :messages="$errors->get('phone')" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save Changes') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-green-600 font-bold"
                >{{ __('Settings Saved Successfully.') }}</p>
            @endif
        </div>
    </form>

    <script>
        function previewImage(input) {
            const preview = document.getElementById('avatar-preview');
            const placeholder = document.getElementById('avatar-placeholder');
            
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                    if(placeholder) placeholder.classList.add('hidden');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</section>