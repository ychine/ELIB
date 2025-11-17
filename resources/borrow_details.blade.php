@extends('layouts.app')

@section('content')
<div class="container mx-auto px-6 py-6">

    {{-- FLASH (FLESH) MESSAGE --}}
    @if(session('success'))
        <div class="bg-green-500 text-white p-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-500 text-white p-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif


    <h2 class="text-2xl font-bold mb-4">Borrow Details</h2>

    <div class="bg-white p-5 rounded shadow">
        <h3 class="font-bold text-xl mb-2">{{ $borrow->book->title }}</h3>

        <p><strong>Status:</strong> {{ $borrow->status }}</p>

        <p><strong>Borrow Date:</strong> {{ $borrow->borrow_date ?? 'Not yet' }}</p>

        <p><strong>Return Date:</strong> {{ $borrow->return_date ?? 'Not yet' }}</p>

        <p class="text-gray-600 mt-4">
            * The librarian's decision will show here (Approved / Rejected / Borrowed)
        </p>
    </div>

</div>
@endsection
