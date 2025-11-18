{{-- borrowModal.blade.php --}}
<div id="borrowModal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4 bg-black/50">
    <div class="bg-white rounded-2xl shadow-2xl max-w-5xl w-full max-h-[90vh] overflow-hidden">
        <div class="flex h-full">
            
            <!-- Left: Thumbnail (Exact match to image) -->
            <div class="w-72 flex-shrink-0 bg-gradient-to-br from-gray-100 to-gray-200 p-6 flex items-center justify-center border-r border-gray-200">
                <div class="w-60 h-96 bg-gray-100 border-8 border-gray-300 rounded-lg shadow-xl flex items-center justify-center overflow-hidden">
                  <img id="modalThumbnail" 
                      src="" 
                      alt="Book Cover" 
                      class="w-full h-full object-cover hidden">
                  <div id="modalThumbnailPlaceholder" 
                      class="w-full h-full bg-gradient-to-br from-gray-400 to-gray-500 flex items-center justify-center text-6xl font-bold text-white">
                      BOOK
                  </div>
              </div>
            </div>

            <!-- Right: Details (Exact match to image) -->
            <div class="flex-1 p-8 flex flex-col">
                
                <!-- Title -->
                <h2 id="modalTitle" class="text-3xl kulim-park-bold font-bold text-gray-900 mb-6 leading-tight"></h2>

                <!-- Rating with Views (★ 4.0 + small gray views count) -->
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center gap-2 text-yellow-500">
                        <span id="modalRating" class="text-4xl font-bold">★ 0.0</span>
                    </div>
                    <div class="text-sm text-gray-500 font-medium">
                        <span id="modalViews">0 views</span>
                    </div>
                </div>

                <!-- Author & Published (Exact layout) -->
                <div class="space-y-3 mb-8 text-gray-800">
                    <p class="flex items-center gap-3">
                        <span class="font-semibold text-sm text-gray-600 uppercase tracking-wide">Author</span>
                        <span id="modalAuthor" class="font-medium text-lg"></span>
                    </p>
                    <p class="flex items-center gap-3">
                        <span class="font-semibold text-sm text-gray-600 uppercase tracking-wide">Originally published</span>
                        <span id="modalPublished" class="font-medium text-lg"></span>
                    </p>
                </div>

                <!-- Tags (Exact: Biography, Rizal) -->
                <div class="mb-8">
                    <p class="font-semibold text-gray-800 text-sm uppercase tracking-wide mb-3">Tags</p>
                    <div id="modalTags" class="flex flex-wrap gap-2"></div>
                </div>

                <!-- Description (Exact spacing & font) -->
                <div class="flex-1 mb-10">
                    <p class="font-semibold text-gray-800 text-sm uppercase tracking-wide mb-4">Description</p>
                    <p id="modalDescription" class="text-gray-700 text-sm leading-relaxed max-h-48 overflow-y-auto pr-2"></p>
                </div>

                <!-- Buttons (Exact: Green + white, perfect spacing) -->
                <div class="flex gap-4 justify-end pt-4 border-t border-gray-200">
                    <button type="button" onclick="closeBorrowModal()"
                            class="px-8 py-3 bg-white text-gray-700 border-2 border-gray-300 rounded-xl hover:bg-gray-50 hover:border-gray-400 transition-all font-medium text-sm shadow-sm">
                        Cancel
                    </button>
                    <form method="POST" action="{{ route('borrow.request') }}" class="inline" id="borrowForm">
                        @csrf
                        <input type="hidden" name="resource_id" id="modalResourceId">
                        <button type="submit" id="borrowButton"
                                class="px-12 py-3 bg-gradient-to-r from-[#22C55E] to-[#16A34A] text-white rounded-xl hover:from-[#16A34A] hover:to-[#15803D] transition-all font-medium text-sm shadow-lg hover:shadow-xl disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:from-[#22C55E] disabled:hover:to-[#16A34A]">
                            Add to Borrow List
                        </button>
                    </form>
                    <div id="alreadyBorrowedMessage" class="hidden px-12 py-3 bg-gray-200 text-gray-600 rounded-xl font-medium text-sm flex items-center gap-2">
                        <i class="fas fa-check-circle"></i>
                        Already on Borrow List
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
  /* Custom scrollbar for description */
  #modalDescription::-webkit-scrollbar { 
    width: 4px; 
  }
  #modalDescription::-webkit-scrollbar-track { 
    background: transparent; 
  }
  #modalDescription::-webkit-scrollbar-thumb { 
    background: #d1d5db; 
    border-radius: 2px; 
  }
  #modalDescription::-webkit-scrollbar-thumb:hover { 
    background: #9ca3af; 
  }

  /* Tag pills (exact match to image) */
  #modalTags span {
    background: #10B981;
    color: white;
    padding: 0.375rem 0.875rem;
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.025em;
  }

  /* Mobile responsiveness */
  @media (max-width: 768px) {
    #borrowModal .bg-white {
      margin: 1rem;
      border-radius: 1rem;
    }
    #borrowModal .w-72 {
      width: 100%;
      height: 280px;
    }
    #borrowModal .w-60 {
      width: 240px;
      height: 100%;
    }
    #borrowModal h2 {
      font-size: 1.75rem !important;
    }
    #borrowModal .text-4xl {
      font-size: 2.5rem !important;
    }
    #borrowModal .flex.gap-4 {
      flex-direction: column-reverse;
      gap: 1rem;
    }
    #borrowModal button {
      flex: 1;
      justify-content: center;
    }
    /* Stack rating and views on mobile for better fit */
    #borrowModal .flex.items-center.justify-between {
      flex-direction: column;
      align-items: flex-start;
      gap: 0.5rem;
    }
  }
</style>