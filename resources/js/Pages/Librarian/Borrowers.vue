<template>
  <AppLayout title="Borrow Requests" content-padding-classes="px-[10%] lg:px-[10%]">
    <div class="rounded-2xl bg-white border border-gray-200 shadow-lg p-6">
      <!-- Flash Messages -->
      <div
        v-if="flashSuccess"
        class="bg-green-100 text-green-700 p-3 rounded mb-4 text-sm"
      >
        {{ flashSuccess }}
      </div>
      <div
        v-if="flashError"
        class="bg-red-100 text-red-700 p-3 rounded mb-4 text-sm"
      >
        {{ flashError }}
      </div>

      <!-- Search Bar -->
      <div class="mb-4">
        <input
          v-model="searchTerm"
          type="text"
          placeholder="Search by requester name, email, or resource name..."
          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
        >
      </div>

      <!-- Borrow Requests Table -->
      <div class="overflow-x-auto">
        <table class="w-full bg-white rounded border border-gray-200 shadow-sm">
          <thead>
            <tr class="bg-gray-200">
              <th class="py-3 px-6 border-b border-gray-400 text-left kantumruy-pro-regular font-semibold">Requester</th>
              <th class="py-3 px-6 border-b border-gray-400 text-left kantumruy-pro-regular font-semibold">Resource</th>
              <th class="py-3 px-6 border-b border-gray-400 text-left kantumruy-pro-regular font-semibold">Request Date</th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="request in filteredRequests"
              :key="request.Borrower_ID"
              class="hover:bg-gray-50 transition-colors cursor-pointer border-b border-gray-300"
              @click="openDetailsModal(request)"
            >
              <td class="py-3 px-6 kantumruy-pro-regular">
                <div>
                  <div class="truncate font-medium" :title="request.user?.full_name ?? 'Unknown'">
                    {{ request.user?.full_name ?? 'Unknown' }}
                  </div>
                  <small class="text-gray-500 text-xs">{{ request.user?.email ?? 'N/A' }}</small>
                </div>
              </td>
              <td class="py-3 px-6 kantumruy-pro-regular">
                <div class="truncate font-medium" :title="request.resource?.Resource_Name ?? 'Unknown Resource'">
                  {{ request.resource?.Resource_Name ?? 'Unknown Resource' }}
                </div>
              </td>
              <td class="py-3 px-6 kantumruy-pro-regular">
                {{ formatDate(request.created_at) }}
              </td>
            </tr>
            <tr v-if="filteredRequests.length === 0">
              <td colspan="3" class="py-12 text-center text-gray-500">
                No pending borrow requests.
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Details Modal -->
    <div
      v-if="selectedRequest"
      id="detailsModal"
      class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50"
      @click.self="closeDetailsModal"
    >
      <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-hidden">
        <div class="p-6 border-b border-gray-200">
          <h3 class="text-xl font-bold kulim-park-bold">Borrow Request Details</h3>
        </div>
        <div class="p-6 overflow-y-auto max-h-[60vh]">
          <div v-if="requestDetails" class="space-y-3">
            <div>
              <strong>Name:</strong> {{ requestDetails.user?.full_name }}
            </div>
            <div>
              <strong>Email:</strong> {{ requestDetails.user?.email }}
            </div>
            <div>
              <strong>Role:</strong> {{ requestDetails.user?.role }}
            </div>
            <div>
              <strong>Verified:</strong>
              <span
                :class="[
                  'inline-flex items-center px-2 py-0.5 rounded text-xs font-medium ml-2',
                  requestDetails.user?.verified === 'Verified'
                    ? 'bg-green-100 text-green-800'
                    : 'bg-red-100 text-red-800'
                ]"
              >
                {{ requestDetails.user?.verified }}
              </span>
            </div>
            <div>
              <strong>Campus:</strong> {{ requestDetails.campus }}
            </div>
            <div>
              <strong>Request Date:</strong> {{ requestDetails.request?.created_at }}
            </div>
            <div>
              <strong>Resource:</strong> {{ requestDetails.resource?.Resource_Name }}
            </div>
          </div>
          <div v-else class="text-center py-4">
            Loading...
          </div>
        </div>
        <div class="p-6 border-t border-gray-200 flex justify-end gap-2">
          <button
            type="button"
            @click="closeDetailsModal"
            class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700"
          >
            Close
          </button>
          <button
            type="button"
            @click="rejectRequest"
            :disabled="isProcessing"
            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 disabled:opacity-50"
          >
            Reject
          </button>
          <button
            type="button"
            @click="approveRequest"
            :disabled="isProcessing"
            class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 disabled:opacity-50"
          >
            Approve
          </button>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { computed, ref } from 'vue';
import { router, usePage } from '@inertiajs/vue3';
import AppLayout from '../../Layouts/AppLayout.vue';

const props = defineProps({
  borrowRequests: {
    type: Array,
    required: true,
  },
});

const page = usePage();
const flashSuccess = computed(() => page.props.flash?.success ?? null);
const flashError = computed(() => page.props.flash?.error ?? null);

const searchTerm = ref('');
const selectedRequest = ref(null);
const requestDetails = ref(null);
const isProcessing = ref(false);

const filteredRequests = computed(() => {
  if (!searchTerm.value) {
    return props.borrowRequests;
  }
  const term = searchTerm.value.toLowerCase();
  return props.borrowRequests.filter(request => {
    const userName = request.user?.full_name?.toLowerCase() ?? '';
    const userEmail = request.user?.email?.toLowerCase() ?? '';
    const resourceName = request.resource?.Resource_Name?.toLowerCase() ?? '';
    return userName.includes(term) || userEmail.includes(term) || resourceName.includes(term);
  });
});

const formatDate = (date) => {
  if (!date) {
    return 'N/A';
  }
  return new Date(date).toLocaleString('en-US', {
    year: 'numeric',
    month: '2-digit',
    day: '2-digit',
    hour: '2-digit',
    minute: '2-digit',
  });
};

const openDetailsModal = async (request) => {
  selectedRequest.value = request;
  isProcessing.value = true;
  try {
    const response = await fetch(`/borrower/${request.Borrower_ID}/details`);
    const data = await response.json();
    requestDetails.value = data;
  } catch (error) {
    console.error('Error fetching details:', error);
  } finally {
    isProcessing.value = false;
  }
};

const closeDetailsModal = () => {
  selectedRequest.value = null;
  requestDetails.value = null;
};

const approveRequest = () => {
  if (!selectedRequest.value || !confirm('Are you sure you want to approve this request?')) {
    return;
  }
  isProcessing.value = true;
  router.post(`/borrow/approve/${selectedRequest.value.Borrower_ID}`, {}, {
    onFinish: () => {
      isProcessing.value = false;
      closeDetailsModal();
    },
  });
};

const rejectRequest = () => {
  if (!selectedRequest.value || !confirm('Are you sure you want to reject this request?')) {
    return;
  }
  isProcessing.value = true;
  router.post(`/borrow/reject/${selectedRequest.value.Borrower_ID}`, {}, {
    onFinish: () => {
      isProcessing.value = false;
      closeDetailsModal();
    },
  });
};
</script>

<style scoped>
/* Truncate text with fade effect */
.truncate {
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
  position: relative;
  max-width: 100%;
}

.truncate::after {
  content: '';
  position: absolute;
  right: 0;
  top: 0;
  bottom: 0;
  width: 40px;
  background: linear-gradient(to right, rgba(255, 255, 255, 0), rgba(255, 255, 255, 1));
  pointer-events: none;
}
</style>

<style scoped>
.kantumruy-pro-regular {
  font-family: 'Kantumruy Pro', sans-serif;
}
</style>

