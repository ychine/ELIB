<script setup>
import { computed, onBeforeUnmount, onMounted, ref, watch } from 'vue';

const props = defineProps({
  links: {
    type: Array,
    default: () => [],
  },
  active: {
    type: String,
    default: '',
  },
  role: {
    type: String,
    default: '',
  },
  logos: {
    type: Object,
    default: () => ({}),
  },
});

const expanded = ref(false);
const isMobile = ref(false);
const sidebarRef = ref(null);

const navLinks = computed(() => props.links.filter((link) => link.type !== 'logout'));
const logoutLink = computed(() => props.links.find((link) => link.type === 'logout'));

const mediaQuery = window.matchMedia('(max-width: 768px)');

const updateSidebarState = () => {
  if (!document || !document.body) return;

  document.body.classList.add('has-dashboard-sidebar');

  if (isMobile.value) {
    document.body.dataset.sidebarState = 'mobile';
  } else {
    document.body.dataset.sidebarState = expanded.value ? 'expanded' : 'collapsed';
  }
};

const handleResize = () => {
  isMobile.value = mediaQuery.matches;
  if (isMobile.value) {
    expanded.value = false;
  }
  updateSidebarState();
};

const toggleSidebar = (targetState = !expanded.value) => {
  if (isMobile.value) return;
  expanded.value = targetState;
};

const handleSidebarBackgroundClick = (event) => {
  if (isMobile.value) return;

  const clickedInteractive =
    event.target.closest('.dashboard-sidebar__link') ||
    event.target.closest('.dashboard-sidebar__toggle');

  if (!clickedInteractive) {
    toggleSidebar(!expanded.value);
  }
};

const submitLogout = () => {
  const form = document.getElementById('logout-form');
  if (form) {
    form.submit();
  } else {
    console.warn('Logout form not found. Ensure a form with id="logout-form" exists.');
  }
};

const handleLinkClick = (link, event) => {
  if (event) {
    event.stopPropagation();
  }

  if (!link) return;

  if (!isMobile.value && !expanded.value) {
    expanded.value = true;
    return;
  }

  if (link.type === 'logout') {
    submitLogout();
    return;
  }

  if (link.href) {
    window.location.href = link.href;
  }
};

onMounted(() => {
  isMobile.value = mediaQuery.matches;
  updateSidebarState();
  mediaQuery.addEventListener('change', handleResize);
});

onBeforeUnmount(() => {
  mediaQuery.removeEventListener('change', handleResize);
  if (document && document.body) {
    document.body.classList.remove('has-dashboard-sidebar');
    delete document.body.dataset.sidebarState;
  }
});

watch([expanded, isMobile], () => updateSidebarState());
</script>

<template>
  <div
    ref="sidebarRef"
    class="dashboard-sidebar"
    :class="{ expanded }"
    @click="handleSidebarBackgroundClick"
  >
    <button
      class="dashboard-sidebar__toggle"
      type="button"
      aria-label="Toggle sidebar"
      @click.stop="toggleSidebar()"
    >
      {{ expanded ? 'Collapse' : 'Menu' }}
    </button>

    <div class="dashboard-sidebar__logo">
      <img
        v-if="logos.border"
        :src="logos.border"
        alt="ISU StudyGo"
        class="dashboard-sidebar__logo-border"
      />
      <img
        v-if="logos.solid"
        :src="logos.solid"
        alt="ISU StudyGo"
        class="dashboard-sidebar__logo-solid"
      />
    </div>

    <nav class="dashboard-sidebar__nav">
      <button
        v-for="link in navLinks"
        :key="link.key"
        type="button"
        class="dashboard-sidebar__link"
        :class="{ active: active === link.key }"
        @click="handleLinkClick(link, $event)"
      >
        <img v-if="link.icon" :src="link.icon" :alt="link.label" />
        <i v-else-if="link.iconClass" :class="link.iconClass"></i>
        <span class="dashboard-sidebar__label">{{ link.label }}</span>
      </button>
    </nav>

    <div class="dashboard-sidebar__role" v-if="role">
      {{ role.toUpperCase() }}
    </div>

    <button
      v-if="logoutLink"
      type="button"
      class="dashboard-sidebar__link"
      @click="handleLinkClick(logoutLink, $event)"
    >
      <img v-if="logoutLink.icon" :src="logoutLink.icon" alt="Logout" />
      <i v-else-if="logoutLink.iconClass" :class="logoutLink.iconClass"></i>
      <span class="dashboard-sidebar__label">{{ logoutLink.label }}</span>
    </button>

    <div class="dashboard-bottom-nav">
      <div class="dashboard-bottom-nav__list">
        <button
          v-for="link in [...navLinks, ...(logoutLink ? [logoutLink] : [])]"
          :key="`${link.key}-mobile`"
          type="button"
          class="dashboard-bottom-nav__button"
          :class="{ active: active === link.key }"
          @click="handleLinkClick(link, $event)"
        >
          <img v-if="link.icon" :src="link.icon" :alt="link.label" />
          <i v-else-if="link.iconClass" :class="link.iconClass"></i>
          <span>{{ link.label }}</span>
        </button>
      </div>
    </div>
  </div>
</template>

