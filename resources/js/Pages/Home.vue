<template>
    <Head title="Home" />
  <AppLayout title="" content-padding-classes="px-4 lg:px-[5%]">
    

    <div class="flex flex-col lg:pl-28 lg:pr-20 lg:flex-row gap-10 pt-4 content-wrapper">
      <!-- Featured Section -->
      <div class="featured-section" style="flex: 0 0 70%;">
        <h2 class="text-2xl sm:text-3xl font-extrabold kulim-park-bold tracking-tight mb-4">
          ISU Featured Resources
        </h2>
        
        <div class="filter-tabs mb-4">
          <button
            type="button"
            @click="changeFilter('latest')"
            :class="['filter-tab', { active: filter === 'latest' }]"
          >
            Latest Uploads
          </button>
          <button
            type="button"
            @click="changeFilter('popular_month')"
            :class="['filter-tab', { active: filter === 'popular_month' }]"
          >
            Popular This Month
          </button>
          <button
            type="button"
            @click="changeFilter('popular_year')"
            :class="['filter-tab', { active: filter === 'popular_year' }]"
          >
            Popular This Year
          </button>
        </div>

        <div class="books-grid">
          <div
            v-for="resource in featuredResources"
            :key="resource.Resource_ID"
            class="book-card cursor-pointer transition-opacity duration-200"
            role="button"
            tabindex="0"
            @click="openBorrowModal(resource)"
            @keydown.enter="openBorrowModal(resource)"
            @keydown.space.prevent="openBorrowModal(resource)"
          >
            <div class="book-cover bg-gray-400">
              <img
                v-if="resource.thumbnail_path"
                :src="`/storage/${resource.thumbnail_path}`"
                :alt="resource.Resource_Name"
                class="w-full h-full object-cover"
                loading="lazy"
              >
              <span v-else class="text-white text-4xl font-bold">
                {{ resource.Resource_Name.substring(0, 2).toUpperCase() }}
              </span>
            </div>
            <div class="book-info">
              <h3 class="book-title">{{ resource.Resource_Name }}</h3>
              <div class="flex items-center gap-1 text-yellow-500 text-sm kantumruy-pro-regular">
                <span>★</span>
                <span class="text-gray-700 font-medium kantumruy-pro-regular">
                  {{ resource.average_rating ?? '0.0' }}
                </span>
              </div>
              <p class="book-author text-sm text-gray-600">
                {{ formatAuthors(resource.authors) }}
              </p>
              <p class="text-xs text-gray-500 kantumruy-pro-regular">
                Published: {{ resource.formatted_publish_date }}
              </p>
              <div class="mt-2 flex flex-wrap gap-1">
                <span
                  v-for="tag in getTags(resource.tags).slice(0, 2)"
                  :key="tag"
                  class="text-xs bg-green-100 text-green-800 px-2 py-0.5 rounded-full kantumruy-pro-regular"
                >
                  {{ tag }}
                </span>
                <span
                  v-if="getTags(resource.tags).length === 0"
                  class="text-xs text-gray-400 kantumruy-pro-regular"
                >
                  No tags
                </span>
              </div>
            </div>
          </div>

          <div
            v-if="featuredResources.length === 0"
            class="col-span-full text-center py-12 text-gray-500 kantumruy-pro-regular"
          >
            No featured resources available.
          </div>
          
          <!-- View More Button -->
          <Link
            v-if="featuredResources.length >= 7"
            href="/featured"
            class="book-card flex items-center justify-center bg-green-50 hover:bg-green-100 transition-colors border-2 border-dashed border-green-500 rounded-lg"
          >
            <div class="text-center">
              <div class="text-4xl font-bold text-green-700 mb-2">+</div>
              <div class="text-sm font-semibold text-green-700">View More</div>
            </div>
          </Link>
        </div>
      </div>

      <!-- Right Sidebar: Popular Tags and Community Uploads -->
      <div class="flex flex-col gap-6" style="flex: 0 0 30%;">
        <!-- Popular Tags Section -->
        <div v-if="popularTags && popularTags.length > 0">
          <h2 class="text-xl font-extrabold kulim-park-bold tracking-tight mb-4">
            Popular Tags
          </h2>
          <div class="bg-white rounded-lg p-4 shadow-lg">
            <div class="flex flex-wrap gap-2">
              <span
                v-for="tag in popularTags"
                :key="tag.id"
                class="text-xs bg-green-100 text-green-800 px-3 py-1.5 rounded-full font-medium hover:bg-green-200 transition-colors cursor-pointer kantumruy-pro-regular"
              >
                {{ tag.name }}
              </span>
            </div>
          </div>
        </div>

        <!-- Community Uploads -->
        <div class="community-section">
          <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-extrabold kulim-park-bold tracking-tight">
              Community Uploads
            </h2>
            <button
              @click="openCommunityUploadModal"
              class="flex items-center justify-center w-10 h-10 rounded-full bg-green-600 text-white hover:bg-green-700 transition-colors shadow-lg hover:shadow-xl"
              title="Upload Community Resource"
            >
              <i class="fas fa-plus text-lg"></i>
            </button>
          </div>
          <div class="bg-white rounded-lg p-4 shadow-lg">
            <Link
              v-for="resource in communityUploads"
              :key="resource.Resource_ID"
              :href="`/resources/${resource.Resource_ID}/view`"
              class="community-item"
            >
              <div class="community-icon">
                {{ resource.Resource_Name.substring(0, 2).toUpperCase() }}
              </div>
              <div class="community-info">
                <div class="community-title">{{ resource.Resource_Name }}</div>
                <div class="community-author">
                  by {{ resource.user?.full_name ?? 'Unknown' }}
                </div>
              </div>
            </Link>
            <div
              v-if="communityUploads.length === 0"
              class="text-center py-8 text-gray-500 kantumruy-pro-regular"
            >
              No community uploads yet.
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Community Upload Modal -->
    <div
      v-if="showCommunityUploadModal"
      class="fixed inset-0 z-[10000] flex items-center justify-center p-4 bg-black/50 overflow-y-auto"
      @click.self="closeCommunityUploadModal"
    >
      <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl my-4 mx-auto max-h-[90vh] overflow-y-auto">
        <div class="sticky top-0 bg-white border-b border-gray-200 px-6 py-4 flex items-center justify-between z-10">
          <h3 class="text-2xl font-bold kulim-park-bold">Upload Community Resource</h3>
          <button
            @click="closeCommunityUploadModal"
            class="text-gray-500 hover:text-gray-700 text-2xl"
          >
            <i class="fas fa-times"></i>
          </button>
        </div>
        <form @submit.prevent="submitCommunityUpload" class="p-6 space-y-4">
          <div>
            <label class="block font-medium mb-1">Title *</label>
            <input
              v-model="communityUploadForm.Resource_Name"
              type="text"
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
              required
            >
          </div>
          <div>
            <label class="block font-medium mb-1">Author(s) (comma-separated)</label>
            <input
              v-model="communityUploadForm.authors"
              type="text"
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
              placeholder="Leave blank to use your name"
            >
          </div>
          <div>
            <label class="block font-medium mb-1">Description *</label>
            <textarea
              v-model="communityUploadForm.Description"
              rows="4"
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
              required
            ></textarea>
          </div>
          <div>
            <label class="block font-medium mb-1">Tags (press Space or + to add)</label>
            <div class="flex flex-wrap gap-1.5 mb-2">
              <span
                v-for="(tag, index) in communityUploadTagsArray"
                :key="index"
                class="inline-flex items-center px-2 py-0.5 bg-green-100 text-green-800 rounded-full text-xs"
              >
                {{ tag }}
                <span
                  @click.stop="removeCommunityTag(index)"
                  class="ml-1 text-green-600 hover:text-green-900 cursor-pointer leading-none"
                  title="Remove tag"
                >×</span>
              </span>
            </div>
            <input
              v-model="communityUploadTagsInput"
              type="text"
              placeholder="Type tag and press Space or +"
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
              @keydown="handleCommunityTagKeydown"
            >
          </div>
          <div>
            <label class="block font-medium mb-1">Publish Date</label>
            <input
              v-model="communityUploadForm.publish_date"
              type="date"
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
            >
          </div>
          <div>
            <label class="block font-medium mb-1">File *</label>
            <div
              class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center cursor-pointer hover:border-green-500 transition-colors"
              @click="$refs.communityFileInput.click()"
              @dragover.prevent="addDragOver = true"
              @dragleave.prevent="addDragOver = false"
              @drop.prevent="handleCommunityFileDrop"
              :class="{ 'border-green-500 bg-green-50': addDragOver }"
            >
              <p v-if="!communityUploadForm.file">Drag & drop file here or click to upload</p>
              <p v-else class="text-green-700 font-medium">{{ communityUploadForm.file.name }}</p>
            </div>
            <input
              ref="communityFileInput"
              type="file"
              class="hidden"
              accept=".pdf,.doc,.docx,.zip"
              @change="handleCommunityFileSelect"
              required
            >
          </div>
          <div class="flex gap-2 justify-end pt-4 border-t border-gray-200">
            <button
              type="button"
              @click="closeCommunityUploadModal"
              class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700"
            >
              Cancel
            </button>
            <button
              type="submit"
              :disabled="isUploading"
              class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 disabled:opacity-50"
            >
              {{ isUploading ? 'Uploading...' : 'Upload' }}
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- Borrow Modal -->
    <div
      v-if="selectedResource"
      id="borrowModal"
      class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 overflow-y-auto"
      @click.self="closeModal"
    >
      <div class="bg-white rounded-2xl shadow-2xl max-w-5xl w-full max-h-[90vh] my-4 overflow-y-auto">
        <div class="flex h-full">
          <!-- Left: Thumbnail -->
          <div class="w-72 flex-shrink-0 bg-gradient-to-br from-gray-100 to-gray-200 p-6 flex items-center justify-center border-r border-gray-200">
            <div class="w-60 h-96 bg-gray-100 border-8 border-gray-300 rounded-lg shadow-xl flex items-center justify-center overflow-hidden">
              <img
                v-if="selectedResource.thumbnail_path"
                :src="`/storage/${selectedResource.thumbnail_path}`"
                alt="Book Cover"
                class="w-full h-full object-cover"
              >
              <div
                v-else
                class="w-full h-full bg-gradient-to-br from-gray-400 to-gray-500 flex items-center justify-center text-6xl font-bold text-white"
              >
                {{ selectedResource.Resource_Name.substring(0, 2).toUpperCase() }}
              </div>
            </div>
          </div>

          <!-- Right: Details -->
          <div class="flex-1 p-8 flex flex-col">
            <!-- Title -->
            <h2 class="text-3xl kulim-park-bold font-bold text-gray-900 mb-6 leading-tight">
              {{ selectedResource.Resource_Name }}
            </h2>

            <!-- Rating with Views -->
            <div class="flex items-center justify-between mb-6">
              <div class="flex items-center gap-2 text-yellow-500">
                <span class="text-4xl font-bold">★ {{ selectedResource.average_rating ?? '0.0' }}</span>
              </div>
              <div class="text-sm text-gray-500 font-medium">
                {{ selectedResource.views || 0 }} views
              </div>
            </div>

            <!-- Author & Published -->
            <div class="space-y-3 mb-8 text-gray-800">
              <p class="flex items-center gap-3">
                <span class="font-semibold text-sm text-gray-600 uppercase tracking-wide">Author</span>
                <span class="font-medium text-lg">{{ formatAuthors(selectedResource.authors) }}</span>
              </p>
              <p class="flex items-center gap-3">
                <span class="font-semibold text-sm text-gray-600 uppercase tracking-wide">Originally published</span>
                <span class="font-medium text-lg">{{ selectedResource.formatted_publish_date ?? 'N/A' }}</span>
              </p>
            </div>

            <!-- Tags -->
            <div class="mb-8">
              <p class="font-semibold text-gray-800 text-sm uppercase tracking-wide mb-3">Tags</p>
              <div class="flex flex-wrap gap-2">
                <template v-if="getTags(selectedResource.tags).length > 0">
                  <span
                    v-for="tag in getTags(selectedResource.tags)"
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

            <!-- Description -->
            <div class="flex-1 mb-10">
              <p class="font-semibold text-gray-800 text-sm uppercase tracking-wide mb-4">Description</p>
              <p
                v-if="selectedResource.Description"
                class="text-gray-700 text-sm leading-relaxed max-h-48 overflow-y-auto pr-2"
              >
                {{ selectedResource.Description }}
              </p>
              <p
                v-else
                class="text-gray-400 text-sm italic"
              >
                No description available.
              </p>
            </div>

            <!-- Rejection Status Box -->
            <div
              v-if="selectedResource.is_rejected && selectedResource.rejection_reason"
              class="mb-8 p-5 bg-red-50 border-2 border-red-300 rounded-xl"
            >
              <div class="flex items-start gap-3">
                <div class="flex-shrink-0">
                  <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                </div>
                <div class="flex-1">
                  <p class="font-bold text-lg text-red-800 mb-2">Request Rejected</p>
                  <p class="text-red-700 font-medium">{{ selectedResource.rejection_reason }}</p>
                </div>
              </div>
            </div>

            <!-- Buttons -->
            <div class="flex gap-4 justify-end pt-4 border-t border-gray-200">
              <button
                type="button"
                @click="closeModal"
                class="px-8 py-3 bg-white text-gray-700 border-2 border-gray-300 rounded-xl hover:bg-gray-50 hover:border-gray-400 transition-all font-medium text-sm shadow-sm"
              >
                Cancel
              </button>
              <button
                v-if="selectedResource.is_rejected"
                type="button"
                @click="showReturnDateModal = true"
                :disabled="isSubmitting"
                class="px-12 py-3 bg-gradient-to-r from-[#22C55E] to-[#16A34A] text-white rounded-xl hover:from-[#16A34A] hover:to-[#15803D] transition-all font-medium text-sm shadow-lg hover:shadow-xl disabled:opacity-50 disabled:cursor-not-allowed"
              >
                Request Again
              </button>
              <div
                v-else-if="selectedResource.is_borrowed"
                class="px-12 py-3 bg-gray-200 text-gray-600 rounded-xl font-medium text-sm flex items-center gap-2"
              >
                <i class="fas fa-check-circle"></i>
                Already on Borrow List
              </div>
              <button
                v-else
                type="button"
                @click="showReturnDateModal = true"
                :disabled="isSubmitting"
                class="px-12 py-3 bg-gradient-to-r from-[#22C55E] to-[#16A34A] text-white rounded-xl hover:from-[#16A34A] hover:to-[#15803D] transition-all font-medium text-sm shadow-lg hover:shadow-xl disabled:opacity-50 disabled:cursor-not-allowed"
              >
                Add to Borrow List
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Return Date Modal -->
    <div
      v-if="showReturnDateModal"
      class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 overflow-y-auto"
      @click.self="showReturnDateModal = false"
    >
      <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full max-h-[90vh] my-4 overflow-y-auto">
        <div class="p-6 border-b border-gray-200">
          <h3 class="text-xl font-bold kulim-park-bold">Select Return Date</h3>
        </div>
        <div class="p-6">
          <div class="mb-4">
            <label class="block font-medium mb-2">Expected Return Date</label>
            <input
              v-model="returnDate"
              type="datetime-local"
              :min="minReturnDate"
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
              required
            >
            <p class="text-xs text-gray-500 mt-1">Please select when you plan to return this resource</p>
          </div>
        </div>
        <div class="p-6 border-t border-gray-200 flex justify-end gap-2">
          <button
            type="button"
            @click="showReturnDateModal = false"
            class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700"
          >
            Cancel
          </button>
          <button
            type="button"
            @click="submitBorrowWithReturnDate"
            :disabled="!returnDate || isSubmitting"
            class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 disabled:opacity-50"
          >
            Confirm
          </button>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { Link, router, Head, usePage } from '@inertiajs/vue3';
import { ref, computed, onMounted, onUnmounted } from 'vue';
import AppLayout from '../Layouts/AppLayout.vue';

const props = defineProps({
  featuredResources: {
    type: Array,
    required: true,
  },
  communityUploads: {
    type: Array,
    required: true,
  },
  filter: {
    type: String,
    default: 'latest',
  },
  popularTags: {
    type: Array,
    default: () => [],
  },
});

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
    // Handle both string arrays and object arrays
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

const page = usePage();
const searchIcon = computed(() => page.props.images?.searchIcon || '/images/Search.png');

const selectedResource = ref(null);
const isSubmitting = ref(false);
const csrfToken = document.head.querySelector('meta[name="csrf-token"]')?.content ?? '';
const showReturnDateModal = ref(false);
const returnDate = ref('');

// Community Upload Modal
const showCommunityUploadModal = ref(false);
const communityUploadForm = ref({
  Resource_Name: '',
  authors: '',
  Description: '',
  tags: '',
  publish_date: '',
  file: null,
});
const communityUploadTagsInput = ref('');
const communityUploadTagsArray = computed({
  get: () => {
    if (!communityUploadForm.value.tags) return [];
    return communityUploadForm.value.tags.split(',').map(t => t.trim()).filter(t => t);
  },
  set: (tags) => {
    communityUploadForm.value.tags = tags.join(', ');
  }
});
const isUploading = ref(false);
const addDragOver = ref(false);

// Search functionality
const searchQuery = ref('');
const searchResults = ref([]);
const showSearchResults = ref(false);
const isSearching = ref(false);
let searchTimeout = null;

const minReturnDate = computed(() => {
  const now = new Date();
  now.setMinutes(now.getMinutes() + 1); // At least 1 minute from now
  // Format as datetime-local (YYYY-MM-DDTHH:mm)
  const year = now.getFullYear();
  const month = String(now.getMonth() + 1).padStart(2, '0');
  const day = String(now.getDate()).padStart(2, '0');
  const hours = String(now.getHours()).padStart(2, '0');
  const minutes = String(now.getMinutes()).padStart(2, '0');
  return `${year}-${month}-${day}T${hours}:${minutes}`;
});

const handleSearch = () => {
  if (searchTimeout) {
    clearTimeout(searchTimeout);
  }
  
  if (searchQuery.value.length < 2) {
    searchResults.value = [];
    showSearchResults.value = false;
    return;
  }

  isSearching.value = true;
  searchTimeout = setTimeout(async () => {
    try {
      const response = await fetch(`/resources/search?q=${encodeURIComponent(searchQuery.value)}`, {
        headers: {
          'X-Requested-With': 'XMLHttpRequest',
          'Accept': 'application/json',
        },
      });
      const data = await response.json();
      searchResults.value = data;
      showSearchResults.value = true;
    } catch (error) {
      console.error('Search error:', error);
      searchResults.value = [];
    } finally {
      isSearching.value = false;
    }
  }, 300);
};

const handleSearchBlur = () => {
  // Delay hiding to allow click events on results
  setTimeout(() => {
    showSearchResults.value = false;
  }, 200);
};

const changeFilter = (filterValue) => {
  router.get('/homeUser', { filter: filterValue }, {
    preserveState: true,
    preserveScroll: true,
  });
};

const openBorrowModal = (resource) => {
  selectedResource.value = resource;
  document.body.style.overflow = 'hidden';
};

const closeModal = () => {
  selectedResource.value = null;
  document.body.style.overflow = '';
};

const submitBorrowWithReturnDate = () => {
  if (!selectedResource.value || !returnDate.value || isSubmitting.value) {
    return;
  }

  // Check if return date is in the past (shouldn't happen with min date, but double check)
  const selectedDate = new Date(returnDate.value);
  const now = new Date();
  
  if (selectedDate <= now) {
    alert('Return date and time must be in the future');
    return;
  }

  isSubmitting.value = true;
  router.post('/borrow/request', {
    resource_id: selectedResource.value.Resource_ID,
    return_date: returnDate.value,
  }, {
    onFinish: () => {
      isSubmitting.value = false;
    },
    onSuccess: () => {
      // Update the resource to mark as borrowed
      if (selectedResource.value) {
        selectedResource.value.is_borrowed = true;
      }
      showReturnDateModal.value = false;
      returnDate.value = '';
      closeModal();
    },
  });
};

// Community Upload Functions
const openCommunityUploadModal = () => {
  showCommunityUploadModal.value = true;
  document.body.style.overflow = 'hidden';
};

const closeCommunityUploadModal = () => {
  showCommunityUploadModal.value = false;
  communityUploadForm.value = {
    Resource_Name: '',
    authors: '',
    Description: '',
    tags: '',
    publish_date: '',
    file: null,
  };
  communityUploadTagsInput.value = '';
  addDragOver.value = false;
  document.body.style.overflow = '';
};

const handleCommunityFileSelect = (event) => {
  if (event.target.files && event.target.files[0]) {
    communityUploadForm.value.file = event.target.files[0];
    if (!communityUploadForm.value.Resource_Name) {
      const filename = event.target.files[0].name;
      communityUploadForm.value.Resource_Name = filename.replace(/\.[^/.]+$/, '');
    }
  }
};

const handleCommunityFileDrop = (event) => {
  addDragOver.value = false;
  if (event.dataTransfer.files && event.dataTransfer.files[0]) {
    communityUploadForm.value.file = event.dataTransfer.files[0];
    if (!communityUploadForm.value.Resource_Name) {
      const filename = event.dataTransfer.files[0].name;
      communityUploadForm.value.Resource_Name = filename.replace(/\.[^/.]+$/, '');
    }
  }
};

const handleCommunityTagKeydown = (event) => {
  if ((event.key === ' ' || event.key === '+' || event.key === 'Enter') && communityUploadTagsInput.value.trim()) {
    event.preventDefault();
    const newTag = communityUploadTagsInput.value.trim();
    if (newTag && !communityUploadTagsArray.value.includes(newTag)) {
      communityUploadTagsArray.value = [...communityUploadTagsArray.value, newTag];
    }
    communityUploadTagsInput.value = '';
  }
};

const removeCommunityTag = (index) => {
  const newTags = [...communityUploadTagsArray.value];
  newTags.splice(index, 1);
  communityUploadTagsArray.value = newTags;
};

const submitCommunityUpload = () => {
  if (!communityUploadForm.value.file) {
    alert('Please select a file');
    return;
  }

  // Ensure tags are properly formatted before submission
  if (communityUploadTagsArray.value.length > 0) {
    communityUploadForm.value.tags = communityUploadTagsArray.value.join(', ');
  } else {
    communityUploadForm.value.tags = '';
  }

  // Parse publish_date if provided
  let publish_year = null;
  let publish_month = null;
  let publish_day = null;
  if (communityUploadForm.value.publish_date) {
    const date = new Date(communityUploadForm.value.publish_date);
    publish_year = date.getFullYear();
    publish_month = date.getMonth() + 1;
    publish_day = date.getDate();
  }

  isUploading.value = true;
  const formData = new FormData();
  formData.append('Resource_Name', communityUploadForm.value.Resource_Name);
  formData.append('authors', communityUploadForm.value.authors || '');
  formData.append('Description', communityUploadForm.value.Description);
  formData.append('tags', communityUploadForm.value.tags || '');
  if (publish_year) formData.append('publish_year', publish_year);
  if (publish_month) formData.append('publish_month', publish_month);
  if (publish_day) formData.append('publish_day', publish_day);
  formData.append('file', communityUploadForm.value.file);
  formData.append('is_community_upload', '1'); // Mark as community upload

  console.log('Submitting community upload:', {
    Resource_Name: communityUploadForm.value.Resource_Name,
    file: communityUploadForm.value.file?.name,
    fileSize: communityUploadForm.value.file?.size,
  });

  router.post('/resources/community-upload', formData, {
    forceFormData: true,
    onStart: () => {
      console.log('Upload started');
    },
    onFinish: () => {
      console.log('Upload finished');
      isUploading.value = false;
    },
    onSuccess: (page) => {
      console.log('Upload success', page);
      // Show success message if available
      const successMessage = page.props?.flash?.success || page.props?.flash?.message;
      if (successMessage) {
        alert(successMessage);
      }
      closeCommunityUploadModal();
      router.reload();
    },
    onError: (errors) => {
      console.error('Upload error:', errors);
      // Show validation errors
      if (errors) {
        const errorMessages = Object.values(errors).flat().join('\n');
        alert('Upload failed:\n' + errorMessages);
      } else {
        alert('Upload failed. Please check the console for details and try again.');
      }
    },
  });
};

// Handle escape key to close modal
const handleEscape = (e) => {
  if (e.key === 'Escape') {
    if (selectedResource.value) {
      closeModal();
    }
    if (showCommunityUploadModal.value) {
      closeCommunityUploadModal();
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
.filter-tabs {
  display: flex;
  gap: 1rem;
  margin-bottom: 1rem;
}

.filter-tab {
  padding: 0.5rem 1rem;
  border-radius: 0.5rem;
  background: white;
  color: #4b5563;
  text-decoration: none;
  transition: all 0.2s;
  font-weight: 500;
  border: 1px solid #e5e7eb;
}

.filter-tab:hover {
  background: #f3f4f6;
}

.filter-tab.active {
  background: #22c55e;
  color: white;
}

.books-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
  gap: 1rem;
  max-width: 100%;
}

@media (min-width: 1024px) {
  .books-grid {
    grid-template-columns: repeat(4, 1fr);
  }
}

.book-card {
  background: white;
  border-radius: 0.5rem;
  overflow: hidden;
  border: 1px solid #e5e7eb;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08), 0 1px 2px rgba(0, 0, 0, 0.06);
  transition: transform 0.2s, box-shadow 0.2s, border-color 0.2s;
}

.book-card:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 12px rgba(0, 0, 0, 0.12), 0 4px 6px rgba(0, 0, 0, 0.08);
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
  padding: 0.75rem;
}

.book-title {
  font-weight: 600;
  font-size: 0.875rem;
  margin-bottom: 0.25rem;
  line-height: 1.2;
  overflow: hidden;
  text-overflow: ellipsis;
  display: -webkit-box;
  -webkit-line-clamp: 2;
  line-clamp: 2;
  -webkit-box-orient: vertical;
}

.book-author {
  margin-top: 0.25rem;
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
}

.community-item {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  padding: 0.75rem;
  border-radius: 0.5rem;
  transition: background 0.2s;
  text-decoration: none;
  color: inherit;
}

.community-item:hover {
  background: #f3f4f6;
}

.community-icon {
  width: 2.5rem;
  height: 2.5rem;
  background: #22c55e;
  color: white;
  border-radius: 0.5rem;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: bold;
  flex-shrink: 0;
}

.community-info {
  flex: 1;
  min-width: 0;
}

.community-title {
  font-weight: 600;
  font-size: 0.875rem;
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
}

.community-author {
  font-size: 0.75rem;
  color: #6b7280;
  margin-top: 0.25rem;
}

/* Modal Styles */
#borrowModal {
  backdrop-filter: blur(4px);
}

#borrowModal .text-4xl {
  font-size: 2.25rem;
}

/* Custom scrollbar for description */
#borrowModal .overflow-y-auto::-webkit-scrollbar {
  width: 4px;
}

#borrowModal .overflow-y-auto::-webkit-scrollbar-track {
  background: transparent;
}

#borrowModal .overflow-y-auto::-webkit-scrollbar-thumb {
  background: #d1d5db;
  border-radius: 2px;
}

#borrowModal .overflow-y-auto::-webkit-scrollbar-thumb:hover {
  background: #9ca3af;
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
}
</style>

