<template>
  <transition name="nav-fade">
    <nav
      v-if="shouldRender"
      ref="navRef"
      class="mobile-bottom-nav fixed inset-x-0 bottom-0"
      role="navigation"
      aria-label="Primary mobile navigation"
      @click="handleClickOutside"
    >
      <div class="mobile-bottom-nav__container">
        <button
              v-for="item in normalizedMenuItems"
              :key="item.key"
              type="button"
              class="mobile-bottom-nav__link mobile-nav-btn" 
              :class="{ 'is-active': isRouteActive(item.key) }"
              @click.stop="navigate(item)"
              @mouseenter="(e) => showTooltip(e, item.label)"
              @mouseleave="hideTooltip"
              @touchstart="(e) => showTooltip(e, item.label)"
              @touchend="hideTooltip"
            >
          <img
            v-if="getIcon(item)"
            :src="getIcon(item)"
            :alt="item.label"
            class="mobile-bottom-nav__icon"
            loading="lazy"
          />
          <i
            v-else-if="item.iconClass"
            :class="item.iconClass"
            class="mobile-bottom-nav__icon"
          />
        </button>

        <div class="mobile-bottom-nav__link mobile-bottom-nav__profile" :class="{ 'is-open': showProfileMenu }">
          <button
            type="button"
            class="mobile-bottom-nav__profile-trigger"
            @click.stop="toggleProfileMenu"
            @mouseenter="(e) => showTooltip(e, 'Account')"
            @mouseleave="hideTooltip"
            @touchstart="(e) => showTooltip(e, 'Account')"
            @touchend="hideTooltip"
          >
            <div class="mobile-bottom-nav__profile-avatar">
              <img
                v-if="profileAvatar"
                :src="profileAvatar"
                :alt="profileInitials"
                loading="lazy"
                @error="handleAvatarError"
              />
              <span v-else>{{ profileInitials }}</span>
            </div>
          </button>

          <transition name="dropdown-fade">
            <div
              v-if="showProfileMenu"
              class="mobile-bottom-nav__dropdown"
              role="menu"
              @click.stop
            >
              <button
                v-for="item in profileMenuItems"
                :key="item.action"
                type="button"
                class="mobile-bottom-nav__dropdown-item"
                role="menuitem"
                @click="handleProfileAction(item.action)"
              >
                <span>{{ item.label }}</span>
              </button>
            </div>
          </transition>
        </div>
      </div>
    </nav>
  </transition>
</template>

<script setup>
import { computed, onBeforeUnmount, onMounted, ref } from 'vue';
import { router } from '@inertiajs/vue3';

const props = defineProps({
  menuItems: {
    type: Array,
    default: () => [],
  },
  activeRoute: {
    type: String,
    default: '',
  },
  logoutUrl: {
    type: String,
    default: '/logout',
  },
  profile: {
    type: Object,
    default: () => ({
      name: 'User',
      email: '',
      profile_picture: null,
    }),
  },
  profileMenu: {
    type: Array,
    default: () => ([
      { label: 'Account Settings', action: 'account-settings' },
      { label: 'Logout', action: 'logout' },
    ]),
  },
});

const navRef = ref(null);
const showProfileMenu = ref(false);
const isMobile = ref(false);
let mediaQuery;

const normalizedMenuItems = computed(() => {
  return (props.menuItems ?? []).filter((item) => item && item.type !== 'logout');
});

const profileMenuItems = computed(() => {
  return (props.profileMenu ?? []).filter((item) => item && item.action && item.label);
});

const profileInitials = computed(() => {
  const name = props.profile?.name || 'U';
  const parts = name.trim().split(/\s+/);
  if (parts.length >= 2) {
    return (parts[0][0] + parts[parts.length - 1][0]).toUpperCase();
  }
  return name.substring(0, 2).toUpperCase();
});

const profileAvatar = computed(() => {
  const picture = props.profile?.profile_picture;
  if (!picture) {
    return null;
  }
  if (picture.startsWith('http')) {
    return picture;
  }
  if (picture.startsWith('/storage/')) {
    return picture;
  }
  return `/storage/${picture}`;
});

const shouldRender = computed(() => {
  return isMobile.value && normalizedMenuItems.value.length > 0;
});

const updateMobileState = () => {
  const matches = mediaQuery ? mediaQuery.matches : window.innerWidth <= 1024;
  isMobile.value = matches;
  document.body?.classList.toggle('has-mobile-bottom-nav', matches);
  if (!matches) {
    showProfileMenu.value = false;
  }
};

const handleClickOutside = (event) => {
  if (!showProfileMenu.value || !navRef.value) {
    return;
  }
  const dropdown = navRef.value.querySelector('.mobile-bottom-nav__dropdown');
  const trigger = navRef.value.querySelector('.mobile-bottom-nav__profile-trigger');
  if (dropdown && !dropdown.contains(event.target) && trigger && !trigger.contains(event.target)) {
    showProfileMenu.value = false;
  }
};

const getIcon = (item) => {
  if (!item?.icon) {
    return null;
  }
  if (isRouteActive(item.key) && item.iconActive) {
    return item.iconActive;
  }
  return item.icon;
};

const isRouteActive = (linkKey) => {
  if (!linkKey || !props.activeRoute) {
    return false;
  }
  if (props.activeRoute === linkKey) {
    return true;
  }
  return props.activeRoute.startsWith(`${linkKey}.`) || linkKey.startsWith(`${props.activeRoute}.`);
};

const navigate = (item) => {
  if (!item?.href || item.href === '#') {
    return;
  }
  router.visit(item.href);
};

const toggleProfileMenu = () => {
  showProfileMenu.value = !showProfileMenu.value;
};

const handleProfileAction = (action) => {
  if (action === 'logout') {
    submitLogout();
  } else if (action === 'account-settings') {
    openAccountSettings();
  }
  showProfileMenu.value = false;
};

const openAccountSettings = () => {
  const event = new CustomEvent('sidebar:open-account-settings', {
    bubbles: true,
  });
  window.dispatchEvent(event);
  document.dispatchEvent(event);
};

const submitLogout = async () => {
  const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
  try {
    await fetch(props.logoutUrl, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': csrf || '',
        'Accept': 'application/json',
      },
      body: JSON.stringify({}),
    });
    window.location.href = '/';
  } catch (error) {
    console.error('Logout error:', error);
    window.location.href = props.logoutUrl;
  }
};

const handleAvatarError = () => {
  // Avatar error handled by computed property
};

const showTooltip = (event, label) => {
  const button = event.currentTarget;
  const rect = button.getBoundingClientRect();
  
  // Create or get tooltip element
  let tooltip = document.getElementById('mobile-bottom-nav-tooltip');
  let tooltipArrow = document.getElementById('mobile-bottom-nav-tooltip-arrow');
  
  if (!tooltip) {
    tooltip = document.createElement('div');
    tooltip.id = 'mobile-bottom-nav-tooltip';
    tooltip.className = 'mobile-bottom-nav-tooltip-overlay';
    document.body.appendChild(tooltip);
  }
  
  if (!tooltipArrow) {
    tooltipArrow = document.createElement('div');
    tooltipArrow.id = 'mobile-bottom-nav-tooltip-arrow';
    tooltipArrow.className = 'mobile-bottom-nav-tooltip-arrow';
    document.body.appendChild(tooltipArrow);
  }
  
  // Position tooltip above the button (since nav is at bottom)
  const top = rect.top - 12;
  const left = rect.left + (rect.width / 2);
  
  tooltip.textContent = label;
  tooltip.style.top = `${top}px`;
  tooltip.style.left = `${left}px`;
  tooltip.style.transform = 'translate(-50%, -100%)';
  tooltip.style.opacity = '1';
  tooltip.style.visibility = 'visible';
  
  // Position arrow below tooltip, pointing down
  tooltipArrow.style.top = `${rect.top - 6}px`;
  tooltipArrow.style.left = `${left}px`;
  tooltipArrow.style.transform = 'translateX(-50%)';
  tooltipArrow.style.opacity = '1';
  tooltipArrow.style.visibility = 'visible';
};

const hideTooltip = () => {
  const tooltip = document.getElementById('mobile-bottom-nav-tooltip');
  const tooltipArrow = document.getElementById('mobile-bottom-nav-tooltip-arrow');
  
  if (tooltip) {
    tooltip.style.opacity = '0';
    tooltip.style.visibility = 'hidden';
  }
  
  if (tooltipArrow) {
    tooltipArrow.style.opacity = '0';
    tooltipArrow.style.visibility = 'hidden';
  }
};

onMounted(() => {
  mediaQuery = window.matchMedia('(max-width: 1024px)');
  updateMobileState();
  if (mediaQuery) {
    mediaQuery.addEventListener('change', updateMobileState);
  }
  document.addEventListener('click', handleClickOutside);
});

onBeforeUnmount(() => {
  // Clean up tooltip elements
  hideTooltip();
  const tooltip = document.getElementById('mobile-bottom-nav-tooltip');
  const tooltipArrow = document.getElementById('mobile-bottom-nav-tooltip-arrow');
  if (tooltip) tooltip.remove();
  if (tooltipArrow) tooltipArrow.remove();
  
  if (mediaQuery) {
    mediaQuery.removeEventListener('change', updateMobileState);
  }
  document.removeEventListener('click', handleClickOutside);
});
</script>

<style scoped>
/* Match sidebar exactly */
.mobile-bottom-nav {
  background: #149637;
  border-top-left-radius: 1rem;
  border-top-right-radius: 1rem;
  padding: 0.5rem;
  box-shadow: 0 -4px 12px rgba(0, 0, 0, 0.2);
  z-index: 9999;
  width: 100%;
  left: 0;
  right: 0;
}

.mobile-bottom-nav__container {
  display: flex;
  align-items: stretch;
  justify-content: space-between;
  gap: 0.5rem;
  width: 100%;
}

/* EXACT SIDEBAR STYLING */
.mobile-bottom-nav__link {
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 0.75rem;
  background: #16a34a !important;  /* Use !important and a visible green */
  border-radius: 0.75rem;
  box-shadow: none;  /* Remove the inset shadow */
  color: white;
  text-decoration: none;
  transition: all 0.3s ease;
  cursor: pointer;
  position: relative;
  border: none;
  font-family: inherit;
  font-size: inherit;
  flex: 1;
  min-width: 0;
  aspect-ratio: 1;
}

.mobile-bottom-nav__link:hover {
  background: #15803d !important;
}

.mobile-bottom-nav__link.is-active {
  background: #22c55e !important;
  box-shadow: 0 0 0 2px rgba(34, 197, 94, 0.5);
}

.mobile-bottom-nav__icon {
  width: 2rem;
  height: 2rem;
  flex-shrink: 0;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  font-size: 1.75rem;
  transition: all 0.3s ease;
  color: white;
  object-fit: contain;
  margin: 0;
}


/* Profile Section */
.mobile-bottom-nav__profile {
  position: relative;
}

.mobile-bottom-nav__profile-trigger {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 100%;
  height: 100%;
  padding: 0;
  background: transparent;
  border: none;
  color: inherit;
  cursor: pointer;
}

.mobile-bottom-nav__profile-avatar {
  width: 2rem;
  height: 2rem;
  border-radius: 0.375rem;
  background: transparent;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  font-weight: 700;
  font-size: 0.875rem;
  color: #fff;
  overflow: hidden;
  flex-shrink: 0;
}

.mobile-bottom-nav__profile-avatar img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

/* Dropdown - Fixed positioning */
.mobile-bottom-nav__dropdown {
  position: absolute;
  bottom: calc(100% + 0.5rem);
  right: 0;
  width: 200px;
  background: #fff;
  border-radius: 0.75rem;
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
  padding: 0.5rem;
  display: flex;
  flex-direction: column;
  gap: 0.25rem;
  z-index: 10000;
  min-width: 180px;
}

.mobile-bottom-nav__dropdown-item {
  padding: 0.75rem 1rem;
  text-align: left;
  background: transparent;
  border: none;
  color: #374151;
  font-size: 0.875rem;
  font-weight: 500;
  cursor: pointer;
  border-radius: 0.5rem;
  transition: background 0.2s ease;
  width: 100%;
}

.mobile-bottom-nav__dropdown-item:hover {
  background: #f3f4f6;
}

.mobile-bottom-nav__dropdown-item:active {
  background: #e5e7eb;
}

/* Transitions */
.nav-fade-enter-active,
.nav-fade-leave-active {
  transition: opacity 0.3s ease, transform 0.3s ease;
}

.nav-fade-enter-from {
  opacity: 0;
  transform: translateY(100%);
}

.nav-fade-leave-to {
  opacity: 0;
  transform: translateY(100%);
}

.dropdown-fade-enter-active,
.dropdown-fade-leave-active {
  transition: opacity 0.2s ease, transform 0.2s ease;
}

.dropdown-fade-enter-from {
  opacity: 0;
  transform: translateY(-10px);
}

.dropdown-fade-leave-to {
  opacity: 0;
  transform: translateY(-10px);
}

/* Hide on larger screens */
@media (min-width: 1025px) {
  .mobile-bottom-nav {
    display: none !important;
  }
}
</style>

<style>
.mobile-bottom-nav-tooltip-overlay {
  position: fixed;
  background: #1f2937;
  color: white;
  padding: 0.5rem 0.75rem;
  border-radius: 0.5rem;
  font-size: 0.875rem;
  white-space: nowrap;
  opacity: 0;
  pointer-events: none;
  z-index: 99999 !important;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
  font-family: 'Kulim Park', sans-serif;
  transition: opacity 0.2s ease 0.1s, visibility 0.2s ease 0.1s;
  visibility: hidden;
  min-width: max-content;
}

.mobile-bottom-nav-tooltip-arrow {
  position: fixed;
  border: 6px solid transparent;
  border-top-color: #1f2937;
  opacity: 0;
  pointer-events: none;
  z-index: 99998 !important;
  transition: opacity 0.2s ease 0.1s, visibility 0.2s ease 0.1s;
  visibility: hidden;
  width: 0;
  height: 0;
}
</style>
