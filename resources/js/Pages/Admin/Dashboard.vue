<template>
  <AdminLayout title="Dashboard">
    <div class="homediv lg:mx-[10%] flex flex-row flex-wrap gap-4 mb-6">
      <div
        v-for="card in statCards"
        :key="card.label"
        class="rounded-2xl bg-white border border-gray-200 shadow-lg p-6 hover:shadow-xl transition-shadow flex-1 min-w-[200px]"
      >
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm font-medium text-gray-600 kulim-park-regular mb-1">
              {{ card.label }}
            </p>
            <p class="text-3xl font-bold kulim-park-bold" :class="card.textClass">
              {{ card.value }}
            </p>
          </div>
          <div :class="['rounded-full p-3', card.badgeClass]">
            <i :class="['text-2xl', card.iconClass]" />
          </div>
        </div>
      </div>
    </div>

    <!-- Graphs Section -->
    <div class="homediv lg:mx-[10%] grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
      <div class="rounded-2xl bg-white border border-gray-200 shadow-lg p-6">
        <h3 class="text-lg font-bold kulim-park-bold mb-4">User Logins (Last 30 Days)</h3>
        <div class="h-64 flex items-end justify-between gap-1">
          <div
            v-for="(day, index) in loginChartData"
            :key="index"
            class="flex-1 flex flex-col items-center"
          >
            <div
              class="w-full rounded-t transition-all hover:opacity-90"
              :class="day.count > 0 ? 'bg-green-500 shadow-[0_0_10px_rgba(34,197,94,0.5)]' : 'bg-gray-200'"
              :style="{ height: `${(day.count / maxLoginCount) * 100}%` }"
              :title="`${day.date}: ${day.count} logins`"
            />
            <span class="text-xs text-gray-500 mt-1 transform -rotate-45 origin-top-left whitespace-nowrap">
              {{ formatChartDate(day.date) }}
            </span>
          </div>
        </div>
      </div>
      <div class="rounded-2xl bg-white border border-gray-200 shadow-lg p-6">
        <h3 class="text-lg font-bold kulim-park-bold mb-4">Resource Uploads (Last 30 Days)</h3>
        <div class="h-64 flex items-end justify-between gap-1">
          <div
            v-for="(day, index) in uploadChartData"
            :key="index"
            class="flex-1 flex flex-col items-center"
          >
            <div
              class="w-full rounded-t transition-all hover:opacity-90"
              :class="day.count > 0 ? 'bg-green-500 shadow-[0_0_10px_rgba(34,197,94,0.5)]' : 'bg-gray-200'"
              :style="{ height: `${(day.count / maxUploadCount) * 100}%` }"
              :title="`${day.date}: ${day.count} uploads`"
            />
            <span class="text-xs text-gray-500 mt-1 transform -rotate-45 origin-top-left whitespace-nowrap">
              {{ formatChartDate(day.date) }}
            </span>
          </div>
        </div>
      </div>
    </div>

    <div class="homediv lg:mx-[10%] rounded-2xl bg-white border border-gray-200 shadow-lg p-6">
      <h2 class="text-1xl sm:text-2xl lg:text-3xl font-extrabold kulim-park-bold tracking-tight mb-6">
        Pending User Approvals
      </h2>

      <div
        v-if="flashSuccess"
        class="bg-green-100 text-green-700 p-3 rounded mb-4 text-sm"
      >
        {{ flashSuccess }}
      </div>

      <p v-if="!pendingUsers.length" class="text-gray-600">
        No pending approvals.
      </p>

      <div v-else class="overflow-x-auto">
        <table class="w-full kantumruy-pro-regular tracking-tight bg-white shadow rounded border border-gray-200">
          <thead>
            <tr class="bg-gray-200">
              <th class="p-3 text-left">Name</th>
              <th class="p-3 text-left">Email</th>
              <th class="p-3 text-left">Role</th>
              <th class="p-3 text-left">Campus</th>
              <th class="p-3 text-left">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="user in pendingUsers" :key="user.id">
              <td class="p-3">
                {{ user.name ?? 'N/A' }}
              </td>
              <td class="p-3">
                {{ user.email }}
              </td>
              <td class="p-3">
                {{ user.role }}
              </td>
              <td class="p-3">
                {{ user.campus ?? 'N/A' }}
              </td>
              <td class="p-3">
                <button
                  type="button"
                  class="bg-green-700 text-white px-3 py-1 rounded hover:bg-green-800"
                  @click="approveUser(user.approveUrl)"
                  :disabled="isSubmitting"
                >
                  Approve
                </button>
                <button
                  type="button"
                  class="bg-red-700 text-white px-3 py-1 rounded hover:bg-red-800"
                  @click="rejectUser(user.rejectUrl)"
                  :disabled="isSubmitting"
                >
                  Reject
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { computed, ref } from 'vue';
import { router, usePage } from '@inertiajs/vue3';
import AdminLayout from '../../Layouts/AdminLayout.vue';

const props = defineProps({
  stats: {
    type: Object,
    required: true,
  },
  pendingUsers: {
    type: Array,
    required: true,
  },
});

const flashSuccess = computed(() => usePage().props.flash?.success ?? null);
const isSubmitting = ref(false);

const statCards = computed(() => [
  {
    label: 'Total Users',
    value: props.stats.totalUsers ?? 0,
    textClass: 'text-green-700',
    badgeClass: 'bg-green-100',
    iconClass: 'fas fa-users text-green-700',
  },
  {
    label: 'Pending Approvals',
    value: props.stats.pendingApprovals ?? 0,
    textClass: 'text-yellow-700',
    badgeClass: 'bg-yellow-100',
    iconClass: 'fas fa-clock text-yellow-700',
  },
  {
    label: 'Total Resources',
    value: props.stats.totalResources ?? 0,
    textClass: 'text-green-700',
    badgeClass: 'bg-green-100',
    iconClass: 'fas fa-book text-green-700',
  },
  {
    label: 'Active Borrows',
    value: props.stats.activeBorrows ?? 0,
    textClass: 'text-purple-700',
    badgeClass: 'bg-purple-100',
    iconClass: 'fas fa-hand-holding text-purple-700',
  },
  {
    label: 'Online Users',
    value: props.stats.onlineUsers ?? 0,
    textClass: 'text-blue-700',
    badgeClass: 'bg-blue-100',
    iconClass: 'fas fa-circle text-blue-700',
  },
]);

const loginChartData = computed(() => {
  return props.stats.recentLogins || [];
});

const uploadChartData = computed(() => {
  return props.stats.resourceUploads || [];
});

const maxLoginCount = computed(() => {
  if (!loginChartData.value.length) return 1;
  return Math.max(...loginChartData.value.map(d => d.count), 1);
});

const maxUploadCount = computed(() => {
  if (!uploadChartData.value.length) return 1;
  return Math.max(...uploadChartData.value.map(d => d.count), 1);
});

const formatChartDate = (dateString) => {
  if (!dateString) return '';
  const date = new Date(dateString);
  return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
};

const approveUser = (url) => {
  if (!url) return;
  isSubmitting.value = true;
  router.post(url, {}, {
    onFinish: () => {
      isSubmitting.value = false;
    },
  });
};

const rejectUser = (url) => {
  if (!url) return;
  isSubmitting.value = true;
  router.delete(url, {
    onFinish: () => {
      isSubmitting.value = false;
    },
  });
};
</script>

