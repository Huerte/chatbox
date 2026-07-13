<x-guest-layout>
    <div class="max-w-sm mx-auto">
        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email Address -->
            <div>
                <x-input-label for="email" :value="__('Email')" class="text-white" />
                <x-text-input id="email" class="block mt-1 w-full text-white bg-zinc-800 border-zinc-700 focus:border-indigo-500 focus:ring-indigo-500" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Password -->
            <div class="mt-4">
                <x-input-label for="password" :value="__('Password')" class="text-white" />

                <x-text-input id="password" class="block mt-1 w-full text-white bg-zinc-800 border-zinc-700 focus:border-indigo-500 focus:ring-indigo-500"
                                type="password"
                                name="password"
                                required autocomplete="current-password" />

                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Remember Me -->
            <div class="block mt-4">
                <label for="remember_me" class="inline-flex items-center">
                    <input id="remember_me" type="checkbox" class="rounded border-zinc-700 bg-zinc-800 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                    <span class="ms-2 text-sm text-gray-300">{{ __('Remember me') }}</span>
                </label>
            </div>

           <!-- Full-width Log in Button directly under Remember Me -->
            <div class="mt-5">
                <x-primary-button class="w-full justify-center py-2.5 bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-900">
                    {{ __('Log in') }}
                </x-primary-button>
            </div>

            <!-- Bottom Action Row -->
            <div class="flex items-center justify-end mt-4">
                @if (Route::has('password.request'))
                    <a class="underline text-sm text-red-400 hover:text-red-300 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                        {{ __('Forgot your password?') }}
                    </a>
                @endif

                <!-- Smaller Register Button next to Forgot your password -->
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="ms-3 inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-sm">
                        {{ __('Register') }}
                    </a>
                @endif
            </div>
        </form>
    </div>
</x-guest-layout>