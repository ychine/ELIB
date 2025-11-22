<template>
  <AdminLayout title="Resource Analytics">
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
  </AdminLayout>
</template>

<script setup>
import AdminLayout from '../../Layouts/AdminLayout.vue';

defineProps({
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
});
</script>

