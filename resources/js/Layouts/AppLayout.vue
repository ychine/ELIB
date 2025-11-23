<template>
  <div class="w-full min-h-screen flex">
    <UniversalSidebar
      v-if="sidebar"
      :menu-items="sidebar.menuItems ?? []"
      :active-route="sidebar.activeRoute ?? ''"
      :user="sidebarUser"
      :logos="sidebar.logos ?? {}"
      :role="sidebar.role ?? ''"
      :logout-url="logoutUrl"
      :default-expanded="false"
      :csrf-token="csrfToken"
      :courses="sidebar.courses ?? []"
    />

    <div class="flex bg-white flex-col flex-1 transition-all duration-300 main-content" style="overflow-x: visible;">
      <div class="fixed w-full top-0 left-0 flex justify-between items-center px-4 py-2 z-10 glass-nav"
        :class="{ scrolled: isNavScrolled }">
        <span class="text-5xl jersey-20-regular pl-3 text-white"></span>
        <div class="relative flex items-center">
          <input
            v-model="globalSearchQuery"
            class="searchbar pl-7 pr-10 sm:w-[545px] h-11 rounded-[34px] shadow-[inset_0px_4px_4px_rgba(0,0,0,0.25)]"
            type="text"
            placeholder="Search for books, papers.."
            @input="handleGlobalSearch"
            @focus="showGlobalSearchResults = true"
            @blur="handleGlobalSearchBlur"
          >
          <img
            :src="searchIcon"
            alt="Search icon"
            class="absolute right-5 w-6 h-6 pointer-events-none"
          >
          
          <!-- Global Search Results Dropdown -->
          <div
            v-if="showGlobalSearchResults && globalSearchResults.length > 0"
            class="absolute top-full left-0 mt-2 w-full sm:w-[545px] bg-white rounded-lg shadow-xl border border-gray-200 max-h-96 overflow-y-auto z-50"
            @mousedown.prevent
          >
            <div
              v-for="result in globalSearchResults"
              :key="result.Resource_ID"
              class="p-4 hover:bg-gray-50 cursor-pointer border-b border-gray-100 last:border-b-0"
              @click="viewResource(result)"
            >
              <div class="flex gap-3">
                <div class="flex-shrink-0 w-16 h-20 bg-gray-200 rounded flex items-center justify-center overflow-hidden">
                  <img
                    v-if="result.thumbnail_path"
                    :src="`/storage/${result.thumbnail_path}`"
                    :alt="result.Resource_Name"
                    class="w-full h-full object-cover"
                  >
                  <span v-else class="text-gray-500 text-xs font-bold">
                    {{ result.Resource_Name.substring(0, 2).toUpperCase() }}
                  </span>
                </div>
                <div class="flex-1 min-w-0">
                  <h3 class="font-semibold text-sm truncate">{{ result.Resource_Name }}</h3>
                  <p class="text-xs text-gray-600 mt-1">{{ formatAuthors(result.authors) }}</p>
                  <div class="flex items-center gap-2 mt-1">
                    <span class="text-yellow-500 text-xs">★</span>
                    <span class="text-xs text-gray-600">{{ result.average_rating }}</span>
                    <span class="text-xs text-gray-400">•</span>
                    <span class="text-xs text-gray-600">{{ result.views }} views</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div
            v-if="showGlobalSearchResults && globalSearchQuery && globalSearchResults.length === 0 && !isGlobalSearching"
            class="absolute top-full left-0 mt-2 w-full sm:w-[545px] bg-white rounded-lg shadow-xl border border-gray-200 p-4 z-50"
          >
            <p class="text-gray-500 text-sm">No results found</p>
          </div>
        </div>
        <div class="text-md flex space-x-4 gap-5 pr-6 plus-jakarta-sans-semibold text-white" />
      </div>

      <div class="hero-container relative w-full greenhue z-1" style="overflow: visible;">
        <img
          :src="sealLogo"
          alt="ISU Logo"
          class="absolute right-0 w-15 h-15 m-7"
        >
        <h5 class="absolute text-white right-0 m-7 mr-10 translate-y-30 kulim-park-semibold">
          One ISU
        </h5>
        <img
          :src="libraryImage"
          alt="Library"
          class="w-full h-50 z-[-1] object-cover absolute"
          style="object-position: 70% middle;"
        >
        <div class="herotext h-50 ml-30 flex relative z-2">
          <div class="column">
            <h1 style="transform: translateY(35%); line-height: 86.402%; font-family: 'Kulim Park', sans-serif; font-weight: 600; letter-spacing: -1.3px; font-size: 45px; text-shadow: 0 4px 4px #000; color: #FFF;">
              Bridging knowledge <br>
              from one campus <br>
              to another
            </h1>
          </div>
        </div>
        <div class="homediv lg:mx-[10%] mt-5 rounded-md bg-white">
          <h2 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold kulim-park-bold tracking-tight mb-4">
            {{ title }}
          </h2>
        </div>

        <div :class="contentPaddingClasses">
          <slot />
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed, onMounted, onUnmounted, ref } from 'vue';
import { usePage, router } from '@inertiajs/vue3';
import UniversalSidebar from '../components/ui/UniversalSidebar.vue';

const props = defineProps({
  title: {
    type: String,
    required: true,
  },
  contentPaddingClasses: {
    type: String,
    default: '',
  },
});

const page = usePage();
const sidebar = computed(() => page.props.sidebar ?? null);
const auth = computed(() => page.props.auth ?? {});
const images = computed(() => page.props.images ?? {});
const sidebarUser = computed(() => {
  if (auth.value.user) {
    return {
      ...auth.value.user, // Include all user data including role, first_name, last_name, student_number, course_id
      name: auth.value.user.name ?? 'User',
      email: auth.value.user.email ?? '',
      campus: auth.value.user.campus ?? null,
      profile_picture: auth.value.user.profile_picture ?? null,
      is_online: auth.value.user.is_online ?? false,
    };
  }
  return null;
});
const logoutUrl = computed(() => sidebar.value?.logoutUrl ?? auth.value.logoutUrl ?? '/logout');
const csrfToken = document.head.querySelector('meta[name="csrf-token"]')?.content ?? '';

const isNavScrolled = ref(false);
const sealLogo = computed(() => images.value.sealLogo ?? '');
const libraryImage = computed(() => images.value.libraryImage ?? '');
const searchIcon = computed(() => images.value.searchIcon ?? '');

// Global search functionality
const globalSearchQuery = ref('');
const globalSearchResults = ref([]);
const showGlobalSearchResults = ref(false);
const isGlobalSearching = ref(false);
let globalSearchTimeout = null;

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

const handleGlobalSearch = () => {
  if (globalSearchTimeout) {
    clearTimeout(globalSearchTimeout);
  }
  
  if (globalSearchQuery.value.length < 2) {
    globalSearchResults.value = [];
    showGlobalSearchResults.value = false;
    return;
  }

  isGlobalSearching.value = true;
  globalSearchTimeout = setTimeout(async () => {
    try {
      const response = await fetch(`/resources/search?q=${encodeURIComponent(globalSearchQuery.value)}`, {
        headers: {
          'X-Requested-With': 'XMLHttpRequest',
          'Accept': 'application/json',
        },
      });
      const data = await response.json();
      globalSearchResults.value = data;
      showGlobalSearchResults.value = true;
    } catch (error) {
      console.error('Search error:', error);
      globalSearchResults.value = [];
    } finally {
      isGlobalSearching.value = false;
    }
  }, 300);
};

const handleGlobalSearchBlur = () => {
  setTimeout(() => {
    showGlobalSearchResults.value = false;
  }, 200);
};

const viewResource = (resource) => {
  router.visit(`/resources/${resource.Resource_ID}/view`);
};

const updateNavState = () => {
  const hero = document.querySelector('.hero-container');
  if (!hero) {
    isNavScrolled.value = true;
    return;
  }

  const rect = hero.getBoundingClientRect();
  const tolerance = 80;
  isNavScrolled.value = rect.bottom <= tolerance;
};

onMounted(() => {
  updateNavState();
  window.addEventListener('scroll', updateNavState, { passive: true });
  window.addEventListener('resize', updateNavState);
});

onUnmounted(() => {
  window.removeEventListener('scroll', updateNavState);
  window.removeEventListener('resize', updateNavState);
});
</script>

<style>
.is-expanded + .main-content {
  margin-left: 15rem;
  margin-top: 0;
}
</style>

<style scoped>
.main-content {
  overflow-x: hidden;
}

.glass-nav {
  background: transparent;
  transition: all 0.3s ease;
}

.glass-nav.scrolled {
  background: rgba(4, 30, 10, 0.85);
  backdrop-filter: blur(20px);
  -webkit-backdrop-filter: blur(20px);
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
}

@supports not (backdrop-filter: blur(16px)) {
  .glass-nav.scrolled {
    background: linear-gradient(rgba(34, 197, 94, 0.2), rgba(34, 197, 94, 0.2));
    background-position: 50% center;
    background-size: cover;
  }
}

.glass-nav.scrolled::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(4, 30, 10, 0.3);
  backdrop-filter: blur(10px);
  -webkit-backdrop-filter: blur(10px);
  z-index: -1;
}

.glass-nav .nav-item {
  visibility: hidden;
  transition: visibility 0.3s ease;
}

.glass-nav.scrolled .nav-item {
  visibility: visible;
}

.searchbar {
  background: rgba(217, 217, 217, 1);
  color: #000;
  transition: all 0.3s ease;
}
</style>

