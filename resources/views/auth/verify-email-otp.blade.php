<x-guest-layout>

    <div class="mb-4 text-sm text-gray-600">
        {{ __('We sent a 6-digit verification code to your email address. Enter the code below to verify your email.') }}
    </div>

    {{-- Success Message --}}
    @if (session('success'))
        <div class="mb-4 text-sm font-medium text-green-600">
            {{ session('success') }}
        </div>
    @endif

    {{-- Validation / OTP Errors --}}
    @if ($errors->any())
        <div class="mb-4">
            @foreach ($errors->all() as $error)
                <div class="text-sm text-red-600">
                    {{ $error }}
                </div>
            @endforeach
        </div>
    @endif

    {{-- Verify OTP --}}
    <form method="POST" action="{{ route('email.otp.verify') }}">
        @csrf

        <div>
            <x-input-label for="otp" value="Verification Code" />

            <x-text-input
                id="otp"
                class="block mt-1 w-full text-center"
                type="text"
                name="otp"
                value="{{ old('otp') }}"
                required
                autofocus
                inputmode="numeric"
                autocomplete="one-time-code"
                maxlength="6"
                pattern="[0-9]{6}"
                placeholder="000000"
            />
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button>
                {{ __('Verify Email') }}
            </x-primary-button>
        </div>

    </form>

    {{-- Resend OTP --}}
    <div class="mt-4">

        <form method="POST" action="{{ route('email.otp.send') }}">
            @csrf

            <button
                type="submit"
                class="underline text-sm text-gray-600 hover:text-gray-900"
            >
                {{ __('Didn\'t receive the code? Resend Code') }}
            </button>

        </form>

    </div>

</x-guest-layout>