<template>
  <Head title="Featured Resources" />
  <AppLayout title="" content-padding-classes="px-4 lg:px-[5%]">
    <div class="flex flex-col lg:pl-28 lg:pr-20 pt-4 content-wrapper">
      <!-- Featured Section -->
      <div class="featured-section w-full">
        <h2 class="text-2xl sm:text-3xl font-extrabold kulim-park-bold tracking-tight mb-4">
          Featured Resources
        </h2>
        
        <div class="filter-tabs mb-4">
          <Link
            :href="`/featured?filter=latest`"
            :class="['filter-tab', { active: filter === 'latest' }]"
          >
            Latest Uploads
          </Link>
          <Link
            :href="`/featured?filter=popular_month`"
            :class="['filter-tab', { active: filter === 'popular_month' }]"
          >
            Popular This Month
          </Link>
          <Link
            :href="`/featured?filter=popular_year`"
            :class="['filter-tab', { active: filter === 'popular_year' }]"
          >
            Popular This Year
          </Link>
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
        </div>
      </div>
    </div>

    <!-- Borrow Modal -->
    <div
      v-if="selectedResource"
      id="borrowModal"
      class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 overflow-y-auto"
      @click.self="closeModal"
    >
      <div class="bg-white rounded-2xl shadow-2xl max-w-5xl w-full max-h-[90vh] my-4 overflow-y-auto mx-auto">
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

            <!-- Buttons -->
            <div class="flex gap-4 justify-end pt-4 border-t border-gray-200">
              <button
                type="button"
                @click="closeModal"
                class="px-8 py-3 bg-white text-gray-700 border-2 border-gray-300 rounded-xl hover:bg-gray-50 hover:border-gray-400 transition-all font-medium text-sm shadow-sm"
              >
                Cancel
              </button>
              <div v-if="selectedResource.is_borrowed" class="px-12 py-3 bg-gray-200 text-gray-600 rounded-xl font-medium text-sm flex items-center gap-2">
                <i class="fas fa-check-circle"></i>
                Already on Borrow List
              </div>
              <form
                v-else
                method="POST"
                :action="`/borrow/request`"
                class="inline"
                @submit.prevent="submitBorrow"
              >
                <input type="hidden" name="_token" :value="csrfToken">
                <input type="hidden" name="resource_id" :value="selectedResource.Resource_ID">
                <button
                  type="submit"
                  :disabled="isSubmitting"
                  class="px-12 py-3 bg-gradient-to-r from-[#22C55E] to-[#16A34A] text-white rounded-xl hover:from-[#16A34A] hover:to-[#15803D] transition-all font-medium text-sm shadow-lg hover:shadow-xl disabled:opacity-50 disabled:cursor-not-allowed"
                >
                  Add to Borrow List
                </button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { Link, router, Head } from '@inertiajs/vue3';
import { ref, onMounted, onUnmounted } from 'vue';
import AppLayout from '../Layouts/AppLayout.vue';

const props = defineProps({
  featuredResources: {
    type: Array,
    required: true,
  },
  filter: {
    type: String,
    default: 'latest',
  },
});

const selectedResource = ref(null);
const isSubmitting = ref(false);
const csrfToken = document.head.querySelector('meta[name="csrf-token"]')?.content ?? '';

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

const openBorrowModal = (resource) => {
  selectedResource.value = resource;
  document.body.style.overflow = 'hidden';
};

const closeModal = () => {
  selectedResource.value = null;
  document.body.style.overflow = '';
};

const submitBorrow = () => {
  if (!selectedResource.value || isSubmitting.value) {
    return;
  }

  isSubmitting.value = true;
  router.post('/borrow/request', {
    resource_id: selectedResource.value.Resource_ID,
  }, {
    onFinish: () => {
      isSubmitting.value = false;
    },
    onSuccess: (page) => {
      const successMessage = page?.props?.flash?.success;
      if (successMessage) {
        alert(successMessage);
      }
      if (selectedResource.value) {
        selectedResource.value.is_borrowed = true;
      }
      closeModal();
    },
    onError: (errors) => {
      const messages = errors ? Object.values(errors).flat().join('\n') : 'Unable to submit borrow request. Please try again.';
      alert(messages);
    },
  });
};

const handleEscape = (e) => {
  if (e.key === 'Escape' && selectedResource.value) {
    closeModal();
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

/* Modal Styles */
#borrowModal {
  backdrop-filter: blur(4px);
}

#borrowModal .text-4xl {
  font-size: 2.25rem;
}

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

