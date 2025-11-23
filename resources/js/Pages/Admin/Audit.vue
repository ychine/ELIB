<template>
  <Head title="Audit Trail" />
  <AdminLayout title="">
    <div class="homediv lg:mx-[10%] rounded-2xl bg-white border border-gray-200 shadow-lg p-6">
      <h2 class="text-2xl font-bold kulim-park-bold mb-6">Audit Trail</h2>
      
      <p v-if="!auditLogs || !auditLogs.data || (Array.isArray(auditLogs.data) && auditLogs.data.length === 0)" class="text-gray-500 mt-4">
        No audit logs available yet.
      </p>

      <div v-else-if="auditLogs && auditLogs.data && auditLogs.data.length > 0">
        <table class="w-full kantumruy-pro-regular tracking-tight bg-white shadow rounded border border-gray-200">
          <thead>
            <tr class="bg-gray-200">
              <th class="p-3 text-left">Date</th>
              <th class="p-3 text-left">User</th>
              <th class="p-3 text-left">Action</th>
              <th class="p-3 text-left">Description</th>
              <th class="p-3 text-left">IP Address</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="log in auditLogs.data" :key="log.id" class="border-b border-gray-300 hover:bg-gray-50">
              <td class="p-3">{{ log.date ?? 'N/A' }}</td>
              <td class="p-3">{{ log.user ?? 'N/A' }}</td>
              <td class="p-3">
                <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">
                  {{ log.action ?? 'N/A' }}
                </span>
              </td>
              <td class="p-3">{{ log.description ?? 'N/A' }}</td>
              <td class="p-3 text-xs text-gray-500">{{ log.ip_address ?? 'N/A' }}</td>
            </tr>
          </tbody>
        </table>

        <!-- Pagination -->
        <div v-if="auditLogs.links && auditLogs.links.length > 3" class="mt-4 flex justify-center gap-2">
          <template v-for="link in auditLogs.links" :key="link.label">
            <Link
              v-if="link.url"
              :href="link.url"
              :class="[
                'px-4 py-2 rounded',
                link.active ? 'bg-green-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300'
              ]"
              v-html="link.label"
            />
            <span
              v-else
              :class="[
                'px-4 py-2 rounded opacity-50 cursor-not-allowed',
                link.active ? 'bg-green-600 text-white' : 'bg-gray-200 text-gray-700'
              ]"
              v-html="link.label"
            />
          </template>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { Link, Head} from '@inertiajs/vue3';
import AdminLayout from '../../Layouts/AdminLayout.vue';

const props = defineProps({
  auditLogs: {
    type: Object,
    default: () => ({ data: [], links: [] }),
  },
});
</script>

