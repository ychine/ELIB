<template>
  <AppLayout title="Community Uploads" content-padding-classes="px-[10%] lg:px-[10%]">
    <div class="rounded-2xl bg-white border border-gray-200 shadow-lg p-6">
      <p class="text-gray-600 mb-4">Community uploads are managed through Resource Management.</p>
      <Link
        href="/resource-management?type=Community Uploads"
        class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-[#22C55E] to-[#16A34A] text-white rounded-xl hover:from-[#16A34A] hover:to-[#15803D] transition-all font-medium text-sm shadow-lg hover:shadow-xl"
      >
        <i class="fas fa-arrow-right"></i>
        Go to Resource Management
      </Link>
    </div>
  </AppLayout>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import AppLayout from '../../Layouts/AppLayout.vue';
</script>

