<template>
  <Head title="Resource View" />
  <AppLayout title="" content-padding-classes="px-4 lg:px-[5%]">
    <div class="max-w-7xl mx-auto py-8">
      <button
        @click="goBack"
        class="mb-6 flex items-center gap-2 text-gray-600 hover:text-gray-900 transition-colors"
      >
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
        Back
      </button>

      <div class="bg-white rounded-2xl shadow-2xl max-w-5xl w-full overflow-hidden">
        <div class="flex h-full min-h-0">
          <!-- Left: Thumbnail -->
          <div class="w-72 flex-shrink-0 bg-gradient-to-br from-gray-100 to-gray-200 p-6 flex items-center justify-center border-r border-gray-200">
            <div class="w-60 h-96 bg-gray-100 border-8 border-gray-300 rounded-lg shadow-xl flex items-center justify-center overflow-hidden">
              <img
                v-if="resource.thumbnail_path"
                :src="`/storage/${resource.thumbnail_path}`"
                alt="Book Cover"
                class="w-full h-full object-cover"
              >
              <div
                v-else
                class="w-full h-full bg-gradient-to-br from-gray-400 to-gray-500 flex items-center justify-center text-6xl font-bold text-white"
              >
                {{ resource.Resource_Name?.substring(0, 2).toUpperCase() || 'BOOK' }}
              </div>
            </div>
          </div>

          <!-- Right: Details -->
          <div class="flex-1 p-8 flex flex-col overflow-y-auto min-h-0">
            <!-- Title -->
            <h2 class="text-3xl kulim-park-bold font-bold text-gray-900 mb-6 leading-tight">
              {{ resource.Resource_Name || 'Unknown Resource' }}
            </h2>

            <!-- Rating with Views -->
            <div class="flex items-center justify-between mb-6">
              <div class="flex items-center gap-2 text-yellow-500">
                <span class="text-4xl font-bold">★ {{ resource.average_rating ?? '0.0' }}</span>
              </div>
              <div class="text-sm text-gray-500 font-medium">
                {{ resource.views || 0 }} views
              </div>
            </div>

            <!-- Author & Published -->
            <div class="space-y-3 mb-8 text-gray-800">
              <p class="flex items-center gap-3">
                <span class="font-semibold text-sm text-gray-600 uppercase tracking-wide">Author</span>
                <span class="font-medium text-lg">{{ formatAuthors(resource.authors) }}</span>
              </p>
              <p class="flex items-center gap-3">
                <span class="font-semibold text-sm text-gray-600 uppercase tracking-wide">Originally published</span>
                <span class="font-medium text-lg">{{ resource.formatted_publish_date ?? 'N/A' }}</span>
              </p>
            </div>

            <!-- Rejection Status Box -->
            <div
              v-if="resource.is_rejected && resource.rejection_reason"
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
                  <p class="text-red-700 font-medium">{{ resource.rejection_reason }}</p>
                </div>
              </div>
            </div>

            <!-- Tags -->
            <div class="mb-8">
              <p class="font-semibold text-gray-800 text-sm uppercase tracking-wide mb-3">Tags</p>
              <div class="flex flex-wrap gap-2">
                <template v-if="getTags(resource.tags).length > 0">
                  <span
                    v-for="tag in getTags(resource.tags)"
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
                v-if="resource.Description"
                class="text-gray-700 text-sm leading-relaxed max-h-48 overflow-y-auto pr-2"
              >
                {{ resource.Description }}
              </p>
              <p
                v-else
                class="text-gray-400 text-sm italic"
              >
                No description available.
              </p>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-4 justify-end pt-4 border-t border-gray-200">
              <button
                type="button"
                @click="goBack"
                class="px-8 py-3 bg-white text-gray-700 border-2 border-gray-300 rounded-xl hover:bg-gray-50 hover:border-gray-400 transition-all font-medium text-sm shadow-sm"
              >
                Close
              </button>
              <!-- View Resource Button (for librarians) -->
              <button
                v-if="isLibrarian && resource.File_Path"
                type="button"
                @click="openPreviewModal"
                class="px-8 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-xl hover:from-blue-700 hover:to-blue-800 transition-all font-medium text-sm shadow-lg hover:shadow-xl"
              >
                <i class="fas fa-eye mr-2"></i>
                View Resource
              </button>
              <!-- Flag/Delete Button (for librarians) -->
              <button
                v-if="isLibrarian"
                type="button"
                @click="showFlagModal = true"
                class="px-8 py-3 bg-gradient-to-r from-red-600 to-red-700 text-white rounded-xl hover:from-red-700 hover:to-red-800 transition-all font-medium text-sm shadow-lg hover:shadow-xl"
              >
                <i class="fas fa-trash-alt mr-2"></i>
                Flag Content
              </button>
              <!-- Report Button (for non-owners, non-librarians) -->
              <button
                v-if="!isOwner && !isLibrarian && resource.Type === 'Community Uploads'"
                type="button"
                @click="showReportModal = true"
                class="px-8 py-3 bg-gradient-to-r from-red-600 to-red-700 text-white rounded-xl hover:from-red-700 hover:to-red-800 transition-all font-medium text-sm shadow-lg hover:shadow-xl"
              >
                <i class="fas fa-flag mr-2"></i>
                Report Content
              </button>
              <!-- Borrow Button (only for non-owners, non-librarians) -->
              <template v-if="!isOwner && !isLibrarian">
                <button
                  v-if="resource.is_rejected"
                  type="button"
                  @click="showReturnDateModal = true"
                  class="px-12 py-3 bg-gradient-to-r from-[#22C55E] to-[#16A34A] text-white rounded-xl hover:from-[#16A34A] hover:to-[#15803D] transition-all font-medium text-sm shadow-lg hover:shadow-xl"
                >
                  Request Again
                </button>
                <button
                  v-else-if="!resource.is_borrowed"
                  type="button"
                  @click="showReturnDateModal = true"
                  class="px-12 py-3 bg-gradient-to-r from-[#22C55E] to-[#16A34A] text-white rounded-xl hover:from-[#16A34A] hover:to-[#15803D] transition-all font-medium text-sm shadow-lg hover:shadow-xl"
                >
                  Add to Borrow List
                </button>
                <div
                  v-else
                  class="px-12 py-3 bg-gray-200 text-gray-600 rounded-xl font-medium text-sm flex items-center gap-2"
                >
                  <i class="fas fa-check-circle"></i>
                  Already on Borrow List
                </div>
              </template>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Report Modal -->
    <div
      v-if="showReportModal"
      class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 overflow-y-auto"
      @click.self="showReportModal = false"
    >
      <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full max-h-[90vh] my-4 overflow-y-auto">
        <div class="p-6 border-b border-gray-200">
          <h3 class="text-xl font-bold kulim-park-bold">Report Content</h3>
        </div>
        <form @submit.prevent="submitReport" class="p-6">
          <div class="space-y-4">
            <div>
              <label class="block font-medium mb-2">Reason for Report</label>
              <select
                v-model="reportForm.reason"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500"
                required
              >
                <option value="">Select a reason</option>
                <option value="inappropriate_content">Inappropriate Content</option>
                <option value="copyright_violation">Copyright Violation</option>
                <option value="spam">Spam or Misleading</option>
                <option value="other">Other</option>
              </select>
            </div>
            <div>
              <label class="block font-medium mb-2">Description</label>
              <textarea
                v-model="reportForm.description"
                rows="4"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500"
                placeholder="Please provide details about why you are reporting this content..."
                required
              />
            </div>
          </div>
          <div class="flex gap-2 justify-end mt-6 pt-4 border-t border-gray-200">
            <button
              type="button"
              @click="showReportModal = false"
              class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700"
            >
              Cancel
            </button>
            <button
              type="submit"
              :disabled="!reportForm.reason || !reportForm.description || isSubmittingReport"
              class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 disabled:opacity-50"
            >
              {{ isSubmittingReport ? 'Submitting...' : 'Submit Report' }}
            </button>
          </div>
        </form>
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

    <!-- Flag Modal -->
    <div
      v-if="showFlagModal"
      class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 overflow-y-auto"
      @click.self="showFlagModal = false"
    >
      <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full max-h-[90vh] my-4 overflow-y-auto">
        <div class="p-6 border-b border-gray-200">
          <h3 class="text-xl font-bold kulim-park-bold">Flag Content</h3>
        </div>
        <form @submit.prevent="submitFlag" class="p-6">
          <div class="space-y-4">
            <div>
              <label class="block font-medium mb-2">Reason for Flagging</label>
              <select
                v-model="reportForm.reason"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500"
                required
              >
                <option value="">Select a reason</option>
                <option value="inappropriate_content">Inappropriate Content</option>
                <option value="copyright_violation">Copyright Violation</option>
                <option value="spam">Spam or Misleading</option>
                <option value="violates_policy">Violates Community Policy</option>
                <option value="other">Other</option>
              </select>
            </div>
            <div>
              <label class="block font-medium mb-2">Description</label>
              <textarea
                v-model="reportForm.description"
                rows="4"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500"
                placeholder="Please provide details about why you are flagging this content..."
                required
              />
            </div>
          </div>
          <div class="flex gap-2 justify-end mt-6 pt-4 border-t border-gray-200">
            <button
              type="button"
              @click="showFlagModal = false"
              class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700"
            >
              Cancel
            </button>
            <button
              type="submit"
              :disabled="!reportForm.reason || !reportForm.description || isSubmittingFlag"
              class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 disabled:opacity-50"
            >
              {{ isSubmittingFlag ? 'Submitting...' : 'Flag Content' }}
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- Preview Modal -->
    <div
      v-if="previewUrl"
      class="fixed inset-0 z-50 bg-black/70 flex items-center justify-center p-4 overflow-y-auto"
    >
      <!-- Modal Container -->
      <div class="relative bg-white rounded-2xl shadow-2xl max-w-5xl w-full max-h-[90vh] my-4 overflow-y-auto mx-auto">
        <!-- Close Button - Top Right Corner of Modal -->
        <button
          @click.stop="closePreviewModal"
          class="absolute top-2 right-2 z-20 bg-white text-gray-700 rounded-full p-2 hover:bg-gray-100 shadow-lg border border-gray-200 transition-all"
          aria-label="Close preview"
        >
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
          </svg>
        </button>
        
        <!-- Document Preview -->
        <iframe
          :src="previewUrl"
          class="w-full h-[90vh] border-0"
        />
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { computed, ref } from 'vue';
import { router, usePage, Head} from '@inertiajs/vue3';
import AppLayout from '../Layouts/AppLayout.vue';

const props = defineProps({
  resource: {
    type: Object,
    required: true,
  },
});

const showReturnDateModal = ref(false);
const showReportModal = ref(false);
const showFlagModal = ref(false);
const previewUrl = ref(null);
const returnDate = ref('');
const isSubmitting = ref(false);
const isSubmittingReport = ref(false);
const isSubmittingFlag = ref(false);
const csrfToken = document.head.querySelector('meta[name="csrf-token"]')?.content ?? '';

const page = usePage();
const currentUserId = computed(() => page.props.auth?.user?.id ?? null);
const currentUserRole = computed(() => page.props.auth?.user?.role ?? null);
const isOwner = computed(() => {
  return props.resource.owner_id && props.resource.owner_id === currentUserId.value;
});
const isLibrarian = computed(() => {
  return currentUserRole.value === 'librarian';
});

const reportForm = ref({
  reason: '',
  description: '',
});

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

const goBack = () => {
  if (isLibrarian.value) {
    router.visit('/homeLibrarian');
  } else {
    router.visit('/homeUser');
  }
};

const openPreviewModal = () => {
  if (props.resource.File_Path) {
    previewUrl.value = `/storage/${props.resource.File_Path}`;
  }
};

const closePreviewModal = () => {
  previewUrl.value = null;
};

const submitBorrowWithReturnDate = () => {
  if (!returnDate.value || isSubmitting.value) {
    return;
  }

  const selectedDate = new Date(returnDate.value);
  const now = new Date();
  
  if (selectedDate <= now) {
    alert('Return date and time must be in the future');
    return;
  }

  isSubmitting.value = true;
  router.post('/borrow/request', {
    resource_id: props.resource.Resource_ID,
    return_date: returnDate.value,
  }, {
    onFinish: () => {
      isSubmitting.value = false;
    },
    onSuccess: (page) => {
      const successMessage = page?.props?.flash?.success;
      if (successMessage) {
        alert(successMessage);
      }
      showReturnDateModal.value = false;
      returnDate.value = '';
      // Update the resource to mark as borrowed
      props.resource.is_borrowed = true;
    },
    onError: (errors) => {
      const messages = errors ? Object.values(errors).flat().join('\\n') : 'Unable to submit borrow request. Please try again.';
      alert(messages);
    },
  });
};

const submitReport = () => {
  if (!reportForm.value.reason || !reportForm.value.description || isSubmittingReport.value) {
    return;
  }

  isSubmittingReport.value = true;
  router.post('/resources/report', {
    resource_id: props.resource.Resource_ID,
    reason: reportForm.value.reason,
    description: reportForm.value.description,
  }, {
    onFinish: () => {
      isSubmittingReport.value = false;
    },
    onSuccess: () => {
      showReportModal.value = false;
      reportForm.value = { reason: '', description: '' };
      alert('Report submitted successfully. Thank you for helping keep our community safe.');
    },
  });
};

const submitFlag = () => {
  if (!reportForm.value.reason || !reportForm.value.description || isSubmittingFlag.value) {
    return;
  }

  isSubmittingFlag.value = true;
  router.post('/resources/flag', {
    resource_id: props.resource.Resource_ID,
    reason: reportForm.value.reason,
    description: reportForm.value.description,
  }, {
    onFinish: () => {
      isSubmittingFlag.value = false;
    },
    onSuccess: () => {
      showFlagModal.value = false;
      reportForm.value = { reason: '', description: '' };
      alert('Content flagged successfully. Admin will review this resource.');
      // Stay on the same page - no redirect needed
    },
    onError: (errors) => {
      console.error('Flag error:', errors);
      if (errors && (errors.message?.includes('403') || errors.message?.includes('Unauthorized'))) {
        alert('You do not have permission to flag content. Please ensure you are logged in as a librarian.');
      } else {
        alert('An error occurred while flagging the content. Please try again.');
      }
    },
  });
};
</script>

