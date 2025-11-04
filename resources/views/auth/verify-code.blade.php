<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Code – ISU StudyGo</title>
    @vite(['resources/css/app.css'])
    <style>
        .digit {
            width: 56px; height: 56px;
            font-size: 1.5rem; font-weight: bold;
            text-align: center;
            border: 2px solid #d1d5db;
            border-radius: .5rem;
            transition: border .2s;
        }
        .digit:focus {
            border-color: #16A34A; outline: none;
            box-shadow: 0 0 0 3px rgba(22,163,74,.15);
        }
    </style>
</head>
<body class="bg-[#000000] min-h-screen flex items-center justify-center p-4">
     <span class="z-[-1] absolute top-1/2 left-1/2 w-[40vw] h-[30vw] -translate-x-1/2 -translate-y-1/2 bg-[#63F068] rounded-full blur-[50vw]"></span>
<div class="bg-white rounded-4xl shadow-lg p-8 max-w-md w-full text-center">
    <img src="{{ Vite::asset('resources/images/ISUStudyGo.svg') }}" alt="Logo"
         class="h-12 mx-auto mb-4">
    <h1 class="text-2xl font-bold text-gray-800">Enter Verification Code</h1>
    <p class="text-gray-600 mt-2 text-sm">
        We sent a 6-digit code to <strong>{{ $email }}</strong>
    </p>

    @if($errors->any())
        <div class="bg-red-100 text-red-700 p-3 rounded mt-4 text-sm">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('verify.code.post') }}" class="mt-6">
        @csrf


        <div class="flex justify-center gap-3 mb-6">
            @for($i = 0; $i < 6; $i++)
                <input type="text" name="digit[{{ $i }}]" maxlength="1"
                    class="digit" autocomplete="off"
                    oninput="this.value=this.value.replace(/[^0-9]/g,'');moveNext(this,{{ $i }})"
                    {{ $i===0 ? 'autofocus' : '' }}>
            @endfor
        </div>

        <button type="submit"
                class="w-full bg-green-800 text-white py-3 rounded-md hover:bg-green-900 transition font-medium">
            Verify
        </button>
    </form>

    <p class="text-sm text-gray-600 mt-6">
        Didn’t receive it?
        <a href="{{ route('register') }}" class="text-green-700 hover:underline font-medium">Register again</a>
    </p>
</div>

<script>
    function moveNext(el, idx) {
        if (el.value.length === 1 && idx < 5) {
            document.getElementsByName(`digit[${idx + 1}]`)[0].focus();
        }
    }

    // Combine digits into hidden `code` field on submit
    document.querySelector('form').addEventListener('submit', function (e) {
        const digits = document.querySelectorAll('input[name^="digit"]');
        let code = '';
        digits.forEach(d => code += d.value);
        const hidden = document.createElement('input');
        hidden.type = 'hidden';
        hidden.name = 'code';
        hidden.value = code;
        this.appendChild(hidden);
    });
</script>
</body>
</html>