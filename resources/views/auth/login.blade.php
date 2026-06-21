<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="mb-6">
        <h2 class="font-headline-md text-2xl font-semibold text-on-surface">Welcome back</h2>
        <p class="font-body-sm text-sm text-on-surface-variant mt-1">Please enter your details to sign in.</p>
    </div>

    <!-- Error State Example -->
    @if ($errors->any())
        <div class="mb-6 bg-error-container text-on-error-container p-2 rounded-md flex items-center gap-2">
            <span class="material-symbols-outlined text-sm" style="font-variation-settings: 'FILL' 1;">error</span>
            <span class="font-body-sm text-sm">Email atau password salah</span>
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <!-- Email Input -->
        <div>
            <label class="block font-label-sm text-xs font-medium text-on-surface mb-1" for="email">Email</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-2 flex items-center pointer-events-none">
                    <span class="material-symbols-outlined text-outline text-sm">mail</span>
                </div>
                <input class="block w-full pl-8 pr-2 py-2 bg-surface rounded-md border border-outline-variant text-on-surface focus:ring-primary focus:border-primary font-body-md text-base placeholder-outline transition-colors" 
                       id="email" name="email" value="{{ old('email') }}" placeholder="name@company.com" required autofocus autocomplete="username" type="email"/>
            </div>
        </div>

        <!-- Password Input -->
        <div>
            <div class="flex justify-between items-center mb-1">
                <label class="block font-label-sm text-xs font-medium text-on-surface" for="password">Password</label>
                @if (Route::has('password.request'))
                    <a class="font-label-sm text-xs font-medium text-secondary hover:text-secondary-fixed-dim transition-colors" href="{{ route('password.request') }}">Forgot Password?</a>
                @endif
            </div>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-2 flex items-center pointer-events-none">
                    <span class="material-symbols-outlined text-outline text-sm">lock</span>
                </div>
                <input class="block w-full pl-8 pr-2 py-2 bg-surface rounded-md border border-outline-variant text-on-surface focus:ring-primary focus:border-primary font-body-md text-base placeholder-outline transition-colors" 
                       id="password" name="password" placeholder="••••••••" required autocomplete="current-password" type="password"/>
            </div>
        </div>

        <!-- Submit Button -->
        <button class="w-full flex justify-center items-center py-4 px-6 mt-6 border border-transparent rounded-md shadow-sm font-label-md text-sm font-semibold text-on-secondary bg-secondary hover:bg-on-secondary-fixed-variant focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-secondary transition-all active:scale-[0.98]" type="submit">
            Masuk
        </button>
    </form>

    <!-- Return Link -->
    <div class="mt-6 text-center">
        <a class="inline-flex items-center gap-1 font-label-sm text-xs font-medium text-on-surface-variant hover:text-primary transition-colors" href="/">
            <span class="material-symbols-outlined text-[16px]">arrow_back</span>
            Return to Landing Page
        </a>
    </div>
</x-guest-layout>
