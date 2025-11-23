<template>
  <Head title="Reports & Analytics" />
  <AdminLayout title="Reports & Analytics">
    <!-- Tabs -->
    <div class="homediv lg:mx-[10%] flex gap-2 mb-4 border-b border-gray-200">
      <button
        @click="activeTab = 'Analytics'"
        :class="[
          'px-4 py-2 font-medium transition-colors',
          activeTab === 'Analytics'
            ? 'border-b-2 border-green-600 text-green-600'
            : 'text-gray-600 hover:text-gray-800'
        ]"
      >
        Analytics
      </button>
      <button
        @click="activeTab = 'Reports'"
        :class="[
          'px-4 py-2 font-medium transition-colors',
          activeTab === 'Reports'
            ? 'border-b-2 border-green-600 text-green-600'
            : 'text-gray-600 hover:text-gray-800'
        ]"
      >
        Reports & Flagged Accounts
      </button>
    </div>

    <!-- Analytics Tab -->
    <div v-if="activeTab === 'Analytics'">
      <div class="homediv lg:mx-[10%] grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
        <div class="rounded-2xl bg-white border border-gray-200 shadow-lg p-6">
          <h3 class="text-lg font-semibold kulim-park-semibold text-gray-700 mb-2">
            Total Resources
          </h3>
          <p class="text-3xl font-bold text-green-700">
            {{ summary.totalResources }}
          </p>
        </div>
      </div>

      <!-- Line Graph: Community Uploads vs Featured -->
      <div class="homediv lg:mx-[10%] rounded-2xl bg-white border border-gray-200 shadow-lg p-6 mb-6">
        <h2 class="text-1xl sm:text-2xl lg:text-3xl font-extrabold kulim-park-bold tracking-tight mb-6">
          Resource Uploads Over Time (Last 30 Days)
        </h2>
        <div class="relative" style="height: 400px;">
          <svg class="w-full h-full" viewBox="0 0 800 400" preserveAspectRatio="xMidYMid meet">
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
              <line :x1="50" :y1="50 + (i-1) * 70" :x2="750" :y2="50 + (i-1) * 70" stroke="#e5e7eb" stroke-width="1" />
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
            <circle
              v-for="(point, index) in communityUploadsChartPoints"
              :key="'community-' + index"
              :cx="point.x"
              :cy="point.y"
              r="4"
              fill="#22c55e"
              stroke="white"
              stroke-width="2"
            />

            <!-- Data points for Featured -->
            <circle
              v-for="(point, index) in featuredChartPoints"
              :key="'featured-' + index"
              :cx="point.x"
              :cy="point.y"
              r="4"
              fill="#3b82f6"
              stroke="white"
              stroke-width="2"
            />

            <!-- X-axis labels (every 5th day) -->
            <g v-for="(day, index) in communityUploadsChart" :key="index">
              <text
                v-if="index % 5 === 0 || index === communityUploadsChart.length - 1"
                :x="50 + (index / (communityUploadsChart.length - 1)) * 700"
                y="390"
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
                :y="50 + (i-1) * 70 + 5"
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
        </div>
      </div>
    </div>

    <div class="homediv lg:mx-[10%] rounded-2xl bg-white border border-gray-200 shadow-lg p-6 mb-6">
      <h2 class="text-1xl sm:text-2xl lg:text-3xl font-extrabold kulim-park-bold tracking-tight mb-6">
        Resources by Type
      </h2>
      <p v-if="!resourcesByType.length" class="text-gray-600">No resources found.</p>
      <div v-else>
        <table class="w-full kantumruy-pro-regular tracking-tight bg-white shadow rounded border border-gray-200">
          <thead>
            <tr class="bg-gray-200">
              <th class="p-3 text-left">Type</th>
              <th class="p-3 text-left">Count</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="type in resourcesByType" :key="type.type">
              <td class="p-3">{{ type.type ?? 'Unknown' }}</td>
              <td class="p-3">{{ type.count }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <div class="homediv lg:mx-[10%] rounded-2xl bg-white border border-gray-200 shadow-lg p-6 mb-6">
      <h2 class="text-1xl sm:text-2xl lg:text-3xl font-extrabold kulim-park-bold tracking-tight mb-6">
        Top Viewed Resources
      </h2>
      <p v-if="!topViewed.length" class="text-gray-600">No resources found.</p>
      <div v-else>
        <table class="w-full kantumruy-pro-regular tracking-tight bg-white shadow rounded border border-gray-200">
          <thead>
            <tr class="bg-gray-200">
              <th class="p-3 text-left">Resource Name</th>
              <th class="p-3 text-left">Type</th>
              <th class="p-3 text-left">Views</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="resource in topViewed" :key="resource.id">
              <td class="p-3">{{ resource.name ?? 'N/A' }}</td>
              <td class="p-3">{{ resource.type ?? 'N/A' }}</td>
              <td class="p-3">{{ resource.views ?? 0 }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <div class="homediv lg:mx-[10%] rounded-2xl bg-white shadow-lg p-6">
      <h2 class="text-1xl sm:text-2xl lg:text-3xl font-extrabold kulim-park-bold tracking-tight mb-6">
        Recent Uploads
      </h2>
      <p v-if="!recentUploads.length" class="text-gray-600">No recent uploads.</p>
      <div v-else>
        <table class="w-full kantumruy-pro-regular tracking-tight bg-white shadow rounded border border-gray-200">
          <thead>
            <tr class="bg-gray-200">
              <th class="p-3 text-left">Resource Name</th>
              <th class="p-3 text-left">Type</th>
              <th class="p-3 text-left">Upload Date</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="resource in recentUploads" :key="resource.id">
              <td class="p-3">{{ resource.name ?? 'N/A' }}</td>
              <td class="p-3">{{ resource.type ?? 'N/A' }}</td>
              <td class="p-3">{{ resource.uploadedAt ?? 'N/A' }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Reports Tab -->
    <div v-if="activeTab === 'Reports'" class="homediv lg:mx-[10%] rounded-2xl bg-white border border-gray-200 shadow-lg p-6 mb-6">
      <h2 class="text-1xl sm:text-2xl lg:text-3xl font-extrabold kulim-park-bold tracking-tight mb-6">
        Resource Reports
      </h2>
      <p v-if="!reports || reports.length === 0" class="text-gray-600">No reports found.</p>
      <div v-else>
        <table class="w-full kantumruy-pro-regular tracking-tight bg-white shadow rounded border border-gray-200">
          <thead>
            <tr class="bg-gray-200">
              <th class="p-3 text-left">Resource</th>
              <th class="p-3 text-left">Reported By</th>
              <th class="p-3 text-left">Reason</th>
              <th class="p-3 text-left">Description</th>
              <th class="p-3 text-left">Date</th>
              <th class="p-3 text-left">Status</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="report in reports" :key="report.id">
              <td class="p-3">{{ report.resource_name ?? 'N/A' }}</td>
              <td class="p-3">
                <div>
                  <div class="font-medium">{{ report.reporter_name ?? 'N/A' }}</div>
                  <div class="text-xs text-gray-500">{{ report.reporter_email ?? 'N/A' }}</div>
                </div>
              </td>
              <td class="p-3">
                <span class="px-2 py-1 text-xs bg-red-100 text-red-800 rounded-full">
                  {{ report.reason ?? 'N/A' }}
                </span>
              </td>
              <td class="p-3 max-w-xs truncate" :title="report.description">
                {{ report.description ?? 'N/A' }}
              </td>
              <td class="p-3">{{ formatDate(report.created_at) }}</td>
              <td class="p-3">
                <span
                  :class="{
                    'px-2 py-1 text-xs rounded-full': true,
                    'bg-yellow-100 text-yellow-800': report.status === 'pending',
                    'bg-green-100 text-green-800': report.status === 'reviewed',
                    'bg-red-100 text-red-800': report.status === 'resolved',
                  }"
                >
                  {{ report.status ?? 'pending' }}
                </span>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Flagged Accounts Tab -->
    <div v-if="activeTab === 'Reports'" class="homediv lg:mx-[10%] rounded-2xl bg-white border border-gray-200 shadow-lg p-6">
      <h2 class="text-1xl sm:text-2xl lg:text-3xl font-extrabold kulim-park-bold tracking-tight mb-6">
        Flagged Accounts
      </h2>
      <p v-if="!flaggedAccounts || flaggedAccounts.length === 0" class="text-gray-600">No flagged accounts.</p>
      <div v-else>
        <table class="w-full kantumruy-pro-regular tracking-tight bg-white shadow rounded border border-gray-200">
          <thead>
            <tr class="bg-gray-200">
              <th class="p-3 text-left">Name</th>
              <th class="p-3 text-left">Email</th>
              <th class="p-3 text-left">Role</th>
              <th class="p-3 text-left">Flagged Reason</th>
              <th class="p-3 text-left">Date Flagged</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="account in flaggedAccounts" :key="account.id">
              <td class="p-3">{{ account.name ?? 'N/A' }}</td>
              <td class="p-3">{{ account.email ?? 'N/A' }}</td>
              <td class="p-3">{{ account.role ?? 'N/A' }}</td>
              <td class="p-3">{{ account.flag_reason ?? 'Community Standards Violation' }}</td>
              <td class="p-3">{{ formatDate(account.flagged_at) }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import AdminLayout from '../../Layouts/AdminLayout.vue';
import { Head } from '@inertiajs/vue3';

const activeTab = ref('Analytics');

const props = defineProps({
  summary: {
    type: Object,
    required: true,
  },
  resourcesByType: {
    type: Array,
    default: () => [],
  },
  topViewed: {
    type: Array,
    default: () => [],
  },
  recentUploads: {
    type: Array,
    default: () => [],
  },
  reports: {
    type: Array,
    default: () => [],
  },
  flaggedAccounts: {
    type: Array,
    default: () => [],
  },
  communityUploadsChart: {
    type: Array,
    default: () => [],
  },
  featuredChart: {
    type: Array,
    default: () => [],
  },
});

const formatDate = (date) => {
  if (!date) return 'N/A';
  return new Date(date).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' });
};

const formatChartDateShort = (dateString) => {
  if (!dateString) return '';
  const date = new Date(dateString);
  const day = date.getDate();
  const month = date.toLocaleDateString('en-US', { month: 'short' }).substring(0, 3);
  return `${month} ${day}`;
};

// Calculate max value for chart scaling
const maxChartValue = computed(() => {
  const allValues = [
    ...props.communityUploadsChart.map(d => d.count),
    ...props.featuredChart.map(d => d.count),
  ];
  const max = Math.max(...allValues, 1);
  return Math.ceil(max * 1.1); // Add 10% padding
});

// Generate SVG path for Community Uploads line
const communityUploadsPoints = computed(() => {
  if (!props.communityUploadsChart.length) return '';
  const points = props.communityUploadsChart.map((day, index) => {
    const x = 50 + (index / (props.communityUploadsChart.length - 1)) * 700;
    const y = 350 - (day.count / maxChartValue.value) * 300;
    return `${x},${y}`;
  });
  return points.join(' ');
});

const communityUploadsPath = computed(() => {
  if (!props.communityUploadsChart.length) return '';
  const points = communityUploadsPoints.value;
  const firstPoint = props.communityUploadsChart[0];
  const lastPoint = props.communityUploadsChart[props.communityUploadsChart.length - 1];
  const firstX = 50;
  const lastX = 750;
  const baseY = 350;
  return `M ${firstX},${baseY} L ${points.split(' ')[0]} ${points} L ${lastX},${baseY} Z`;
});

// Generate SVG path for Featured line
const featuredPoints = computed(() => {
  if (!props.featuredChart.length) return '';
  const points = props.featuredChart.map((day, index) => {
    const x = 50 + (index / (props.featuredChart.length - 1)) * 700;
    const y = 350 - (day.count / maxChartValue.value) * 300;
    return `${x},${y}`;
  });
  return points.join(' ');
});

const featuredPath = computed(() => {
  if (!props.featuredChart.length) return '';
  const points = featuredPoints.value;
  const firstPoint = props.featuredChart[0];
  const lastPoint = props.featuredChart[props.featuredChart.length - 1];
  const firstX = 50;
  const lastX = 750;
  const baseY = 350;
  return `M ${firstX},${baseY} L ${points.split(' ')[0]} ${points} L ${lastX},${baseY} Z`;
});

// Chart points for circles
const communityUploadsChartPoints = computed(() => {
  return props.communityUploadsChart.map((day, index) => ({
    x: 50 + (index / (props.communityUploadsChart.length - 1)) * 700,
    y: 350 - (day.count / maxChartValue.value) * 300,
  }));
});

const featuredChartPoints = computed(() => {
  return props.featuredChart.map((day, index) => ({
    x: 50 + (index / (props.featuredChart.length - 1)) * 700,
    y: 350 - (day.count / maxChartValue.value) * 300,
  }));
});
</script>

