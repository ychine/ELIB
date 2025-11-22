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
    />

    <div class="flex bg-white flex-col flex-1 transition-all duration-300 main-content" style="overflow-x: visible;">
      <div class="fixed w-full top-0 left-0 flex justify-between items-center px-4 py-2 z-10 glass-nav"
        :class="{ scrolled: isNavScrolled }">
        <span class="text-5xl jersey-20-regular pl-3 text-white"></span>
        <div class="relative flex items-center">
          <input
            class="searchbar pl-7 pr-10 sm:w-[545px] h-11 rounded-[34px] shadow-[inset_0px_4px_4px_rgba(0,0,0,0.25)]"
            type="text"
            placeholder="Search for books, papers.."
          >
          <img
            :src="searchIcon"
            alt="Search icon"
            class="absolute right-5 w-6 h-6"
          >
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
import { usePage } from '@inertiajs/vue3';
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

