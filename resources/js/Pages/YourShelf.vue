<template>
  <AppLayout title="" content-padding-classes="px-4 lg:px-[5%]">
    <div class="flex flex-col lg:pl-28 lg:pr-20 pt-4 content-wrapper">
      <div class="w-full">
        <h2 class="text-2xl sm:text-3xl font-extrabold kulim-park-bold tracking-tight mb-6">
          Your Shelf
        </h2>

        <!-- Community Uploads Section (for Librarians) -->
        <div v-if="userRole === 'librarian'" class="mb-8">
          <div class="bg-gradient-to-r from-green-50 to-emerald-50 border-2 border-green-200 rounded-2xl p-6 shadow-md">
            <h3 class="text-xl font-bold kulim-park-bold mb-3 text-gray-800 flex items-center gap-2">
              <i class="fas fa-users text-green-600"></i>
              Community Uploads Management
            </h3>
            <p class="text-gray-600 mb-4">
              Review and manage pending community upload requests from users.
            </p>
            <Link
              href="/resource-management?type=Community Uploads"
              class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-[#22C55E] to-[#16A34A] text-white rounded-xl hover:from-[#16A34A] hover:to-[#15803D] transition-all font-medium text-sm shadow-lg hover:shadow-xl"
            >
              <i class="fas fa-arrow-right"></i>
              Go to Community Uploads
            </Link>
          </div>
        </div>

        <div v-if="borrows.length === 0" class="bg-white rounded-2xl shadow-lg p-12 text-center text-gray-600 text-lg">
          You don't have any borrows yet. Browse featured resources to start reading!
        </div>

        <div v-else>
          <!-- Pending Requests -->
          <div v-if="pendingBorrows.length > 0" class="mb-8">
            <h3 class="text-xl font-bold kulim-park-bold mb-4 text-gray-800">Pending Requests</h3>
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
              <div class="overflow-x-auto">
                <table class="w-full">
                  <thead class="bg-gray-50">
                    <tr>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Resource</th>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Requested Date</th>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    </tr>
                  </thead>
                  <tbody class="bg-white divide-y divide-gray-200">
                    <tr
                      v-for="borrow in pendingBorrows"
                      :key="borrow.id"
                      class="hover:bg-gray-50"
                    >
                      <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center gap-3">
                          <div
                            v-if="borrow.resource?.thumbnail_path"
                            class="w-12 h-16 rounded overflow-hidden flex-shrink-0"
                          >
                            <img
                              :src="`/storage/${borrow.resource.thumbnail_path}`"
                              :alt="borrow.resource.Resource_Name"
                              class="w-full h-full object-cover"
                            >
                          </div>
                          <div
                            v-else
                            class="w-12 h-16 bg-gray-200 rounded flex items-center justify-center uppercase text-gray-500 text-xs flex-shrink-0"
                          >
                            {{ borrow.resource?.Resource_Name?.substring(0, 2).toUpperCase() || 'NA' }}
                          </div>
                          <span class="font-semibold text-gray-900">{{ borrow.resource?.Resource_Name || 'Unknown Resource' }}</span>
                        </div>
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap text-gray-600">
                        {{ formatDate(borrow.created_at) }}
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-3 py-1 text-sm rounded-full bg-yellow-100 text-yellow-700 font-medium">
                          Pending Approval
                        </span>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>

          <!-- Active Borrows -->
          <div v-if="activeBorrows.length > 0" class="mb-8">
            <h3 class="text-xl font-bold kulim-park-bold mb-4 text-gray-800">Active Borrows</h3>
            <div class="books-grid">
              <div
                v-for="borrow in activeBorrows"
                :key="borrow.id"
                class="book-card cursor-pointer"
                @click="openBookModal(borrow)"
              >
                <div class="book-cover bg-gray-400">
                  <img
                    v-if="borrow.resource?.thumbnail_path"
                    :src="`/storage/${borrow.resource.thumbnail_path}`"
                    :alt="borrow.resource.Resource_Name"
                    class="w-full h-full object-cover"
                  >
                  <span v-else class="text-white text-4xl font-bold">
                    {{ borrow.resource?.Resource_Name?.substring(0, 2).toUpperCase() || 'NA' }}
                  </span>
                </div>
                <div class="book-info">
                  <h3 class="book-title">{{ borrow.resource?.Resource_Name || 'Unknown Resource' }}</h3>
                  <p class="text-xs text-gray-500">
                    Borrowed: {{ formatDate(borrow.Approved_Date) }}
                  </p>
                </div>
              </div>
            </div>
          </div>

          <!-- Returned Books -->
          <div v-if="returnedBorrows.length > 0">
            <h3 class="text-xl font-bold kulim-park-bold mb-4 text-gray-800">Returned Books</h3>
            <div class="books-grid">
              <div
                v-for="borrow in returnedBorrows"
                :key="borrow.id"
                class="book-card cursor-pointer"
                @click="openRatingModal(borrow)"
              >
                <div class="book-cover bg-gray-400">
                  <img
                    v-if="borrow.resource?.thumbnail_path"
                    :src="`/storage/${borrow.resource.thumbnail_path}`"
                    :alt="borrow.resource.Resource_Name"
                    class="w-full h-full object-cover"
                  >
                  <span v-else class="text-white text-4xl font-bold">
                    {{ borrow.resource?.Resource_Name?.substring(0, 2).toUpperCase() || 'NA' }}
                  </span>
                </div>
                <div class="book-info">
                  <h3 class="book-title">{{ borrow.resource?.Resource_Name || 'Unknown Resource' }}</h3>
                  <p class="text-xs text-gray-500">
                    Returned: {{ borrow.Return_Date ? formatDate(borrow.Return_Date) : 'N/A' }}
                  </p>
                  <div class="flex items-center gap-1 mt-2">
                    <span class="text-yellow-500 text-sm">★</span>
                    <span class="text-xs text-gray-600">
                      {{ borrow.resource?.average_rating ?? '0.0' }}
                    </span>
                    <span v-if="borrow.userRating" class="text-xs text-green-600 ml-2">
                      (You rated: {{ borrow.userRating }})
                    </span>
                  </div>
                  <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-700 inline-block mt-1">
                    Returned
                  </span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Book Modal -->
    <div
      v-if="selectedBorrow"
      id="shelfModal"
      class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 overflow-y-auto"
      @click.self="closeModal"
    >
      <div class="bg-white rounded-2xl shadow-2xl max-w-5xl w-full max-h-[90vh] my-4 overflow-y-auto">
        <div class="flex h-full min-h-0">
          <!-- Left: Thumbnail -->
          <div class="w-72 flex-shrink-0 bg-gradient-to-br from-gray-100 to-gray-200 p-6 flex items-center justify-center border-r border-gray-200">
            <div class="w-60 h-96 bg-gray-100 border-8 border-gray-300 rounded-lg shadow-xl flex items-center justify-center overflow-hidden">
              <img
                v-if="selectedBorrow.resource?.thumbnail_path"
                :src="`/storage/${selectedBorrow.resource.thumbnail_path}`"
                alt="Book Cover"
                class="w-full h-full object-cover"
              >
              <div
                v-else
                class="w-full h-full bg-gradient-to-br from-gray-400 to-gray-500 flex items-center justify-center text-6xl font-bold text-white"
              >
                {{ selectedBorrow.resource?.Resource_Name?.substring(0, 2).toUpperCase() || 'BOOK' }}
              </div>
            </div>
          </div>

          <!-- Right: Details -->
          <div class="flex-1 p-8 flex flex-col overflow-y-auto min-h-0">
            <h2 class="text-3xl kulim-park-bold font-bold text-gray-900 mb-6 leading-tight">
              {{ selectedBorrow.resource?.Resource_Name || 'Unknown Resource' }}
            </h2>

            <div class="flex items-center justify-between mb-6">
              <div class="flex items-center gap-2 text-yellow-500">
                <span class="text-4xl font-bold">★ {{ selectedBorrow.resource?.average_rating ?? '0.0' }}</span>
              </div>
              <div class="text-sm text-gray-500 font-medium">
                {{ selectedBorrow.resource?.views || 0 }} views
              </div>
            </div>

            <div class="space-y-3 mb-8 text-gray-800">
              <p class="flex items-center gap-3">
                <span class="font-semibold text-sm text-gray-600 uppercase tracking-wide">Author</span>
                <span class="font-medium text-lg">{{ formatAuthors(selectedBorrow.resource?.authors) }}</span>
              </p>
              <p class="flex items-center gap-3">
                <span class="font-semibold text-sm text-gray-600 uppercase tracking-wide">Originally published</span>
                <span class="font-medium text-lg">{{ selectedBorrow.resource?.formatted_publish_date ?? 'N/A' }}</span>
              </p>
              <p class="flex items-center gap-3">
                <span class="font-semibold text-sm text-gray-600 uppercase tracking-wide">Borrowed</span>
                <span class="font-medium text-lg">{{ formatDate(selectedBorrow.Approved_Date) }}</span>
              </p>
            </div>

            <div class="mb-8">
              <p class="font-semibold text-gray-800 text-sm uppercase tracking-wide mb-3">Tags</p>
              <div class="flex flex-wrap gap-2">
                <template v-if="getTags(selectedBorrow.resource?.tags).length > 0">
                  <span
                    v-for="tag in getTags(selectedBorrow.resource?.tags)"
                    :key="tag"
                    class="bg-[#10B981] text-white px-3.5 py-1.5 rounded-full text-xs font-medium uppercase tracking-wide"
                  >
                    {{ tag }}
                  </span>
                </template>
                <span
                  v-else
                  class="text-gray-400 text-sm italic"
                >
                  No tags
                </span>
              </div>
            </div>

            <div class="flex-1 mb-10">
              <p class="font-semibold text-gray-800 text-sm uppercase tracking-wide mb-4">Description</p>
              <p
                v-if="selectedBorrow.resource?.Description"
                class="text-gray-700 text-sm leading-relaxed max-h-48 overflow-y-auto pr-2"
              >
                {{ selectedBorrow.resource.Description }}
              </p>
              <p
                v-else
                class="text-gray-400 text-sm italic"
              >
                No description available.
              </p>
            </div>

            <div class="flex gap-4 justify-end pt-4 border-t border-gray-200">
              <button
                type="button"
                @click="closeModal"
                class="px-8 py-3 bg-white text-gray-700 border-2 border-gray-300 rounded-xl hover:bg-gray-50 hover:border-gray-400 transition-all font-medium text-sm shadow-sm"
              >
                Close
              </button>
              <a
                v-if="selectedBorrow.resource?.Resource_ID && selectedBorrow.Approved_Date"
                :href="`/viewer/${selectedBorrow.resource.Resource_ID}`"
                target="_blank"
                rel="noopener noreferrer"
                class="px-12 py-3 bg-gradient-to-r from-[#22C55E] to-[#16A34A] text-white rounded-xl hover:from-[#16A34A] hover:to-[#15803D] transition-all font-medium text-sm shadow-lg hover:shadow-xl inline-block text-center no-underline"
                @click.stop
              >
                View Book
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Rating Modal -->
    <div
      v-if="ratingBorrow"
      id="ratingModal"
      class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50"
      @click.self="closeRatingModal"
    >
      <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-hidden">
        <div class="p-8">
          <h2 class="text-2xl kulim-park-bold font-bold text-gray-900 mb-6">
            Rate This Book
          </h2>
          
          <div class="mb-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-2">
              {{ ratingBorrow.resource?.Resource_Name || 'Unknown Resource' }}
            </h3>
            <p class="text-sm text-gray-600">
              by {{ formatAuthors(ratingBorrow.resource?.authors) }}
            </p>
          </div>

          <div class="mb-8">
            <label class="block text-sm font-medium text-gray-700 mb-4">
              Your Rating
            </label>
            <div class="flex gap-2 justify-center">
              <button
                v-for="star in 5"
                :key="star"
                type="button"
                @click="selectedRating = star"
                class="text-5xl transition-all hover:scale-110"
                :class="star <= selectedRating ? 'text-yellow-400' : 'text-gray-300'"
              >
                ★
              </button>
            </div>
            <p class="text-center text-sm text-gray-600 mt-2">
              {{ selectedRating ? `${selectedRating} out of 5 stars` : 'Select a rating' }}
            </p>
          </div>

          <div class="flex gap-4 justify-end pt-4 border-t border-gray-200">
            <button
              type="button"
              @click="closeRatingModal"
              class="px-8 py-3 bg-white text-gray-700 border-2 border-gray-300 rounded-xl hover:bg-gray-50 hover:border-gray-400 transition-all font-medium text-sm shadow-sm"
            >
              Cancel
            </button>
            <button
              type="button"
              @click="submitRating"
              :disabled="!selectedRating || isSubmittingRating"
              class="px-12 py-3 bg-gradient-to-r from-[#22C55E] to-[#16A34A] text-white rounded-xl hover:from-[#16A34A] hover:to-[#15803D] transition-all font-medium text-sm shadow-lg hover:shadow-xl disabled:opacity-50 disabled:cursor-not-allowed"
            >
              {{ isSubmittingRating ? 'Submitting...' : 'Submit Rating' }}
            </button>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { Link, usePage, router } from '@inertiajs/vue3';
import { computed, ref, onMounted, onUnmounted } from 'vue';
import AppLayout from '../Layouts/AppLayout.vue';

const props = defineProps({
  borrows: {
    type: Array,
    required: true,
  },
});

const page = usePage();
const userRole = computed(() => {
  return page.props.auth?.user?.role || null;
});

const selectedBorrow = ref(null);
const ratingBorrow = ref(null);
const selectedRating = ref(0);
const isSubmittingRating = ref(false);
const csrfToken = document.head.querySelector('meta[name="csrf-token"]')?.content ?? '';

const pendingBorrows = computed(() => {
  return props.borrows.filter(borrow => !borrow.Approved_Date);
});

const activeBorrows = computed(() => {
  return props.borrows.filter(borrow => !borrow.isReturned && borrow.Approved_Date);
});

const returnedBorrows = computed(() => {
  return props.borrows.filter(borrow => borrow.isReturned || borrow.Return_Date);
});

const formatDate = (date) => {
  if (!date) {
    return 'N/A';
  }
  return new Date(date).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' });
};

const formatAuthors = (authors) => {
  if (!authors) {
    return 'Unknown Author';
  }
  if (Array.isArray(authors)) {
    return authors.map(a => a.name || a).join(', ') || 'Unknown Author';
  }
  if (typeof authors === 'string') {
    return authors;
  }
  return 'Unknown Author';
};

const getTags = (tags) => {
  if (!tags) {
    return [];
  }
  if (Array.isArray(tags)) {
    return tags.map(t => {
      if (typeof t === 'string') {
        return t;
      }
      if (typeof t === 'object' && t !== null) {
        return t.name || t.Name || String(t);
      }
      return String(t);
    }).filter(Boolean);
  }
  return [];
};

const openBookModal = (borrow) => {
  selectedBorrow.value = borrow;
  document.body.style.overflow = 'hidden';
};

const closeModal = () => {
  selectedBorrow.value = null;
  document.body.style.overflow = '';
};

const openRatingModal = (borrow) => {
  ratingBorrow.value = borrow;
  selectedRating.value = borrow.userRating || 0;
  document.body.style.overflow = 'hidden';
};

const closeRatingModal = () => {
  ratingBorrow.value = null;
  selectedRating.value = 0;
  document.body.style.overflow = '';
};

const submitRating = () => {
  if (!ratingBorrow.value || !selectedRating.value || isSubmittingRating.value) {
    return;
  }

  isSubmittingRating.value = true;
  router.post('/ratings', {
    resource_id: ratingBorrow.value.resource.Resource_ID,
    rating: selectedRating.value,
  }, {
    onFinish: () => {
      isSubmittingRating.value = false;
    },
    onSuccess: () => {
      // Update the borrow with the new rating
      if (ratingBorrow.value) {
        ratingBorrow.value.userRating = selectedRating.value;
      }
      closeRatingModal();
      // Reload to get updated average rating
      router.reload({ only: ['borrows'] });
    },
  });
};

const handleEscape = (e) => {
  if (e.key === 'Escape') {
    if (selectedBorrow.value) {
      closeModal();
    }
    if (ratingBorrow.value) {
      closeRatingModal();
    }
  }
};

onMounted(() => {
  window.addEventListener('keydown', handleEscape);
});

onUnmounted(() => {
  window.removeEventListener('keydown', handleEscape);
  document.body.style.overflow = '';
});
</script>

<style scoped>
.books-grid {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 1rem;
  width: 100%;
}

@media (min-width: 640px) {
  .books-grid {
    grid-template-columns: repeat(3, 1fr);
  }
}

@media (min-width: 1024px) {
  .books-grid {
    grid-template-columns: repeat(5, 1fr);
  }
}

.book-card {
  background: white;
  border-radius: 16px;
  overflow: hidden;
  border: 1px solid #e5e7eb;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08), 0 1px 2px rgba(0, 0, 0, 0.06);
  transition: all 0.3s ease;
  cursor: pointer;
  display: flex;
  flex-direction: column;
  height: 100%;
}

.book-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15), 0 4px 8px rgba(0, 0, 0, 0.1);
  border-color: #d1d5db;
}

.book-cover {
  width: 100%;
  height: 200px;
  display: flex;
  align-items: center;
  justify-content: center;
  overflow: hidden;
}

.book-info {
  padding: 1rem;
}

.book-title {
  font-weight: 600;
  font-size: 0.875rem;
  margin-bottom: 0.5rem;
  line-height: 1.3;
  overflow: hidden;
  text-overflow: ellipsis;
  display: -webkit-box;
  -webkit-line-clamp: 2;
  line-clamp: 2;
  -webkit-box-orient: vertical;
}

#shelfModal {
  backdrop-filter: blur(4px);
}

#shelfModal .overflow-y-auto::-webkit-scrollbar {
  width: 4px;
}

#shelfModal .overflow-y-auto::-webkit-scrollbar-track {
  background: transparent;
}

#shelfModal .overflow-y-auto::-webkit-scrollbar-thumb {
  background: #d1d5db;
  border-radius: 2px;
}

@media (max-width: 768px) {
  #shelfModal .bg-white {
    margin: 1rem;
    border-radius: 1rem;
  }
  #shelfModal .w-72 {
    width: 100%;
    height: 280px;
  }
}
</style>

