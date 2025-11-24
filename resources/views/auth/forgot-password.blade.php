<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password – ISU StudyGo</title>
    <link rel="icon" href="{{ Vite::asset('resources/images/FINAL_SEAL.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Kantumruy+Pro:wght@400;500;600&family=Kulim+Park:wght@400;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/css/kulimpark.css', 'resources/css/Inter.css', 'resources/css/kantumruypro.css'])
    <style>
        body { background-color: #000000 !important; min-height: 100vh; }
        .digit {
            width: 50px;
            height: 56px;
            font-size: 1.5rem;
            font-weight: bold;
            text-align: center;
            border: 2px solid #d1d5db;
            border-radius: .5rem;
            transition: border .2s;
        }
        .digit:focus {
            border-color: #16A34A; outline: none;
            box-shadow: 0 0 0 3px rgba(22,163,74,.15);
        }
        .status-pill {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: #DCFCE7;
            color: #166534;
            padding: 0.5rem 0.85rem;
            border-radius: 999px;
            font-size: 0.85rem;
            font-weight: 600;
            margin-top: 1rem;
        }
        input[disabled] {
            cursor: not-allowed;
            background: #f3f4f6 !important;
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4 relative" style="background-color: #000000 !important;">
    <span class="z-[-1] absolute top-1/2 left-1/2 w-[40vw] h-[30vw] -translate-x-1/2 -translate-y-1/2 bg-[#63F068] rounded-full blur-[50vw]"></span>

    <div class="bg-white rounded-4xl shadow-lg p-8 max-w-xl w-full relative z-10 space-y-8">
        <div class="text-center">
            <img src="{{ Vite::asset('resources/images/ISUStudyGo.svg') }}" alt="Logo" class="h-12 mx-auto mb-4">
            <h1 class="text-3xl font-bold text-gray-900">Reset your password</h1>
            <p class="text-gray-600 mt-2 text-sm max-w-md mx-auto">
                Enter your registered email to receive a 6-digit reset code. Once you have the code, enter it below with your new password.
            </p>

            @if(session('status') === 'reset-code-sent')
                <div class="status-pill justify-center mx-auto">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path d="M5 13l4 4L19 7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Reset code sent! Please check your email.
                </div>
            @elseif(session('status') === 'password-reset-success')
                <div class="status-pill justify-center mx-auto bg-green-600/10 text-green-800">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path d="M5 13l4 4L19 7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Password updated! You can now sign in.
                </div>
            @endif

            @if($errors->any())
                <div class="bg-red-100 text-red-700 p-3 rounded mt-4 text-sm">
                    {{ $errors->first() }}
                </div>
            @endif
        </div>

        @php($resetData = session('password_reset'))
        @php($resetEmail = old('email', $resetData['email'] ?? ''))

        <div class="space-y-10">
            <section id="request-code-section" class="space-y-4">
                <h2 class="text-lg font-semibold text-gray-800">Step 1: Request a reset code</h2>
                <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-left text-sm font-medium text-gray-700 mb-1">Email Address</label>
                        <input
                            type="email"
                            name="email"
                            value="{{ old('email', $resetEmail) }}"
                            required
                            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-green-500 focus:border-green-500"
                            placeholder="your@email.com"
                        >
                    </div>
                    <button
                        type="submit"
                        class="w-full bg-green-700 text-white py-3 rounded-lg hover:bg-green-800 transition font-semibold"
                    >
                        Send Reset Code
                    </button>
                </form>
            </section>

            <section class="space-y-4">
                <h2 class="text-lg font-semibold text-gray-800">Step 2: Enter code & new password</h2>
                <form method="POST" action="{{ route('password.reset.code') }}" class="space-y-5">
                    @csrf
                    @if($resetEmail)
                        <div class="bg-green-50 border border-green-200 rounded-lg p-3 text-sm text-green-800 flex items-center justify-between gap-2">
                            <span>
                                Resetting password for
                                <strong>{{ $resetEmail }}</strong>
                            </span>
                            <button type="button" class="text-green-700 text-xs font-semibold hover:underline" onclick="document.getElementById('request-code-section').scrollIntoView({ behavior: 'smooth' })">
                                Use another email
                            </button>
                        </div>
                        <input type="hidden" name="email" value="{{ $resetEmail }}">
                    @else
                        <div class="bg-gray-100 text-gray-600 rounded-lg p-3 text-sm">
                            Request a reset code above to unlock this step.
                        </div>
                    @endif

                    <div>
                        <label class="block text-left text-sm font-medium text-gray-700 mb-2">6-Digit Code</label>
                        <div class="flex justify-center gap-3">
                            @for($i = 0; $i < 6; $i++)
                                <input
                                    type="text"
                                    name="digit[{{ $i }}]"
                                    maxlength="1"
                                    class="digit"
                                    autocomplete="off"
                                    oninput="this.value=this.value.replace(/[^0-9]/g,'');moveNext(this,{{ $i }})"
                                    {{ $i === 0 ? 'autofocus' : '' }}
                                    {{ $resetEmail ? '' : 'disabled' }}
                                >
                            @endfor
                        </div>
                    </div>

                    <div class="grid sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-left text-sm font-medium text-gray-700 mb-1">New Password</label>
                            <input
                                type="password"
                                name="password"
                                class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                placeholder="Enter new password"
                                {{ $resetEmail ? '' : 'disabled' }}
                            >
                        </div>
                        <div>
                            <label class="block text-left text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                            <input
                                type="password"
                                name="password_confirmation"
                                class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                placeholder="Confirm new password"
                                {{ $resetEmail ? '' : 'disabled' }}
                            >
                        </div>
                    </div>

                    <button
                        type="submit"
                        class="w-full bg-gray-900 text-white py-3 rounded-lg hover:bg-black transition font-semibold disabled:opacity-60 disabled:cursor-not-allowed"
                        {{ $resetEmail ? '' : 'disabled' }}
                    >
                        Update Password
                    </button>

                    <p class="text-center text-sm text-gray-600">
                        Remembered your password?
                        <a href="{{ route('login') }}" class="text-green-700 font-semibold hover:underline">Sign in instead</a>
                    </p>
                </form>
            </section>
        </div>
    </div>

    <script>
        const resetEmailPresent = '{{ $resetEmail ? 'yes' : 'no' }}';
        function moveNext(el, idx) {
            if (el.value.length === 1 && idx < 5) {
                const next = document.getElementsByName(`digit[${idx + 1}]`)[0];
                if (next && !next.disabled) {
                    next.focus();
                }
            }
        }

        const codeForm = document.querySelector('section:nth-of-type(2) form');
        if (codeForm) {
            codeForm.addEventListener('submit', function (e) {
                const digits = document.querySelectorAll('input[name^="digit"]');
                let code = '';
                digits.forEach(d => code += d.value);

                if (resetEmailPresent !== 'yes') {
                    e.preventDefault();
                    alert('Please request a reset code first.');
                    return;
                }

                const hidden = document.createElement('input');
                hidden.type = 'hidden';
                hidden.name = 'code';
                hidden.value = code;
                this.appendChild(hidden);
            });
        }
    </script>
    @include('partials.globalLoader')
</body>
</html>

