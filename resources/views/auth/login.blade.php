<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="mt-6 flex items-center justify-between gap-4">
            <a href="{{ route('password.request') }}" class="text-sm text-indigo-600 hover:text-indigo-700 underline">
                {{ __('Forgot your password?') }}
            </a>

            <button type="submit" style="padding: 0.5rem 1.5rem; background-color: rgb(79, 70, 229); color: white; font-weight: 600; border-radius: 0.5rem; border: none; cursor: pointer;" onmouseover="this.style.backgroundColor='rgb(67, 56, 202)'" onmouseout="this.style.backgroundColor='rgb(79, 70, 229)'">
                {{ __('Log in') }}
            </button>
        </div>
    </form>
</x-guest-layout>
