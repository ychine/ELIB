<template>
  <Head title="Dashboard" />
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

    <div class="homediv lg:mx-[10%] rounded-2xl bg-white border border-gray-200 shadow-lg p-6 mb-6">
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

    <!-- Graphs Section -->
    <div class="homediv lg:mx-[10%] grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
      <div class="rounded-2xl bg-white border border-gray-200 shadow-lg p-6">
        <h3 class="text-lg font-bold kulim-park-bold mb-4">User Logins (Last 30 Days)</h3>
        <div class="relative" style="height: 300px;">
          <div class="absolute inset-0 flex items-end justify-between gap-0.5 pb-8">
            <div
              v-for="(day, index) in loginChartData"
              :key="index"
              class="flex-1 flex flex-col items-center justify-end h-full"
              style="min-width: 0;"
            >
              <div
                class="w-full rounded-t transition-all hover:opacity-90 cursor-pointer"
                :class="day.count > 0 ? 'bg-green-500 shadow-[0_0_10px_rgba(34,197,94,0.5)]' : 'bg-gray-200'"
                :style="{ 
                  height: maxLoginCount > 0 
                    ? `${Math.max((day.count / maxLoginCount) * 100, day.count > 0 ? 3 : 1)}%` 
                    : '1%',
                  minHeight: day.count > 0 ? '4px' : '1px'
                }"
                @mouseenter="showLoginTooltip($event, day)"
                @mouseleave="hideLoginTooltip"
              />
            </div>
          </div>
          <div class="absolute bottom-0 left-0 right-0 flex justify-between gap-0.5 overflow-x-auto">
            <span
              v-for="(day, index) in loginChartData"
              :key="index"
              class="text-[9px] text-gray-500 flex-shrink-0"
              style="writing-mode: vertical-rl; text-orientation: mixed; transform: rotate(180deg);"
            >
              {{ formatChartDateShort(day.date) }}
            </span>
          </div>
          
          <!-- Login Tooltip -->
          <div
            v-if="loginTooltip.visible"
            class="absolute bg-gray-900 text-white text-xs rounded py-2 px-3 shadow-lg pointer-events-none z-10"
            :style="{
              left: loginTooltip.x + 'px',
              top: loginTooltip.y + 'px',
              transform: 'translate(-50%, -100%)',
              marginTop: '-10px'
            }"
          >
            <div class="font-semibold">{{ loginTooltip.date }}</div>
            <div>{{ loginTooltip.count }} login{{ loginTooltip.count !== 1 ? 's' : '' }}</div>
          </div>
        </div>
      </div>
      <div class="rounded-2xl bg-white border border-gray-200 shadow-lg p-6">
        <h3 class="text-lg font-bold kulim-park-bold mb-4">Resource Uploads (Last 30 Days)</h3>
        <div class="relative" style="height: 300px;">
          <svg class="w-full h-full" viewBox="0 0 800 300" preserveAspectRatio="xMidYMid meet">
            <!-- Grid lines -->
            <defs>
              <linearGradient id="communityGradient" x1="0%" y1="0%" x2="0%" y2="100%">
                <stop offset="0%" style="stop-color:#22c55e;stop-opacity:0.3" />
                <stop offset="100%" style="stop-color:#22c55e;stop-opacity:0" />
              </linearGradient>
              <linearGradient id="featuredGradient" x1="0%" y1="0%" x2="0%" y2="100%">
                <stop offset="0%" style="stop-color:#3b82f6;stop-opacity:0.3" />
                <stop offset="100%" style="stop-color:#3b82f6;stop-opacity:0" />
              </linearGradient>
            </defs>
            
            <!-- Y-axis grid lines -->
            <g v-for="i in 5" :key="i" class="text-gray-300">
              <line :x1="50" :y1="50 + (i-1) * 50" :x2="750" :y2="50 + (i-1) * 50" stroke="#e5e7eb" stroke-width="1" />
            </g>

            <!-- Community Uploads area -->
            <path
              :d="communityUploadsPath"
              fill="url(#communityGradient)"
              opacity="0.5"
            />
            
            <!-- Featured area -->
            <path
              :d="featuredPath"
              fill="url(#featuredGradient)"
              opacity="0.5"
            />

            <!-- Community Uploads line -->
            <polyline
              :points="communityUploadsPoints"
              fill="none"
              stroke="#22c55e"
              stroke-width="3"
              stroke-linecap="round"
              stroke-linejoin="round"
            />

            <!-- Featured line -->
            <polyline
              :points="featuredPoints"
              fill="none"
              stroke="#3b82f6"
              stroke-width="3"
              stroke-linecap="round"
              stroke-linejoin="round"
            />

            <!-- Data points for Community Uploads -->
            <g v-for="(point, index) in communityUploadsChartPoints" :key="'community-' + index">
              <circle
                :cx="point.x"
                :cy="point.y"
                r="12"
                fill="transparent"
                class="cursor-pointer"
                @mouseenter="showUploadTooltip($event, communityUploadsChart[index], 'Community Uploads')"
                @mouseleave="hideUploadTooltip"
              />
              <circle
                :cx="point.x"
                :cy="point.y"
                r="5"
                fill="#22c55e"
                stroke="white"
                stroke-width="2"
                class="pointer-events-none"
              />
            </g>

            <!-- Data points for Featured -->
            <g v-for="(point, index) in featuredChartPoints" :key="'featured-' + index">
              <circle
                :cx="point.x"
                :cy="point.y"
                r="12"
                fill="transparent"
                class="cursor-pointer"
                @mouseenter="showUploadTooltip($event, featuredChart[index], 'Featured')"
                @mouseleave="hideUploadTooltip"
              />
              <circle
                :cx="point.x"
                :cy="point.y"
                r="5"
                fill="#3b82f6"
                stroke="white"
                stroke-width="2"
                class="pointer-events-none"
              />
            </g>

            <!-- X-axis labels (every 5th day) -->
            <g v-for="(day, index) in communityUploadsChart" :key="index">
              <text
                v-if="index % 5 === 0 || index === communityUploadsChart.length - 1"
                :x="50 + (index / (communityUploadsChart.length - 1)) * 700"
                y="290"
                class="text-xs fill-gray-600"
                text-anchor="middle"
              >
                {{ formatChartDateShort(day.date) }}
              </text>
            </g>

            <!-- Y-axis labels -->
            <g v-for="i in 5" :key="i">
              <text
                x="45"
                :y="50 + (i-1) * 50 + 5"
                class="text-xs fill-gray-600"
                text-anchor="end"
              >
                {{ Math.round(maxChartValue - (maxChartValue / 4) * (i-1)) }}
              </text>
            </g>
          </svg>

          <!-- Legend -->
          <div class="absolute top-4 right-4 flex gap-4">
            <div class="flex items-center gap-2">
              <div class="w-4 h-4 bg-green-500 rounded"></div>
              <span class="text-sm text-gray-700">Community Uploads</span>
            </div>
            <div class="flex items-center gap-2">
              <div class="w-4 h-4 bg-blue-500 rounded"></div>
              <span class="text-sm text-gray-700">Featured</span>
            </div>
          </div>

          <!-- Upload Tooltip -->
          <div
            v-if="uploadTooltip.visible"
            class="absolute bg-gray-900 text-white text-xs rounded py-2 px-3 shadow-lg pointer-events-none z-10"
            :style="{
              left: uploadTooltip.x + 'px',
              top: uploadTooltip.y + 'px',
              transform: 'translate(-50%, -100%)',
              marginTop: '-10px'
            }"
          >
            <div class="font-semibold">{{ uploadTooltip.date }}</div>
            <div>{{ uploadTooltip.type }}: {{ uploadTooltip.count }} upload{{ uploadTooltip.count !== 1 ? 's' : '' }}</div>
          </div>
        </div>
      </div>
    </div>

    
  </AdminLayout>
</template>

<script setup>
import { computed, ref } from 'vue';
import { router, usePage, Head } from '@inertiajs/vue3';
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

const communityUploadsChart = computed(() => {
  return props.stats.communityUploadsChart || [];
});

const featuredChart = computed(() => {
  return props.stats.featuredChart || [];
});

const maxLoginCount = computed(() => {
  if (!loginChartData.value.length) return 1;
  return Math.max(...loginChartData.value.map(d => d.count), 1);
});

// Calculate max value for chart scaling (both lines)
const maxChartValue = computed(() => {
  const allValues = [
    ...communityUploadsChart.value.map(d => d.count),
    ...featuredChart.value.map(d => d.count),
  ];
  const max = Math.max(...allValues, 1);
  return Math.ceil(max * 1.1); // Add 10% padding
});

// Generate SVG path for Community Uploads line
const communityUploadsPoints = computed(() => {
  if (!communityUploadsChart.value.length) return '';
  const points = communityUploadsChart.value.map((day, index) => {
    const x = 50 + (index / (communityUploadsChart.value.length - 1)) * 700;
    const y = 250 - (day.count / maxChartValue.value) * 200;
    return `${x},${y}`;
  });
  return points.join(' ');
});

const communityUploadsPath = computed(() => {
  if (!communityUploadsChart.value.length) return '';
  const points = communityUploadsPoints.value;
  const firstX = 50;
  const lastX = 750;
  const baseY = 250;
  return `M ${firstX},${baseY} L ${points.split(' ')[0]} ${points} L ${lastX},${baseY} Z`;
});

// Generate SVG path for Featured line
const featuredPoints = computed(() => {
  if (!featuredChart.value.length) return '';
  const points = featuredChart.value.map((day, index) => {
    const x = 50 + (index / (featuredChart.value.length - 1)) * 700;
    const y = 250 - (day.count / maxChartValue.value) * 200;
    return `${x},${y}`;
  });
  return points.join(' ');
});

const featuredPath = computed(() => {
  if (!featuredChart.value.length) return '';
  const points = featuredPoints.value;
  const firstX = 50;
  const lastX = 750;
  const baseY = 250;
  return `M ${firstX},${baseY} L ${points.split(' ')[0]} ${points} L ${lastX},${baseY} Z`;
});

// Chart points for circles
const communityUploadsChartPoints = computed(() => {
  return communityUploadsChart.value.map((day, index) => ({
    x: 50 + (index / (communityUploadsChart.value.length - 1)) * 700,
    y: 250 - (day.count / maxChartValue.value) * 200,
  }));
});

const featuredChartPoints = computed(() => {
  return featuredChart.value.map((day, index) => ({
    x: 50 + (index / (featuredChart.value.length - 1)) * 700,
    y: 250 - (day.count / maxChartValue.value) * 200,
  }));
});

// Login tooltip state
const loginTooltip = ref({
  visible: false,
  x: 0,
  y: 0,
  date: '',
  count: 0,
});

const showLoginTooltip = (event, day) => {
  const container = event.target.closest('.relative');
  const rect = container.getBoundingClientRect();
  const x = event.clientX - rect.left;
  const y = event.clientY - rect.top;
  
  loginTooltip.value = {
    visible: true,
    x: x,
    y: y - 10,
    date: formatChartDate(day.date),
    count: day.count,
  };
};

const hideLoginTooltip = () => {
  loginTooltip.value.visible = false;
};

// Upload tooltip state
const uploadTooltip = ref({
  visible: false,
  x: 0,
  y: 0,
  date: '',
  count: 0,
  type: '',
});

const showUploadTooltip = (event, day, type) => {
  const svgElement = event.target.closest('svg');
  const container = svgElement.parentElement;
  const rect = container.getBoundingClientRect();
  const x = event.clientX - rect.left;
  const y = event.clientY - rect.top;
  
  uploadTooltip.value = {
    visible: true,
    x: x,
    y: y - 10,
    date: formatChartDate(day.date),
    count: day.count,
    type: type,
  };
};

const hideUploadTooltip = () => {
  uploadTooltip.value.visible = false;
};

const formatChartDate = (dateString) => {
  if (!dateString) return '';
  const date = new Date(dateString);
  return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
};

const formatChartDateShort = (dateString) => {
  if (!dateString) return '';
  const date = new Date(dateString);
  const day = date.getDate();
  const month = date.toLocaleDateString('en-US', { month: 'short' }).substring(0, 3);
  return `${month} ${day}`;
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

