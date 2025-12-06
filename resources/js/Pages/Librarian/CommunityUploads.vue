<template>
  <Head title="Community Uploads"/>
  <AppLayout title="Community Uploads" content-padding-classes="px-[10%] lg:px-[10%]">
    <div class="rounded-2xl bg-white border border-gray-200 shadow-lg p-6">
      <div class="flex justify-end items-center mb-4">
        <Link
          v-if="userRole === 'librarian'"
          href="/resource-management?type=Community Uploads"
          class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-[#22C55E] to-[#16A34A] text-white rounded-lg hover:from-[#16A34A] hover:to-[#15803D] transition-all font-medium text-sm shadow-lg hover:shadow-xl"
        >
          <i class="fas fa-cog"></i>
          Manage Uploads
        </Link>
      </div>

      <p v-if="!communityUploads || communityUploads.length === 0" class="text-gray-600 text-center py-8">
        No approved community uploads yet.
      </p>

      <div v-else class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4">
        <div
          v-for="resource in communityUploads"
          :key="resource.Resource_ID"
          class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow cursor-pointer"
          @click="viewResource(resource.Resource_ID)"
        >
          <div class="w-full h-48 bg-gray-200 overflow-hidden">
            <img
              v-if="resource.thumbnail_path"
              :src="`/storage/${resource.thumbnail_path}`"
              :alt="resource.Resource_Name"
              class="w-full h-full object-cover"
            >
            <div
              v-else
              class="w-full h-full flex items-center justify-center text-2xl font-bold text-gray-400"
            >
              {{ resource.Resource_Name?.substring(0, 2).toUpperCase() || 'NA' }}
            </div>
          </div>
          <div class="p-3">
            <h3 class="font-bold text-sm mb-1 line-clamp-2">{{ resource.Resource_Name }}</h3>
            <p class="text-xs text-gray-600 mb-2">by {{ resource.authors || 'Unknown' }}</p>
            
            <!-- Uploader Info -->
            <div class="flex items-center gap-2 mb-2">
              <div class="w-6 h-6 rounded-full bg-gray-300 overflow-hidden flex-shrink-0">
                <img
                  v-if="resource.uploader?.profile_picture"
                  :src="`/storage/${resource.uploader.profile_picture}`"
                  :alt="resource.uploader.name"
                  class="w-full h-full object-cover"
                >
                <div
                  v-else
                  class="w-full h-full flex items-center justify-center text-xs font-semibold text-gray-600"
                >
                  {{ resource.uploader?.name?.substring(0, 1).toUpperCase() || 'U' }}
                </div>
              </div>
              <div class="flex-1 min-w-0">
                <p class="text-xs font-medium text-gray-900 truncate">{{ resource.uploader?.name || 'Unknown' }}</p>
                <p class="text-xs text-gray-500 truncate">
                  <span v-if="resource.uploader?.campus">{{ resource.uploader.campus }}</span>
                  <span v-if="resource.uploader?.course && resource.uploader?.role === 'student'">
                    <span v-if="resource.uploader?.campus"> • </span>{{ resource.uploader.course }}
                  </span>
                  <span v-else-if="resource.uploader?.role === 'faculty'">
                    <span v-if="resource.uploader?.campus"> • </span>Faculty
                  </span>
                </p>
              </div>
            </div>

            <div class="flex items-center justify-between text-xs text-gray-500">
              <span>{{ resource.views || 0 }} views</span>
              <span class="flex items-center gap-1">
                <i class="fas fa-star text-yellow-500"></i>
                {{ resource.average_rating || '0.0' }}
              </span>
            </div>
            <div v-if="userRole === 'librarian'" class="mt-2 flex gap-2" @click.stop>
              <button
                @click="deleteResource(resource.Resource_ID)"
                class="flex-1 px-2 py-1 bg-red-600 text-white text-xs rounded hover:bg-red-700"
              >
                Delete
              </button>
              <button
                @click="flagResource(resource.Resource_ID)"
                class="flex-1 px-2 py-1 bg-orange-600 text-white text-xs rounded hover:bg-orange-700"
              >
                Flag
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { computed } from 'vue';
import { Link, router, usePage, Head } from '@inertiajs/vue3';
import AppLayout from '../../Layouts/AppLayout.vue';

const props = defineProps({
  communityUploads: {
    type: Array,
    default: () => [],
  },
});

const page = usePage();
const userRole = computed(() => page.props.auth?.user?.role || null);

const viewResource = (resourceId) => {
  router.visit(`/resources/${resourceId}/view`);
};

const deleteResource = (resourceId) => {
  if (!confirm('Are you sure you want to delete this community upload?')) {
    return;
  }

  router.delete(`/resources/${resourceId}`, {
    onSuccess: () => {
      router.reload();
    },
  });
};

const flagResource = (resourceId) => {
  if (!confirm('Are you sure you want to flag this resource and its owner?')) {
    return;
  }

  // TODO: Implement resource flagging logic
  alert('Resource flagging feature to be implemented');
};
</script>

