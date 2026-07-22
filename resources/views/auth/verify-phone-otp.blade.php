<x-guest-layout>

    <div class="mb-4 text-sm text-gray-600">
        {{ __('Enter your phone number to receive a 6-digit verification code. Then enter the code below to verify your phone.') }}
    </div>

    {{-- Success Message --}}
    @if (session('success'))
        <div class="mb-4 text-sm font-medium text-green-600">
            {{ session('success') }}
        </div>
    @endif

    {{-- General Errors --}}
    @if ($errors->any())
        <div class="mb-4">
            @foreach ($errors->all() as $error)
                <div class="text-sm text-red-600">
                    {{ $error }}
                </div>
            @endforeach
        </div>
    @endif


    {{-- ========================================= --}}
    {{-- PHONE NUMBER / SEND OTP --}}
    {{-- ========================================= --}}

    <form method="POST" action="{{ route('phone.otp.send') }}">
        @csrf

        <div>
            <x-input-label
                for="phone"
                :value="__('Phone Number')"
            />

            <x-text-input
                id="phone"
                class="block mt-1 w-full"
                type="tel"
                name="phone"
                :value="old('phone', auth()->user()->phone)"
                required
                autofocus
                autocomplete="tel"
                placeholder="+8801XXXXXXXXX"
            />

            <x-input-error
                :messages="$errors->get('phone')"
                class="mt-2"
            />
        </div>

        <div class="flex items-center justify-end mt-4">

            <x-primary-button>
                {{ auth()->user()->phone ? __('Send / Resend Code') : __('Send Code') }}
            </x-primary-button>

        </div>

    </form>


    {{-- ========================================= --}}
    {{-- VERIFY OTP --}}
    {{-- ========================================= --}}

    @if (auth()->user()->phone_otp)

        <hr class="my-6">

        <div class="mb-4 text-sm text-gray-600">
            {{ __('Enter the 6-digit verification code for:') }}

            <strong>
                {{ auth()->user()->phone }}
            </strong>
        </div>

        <form method="POST" action="{{ route('phone.otp.verify') }}">
            @csrf

            <div>
                <x-input-label
                    for="otp"
                    :value="__('Verification Code')"
                />

                <x-text-input
                    id="otp"
                    class="block mt-1 w-full text-center"
                    type="text"
                    name="otp"
                    value="{{ old('otp') }}"
                    required
                    inputmode="numeric"
                    autocomplete="one-time-code"
                    maxlength="6"
                    pattern="[0-9]{6}"
                    placeholder="000000"
                />

                <x-input-error
                    :messages="$errors->get('otp')"
                    class="mt-2"
                />

            </div>

            <div class="flex items-center justify-end mt-4">

                <x-primary-button>
                    {{ __('Verify Phone') }}
                </x-primary-button>

            </div>

        </form>

    @endif

</x-guest-layout>