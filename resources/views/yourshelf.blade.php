<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" href="{{ Vite::asset('resources/images/FINAL_SEAL.png') }}">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Kulim+Park:ital,wght@0,200;0,300;0,400;0,600;0,700;1,200;1,300;1,400;1,600;1,700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  @vite(['resources/css/app.css', 'resources/css/output.css', 'resources/css/Inter.css', 'resources/css/kulimpark.css'])
  <title>Your Shelf | ISU StudyGo</title>
</head>
<body class="bg-gray-50">
<div class="container mx-auto px-6 py-6">

    <h2 class="text-2xl font-bold mb-6">Borrow Status</h2>

    @forelse($borrows as $borrow)
        <div class="block bg-white p-4 rounded-lg shadow mb-4 hover:shadow-lg transition">
            <div class="flex gap-4">
                @if($borrow->resource && $borrow->resource->thumbnail_path)
                    <img src="{{ asset('storage/' . $borrow->resource->thumbnail_path) }}"
                         alt="{{ $borrow->resource->Resource_Name }}"
                         class="w-20 h-28 object-cover rounded">
                @else
                    <div class="w-20 h-28 bg-gray-300 rounded flex items-center justify-center">
                        <span class="text-gray-500 text-xs text-center">No Image</span>
                    </div>
                @endif

                <div class="flex-1">
                    <h3 class="font-bold text-lg">{{ $borrow->resource->Resource_Name ?? 'Unknown Resource' }}</h3>
                    <p class="text-gray-600">Status: 
                        <span class="font-semibold">
                            @if($borrow->isReturned)
                                Returned
                            @elseif($borrow->Approved_Date)
                                Approved
                            @else
                                Pending
                            @endif
                        </span>
                    </p>

                    @if($borrow->Approved_Date)
                        <p class="text-sm text-gray-500">Borrowed: {{ \Carbon\Carbon::parse($borrow->Approved_Date)->format('M d, Y') }}</p>
                    @endif
                    @if($borrow->Return_Date)
                        <p class="text-sm text-gray-500">Return: {{ \Carbon\Carbon::parse($borrow->Return_Date)->format('M d, Y') }}</p>
                    @endif

                    @if($borrow->Approved_Date && $borrow->resource)
                        <div class="mt-3">
                            <a href="/viewer/{{ $borrow->resource->Resource_ID }}" 
                               target="_blank"
                               class="inline-block bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-lg transition-colors">
                                <i class="fas fa-book-open mr-2"></i>View PDF
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @empty
        <div class="bg-white p-8 rounded-lg shadow text-center">
            <p class="text-gray-600 text-lg">You don't have any borrows yet.</p>
        </div>
    @endforelse

</div>
</body>
</html>
