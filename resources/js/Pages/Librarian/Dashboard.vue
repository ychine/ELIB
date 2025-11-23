<template>
  <Head title="Dashboard" />
  <AppLayout title="Dashboard" content-padding-classes="px-[10%] lg:px-[10%]">
    <!-- Stats Cards -->
    <div class="grid sm:grid-cols-1 lg:grid-cols-3 grid-cols-3 gap-6 mb-6">
      <div class="rounded-2xl bg-white border border-gray-200 shadow-lg p-6">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm font-medium text-gray-600 kulim-park-regular mb-1">
              Pending Borrows
            </p>
            <p class="text-3xl font-bold kulim-park-bold text-yellow-700">
              {{ stats.pendingBorrows ?? 0 }}
            </p>
          </div>
          <div class="rounded-full p-3 bg-yellow-100">
            <i class="fas fa-clock text-2xl text-yellow-700" />
          </div>
        </div>
      </div>
      <div class="rounded-2xl bg-white border border-gray-200 shadow-lg p-6">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm font-medium text-gray-600 kulim-park-regular mb-1">
              Active Borrows
            </p>
            <p class="text-3xl font-bold kulim-park-bold text-green-700">
              {{ stats.activeBorrows ?? 0 }}
            </p>
          </div>
          <div class="rounded-full p-3 bg-green-100">
            <i class="fas fa-hand-holding text-2xl text-green-700" />
          </div>
        </div>
      </div>
      <div class="rounded-2xl bg-white border border-gray-200 shadow-lg p-6">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm font-medium text-gray-600 kulim-park-regular mb-1">
              Total Resources
            </p>
            <p class="text-3xl font-bold kulim-park-bold text-blue-700">
              {{ stats.totalResources ?? 0 }}
            </p>
          </div>
          <div class="rounded-full p-3 bg-blue-100">
            <i class="fas fa-book text-2xl text-blue-700" />
          </div>
        </div>
      </div>
    </div>

    <div class="rounded-2xl bg-white border border-gray-200 shadow-lg p-6">
      <p class="text-gray-600">Welcome to your librarian dashboard.</p>
    </div>
  </AppLayout>
</template>

<script setup>
import AppLayout from '../../Layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';

const props = defineProps({
  stats: {
    type: Object,
    default: () => ({
      pendingBorrows: 0,
      activeBorrows: 0,
      totalResources: 0,
    }),
  },
});
</script>

