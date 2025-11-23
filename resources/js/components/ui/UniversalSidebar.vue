<template>
  <div
    ref="sidebarRef"
    class="universal-sidebar"
    :class="{ 'is-expanded': isExpanded, 'is-collapsed': !isExpanded }"
    @click="handleSidebarClick"
  >
    <!-- Logo Section -->
    <div class="universal-sidebar__logo">
      <img
        v-if="logos.border"
        :src="logos.border"
        alt="ISU StudyGo"
        class="universal-sidebar__logo-border"
      />
      <img
        v-if="logos.solid"
        :src="logos.solid"
        alt="ISU StudyGo"
        class="universal-sidebar__logo-solid"
      />
    </div>

    <!-- Role Text (Plain Text) -->
    <div v-if="role" class="universal-sidebar__role">
      <span class="universal-sidebar__role-text">{{ role.toUpperCase() }}</span>
    </div>

    <!-- Navigation Links -->
    <nav class="universal-sidebar__nav">
      <template v-for="link in menuItems" :key="link.key">
        <!-- Regular Links (logout removed from menu) -->
        <a
          v-if="link.type !== 'logout' && isExpanded"
          :href="link.href"
          class="universal-sidebar__link"
          :class="{ 'is-active': isActiveRoute(link.key) }"
          @click.stop.prevent="handleLinkClick(link, $event)"
        >
          <img
            v-if="link.icon"
            :src="isActiveRoute(link.key) && link.iconActive ? link.iconActive : link.icon"
            :alt="link.label"
            class="universal-sidebar__icon"
          />
          <i
            v-else-if="link.iconClass"
            :class="link.iconClass"
            class="universal-sidebar__icon"
          ></i>
          <span class="universal-sidebar__label">{{ link.label }}</span>
        </a>
        <button
          v-else-if="link.type !== 'logout' && !isExpanded"
          type="button"
          class="universal-sidebar__link"
          :class="{ 'is-active': isActiveRoute(link.key) }"
          @click.stop="handleLinkClick(link, $event)"
          @mouseenter="(e) => showTooltip(e, link.label)"
          @mouseleave="hideTooltip"
          :data-tooltip="link.label"
        >
          <img
            v-if="link.icon"
            :src="isActiveRoute(link.key) && link.iconActive ? link.iconActive : link.icon"
            :alt="link.label"
            class="universal-sidebar__icon"
          />
          <i
            v-else-if="link.iconClass"
            :class="link.iconClass"
            class="universal-sidebar__icon"
          ></i>
          <span class="universal-sidebar__label">{{ link.label }}</span>
        </button>
      </template>
    </nav>

    <!-- Profile Card Section (Account Settings) -->
    <div class="universal-sidebar__profile-wrapper">
      <div class="universal-sidebar__profile" @click.stop="toggleProfileDropdown">
        <div class="universal-sidebar__profile-avatar">
          <img
            v-if="user?.profile_picture"
            :src="getProfilePictureUrl(user.profile_picture)"
            :alt="user.name"
            class="w-full h-full object-cover rounded-full"
            @error="handleImageError"
          />
          <div v-else class="universal-sidebar__profile-placeholder">
            {{ userInitials }}
          </div>
        </div>
        <div v-if="isExpanded" class="universal-sidebar__profile-info">
          <div class="universal-sidebar__profile-name">{{ user.name }}</div>
          <div class="universal-sidebar__profile-email">{{ user.email }}</div>
        </div>
        <i 
          v-if="isExpanded"
          class="fas universal-sidebar__profile-chevron"
          :class="showProfileDropdown ? 'fa-chevron-up' : 'fa-chevron-down'"
          style="display: block;"
        ></i>
      </div>

      <!-- Profile Dropdown Menu (Drop-up) -->
      <transition name="dropdown-fade">
        <div
          v-if="showProfileDropdown && isExpanded"
          class="universal-sidebar__profile-dropdown"
          @click.stop
        >
          <button
            class="universal-sidebar__dropdown-item"
            type="button"
            @click.stop="openAccountSettings"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
            </svg>
            <span>Account Settings</span>
          </button>
          <button
            type="button"
            @click="handleLogout"
            class="universal-sidebar__dropdown-item universal-sidebar__dropdown-item--logout"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
            </svg>
            <span>Logout</span>
          </button>
        </div>
      </transition>
    </div>

    <!-- Footer -->
    <div class="universal-sidebar__footer">
      <p class="universal-sidebar__footer-text">© Copyright Sapaula, A. & Benitez, R.D., ISU StudyGo.<br></br> All rights reserved.</p>
    </div>

   </div>

  <!-- Account Settings Overlay -->
  <AccountSettingsOverlay
    v-if="showAccountSettings"
    v-model="showAccountSettings"
    :user="user"
    :courses="courses"
    @save="handleAccountSave"
  />
</template>

<script setup>
import { computed, onBeforeUnmount, onMounted, ref, watch } from 'vue';
import { router } from '@inertiajs/vue3';
import AccountSettingsOverlay from './AccountSettingsOverlay.vue';

const props = defineProps({
  menuItems: {
    type: Array,
    required: true,
    default: () => [],
  },
  activeRoute: {
    type: String,
    default: '',
  },
  user: {
    type: Object,
    default: () => ({
      name: 'John Doe',
      email: 'john.doe@example.com',
      avatar: null,
    }),
  },
  logos: {
    type: Object,
    default: () => ({
      border: null,
      solid: null,
    }),
  },
  defaultExpanded: {
    type: Boolean,
    default: false,
  },
  csrfToken: {
    type: String,
    default: '',
  },
  logoutUrl: {
    type: String,
    default: '/logout',
  },
  role: {
    type: String,
    default: '',
  },
  courses: {
    type: Array,
    default: () => [],
  },
});

const isExpanded = ref(props.defaultExpanded);
const showAccountSettings = ref(false);
const showProfileDropdown = ref(false);
const sidebarRef = ref(null);
const tooltipRef = ref(null);
const tooltipArrowRef = ref(null);

const logoutHref = computed(() => {
  return props.logoutUrl || '/logout';
});

const getProfilePictureUrl = (profilePicture) => {
  if (!profilePicture) return null;
  // If path already includes /storage/, use it as is
  if (profilePicture.startsWith('/storage/')) {
    return profilePicture;
  }
  // If path starts with storage/ (no leading slash), add leading slash
  if (profilePicture.startsWith('storage/')) {
    return `/${profilePicture}`;
  }
  // Otherwise, prepend /storage/
  return `/storage/${profilePicture}`;
};

const handleImageError = (event) => {
  // If image fails to load, hide it and show placeholder
  event.target.style.display = 'none';
};

const userInitials = computed(() => {
  if (!props.user.name) return 'U';
  const names = props.user.name.split(' ');
  if (names.length >= 2) {
    return (names[0][0] + names[names.length - 1][0]).toUpperCase();
  }
  return props.user.name.substring(0, 2).toUpperCase();
});

const handleSidebarClick = (event) => {
  // Don't toggle if clicking on links or profile
  if (
    event.target.closest('.universal-sidebar__link') ||
    event.target.closest('.universal-sidebar__profile')
  ) {
    return;
  }
  toggleSidebar();
};

const toggleSidebar = () => {
  isExpanded.value = !isExpanded.value;
  updateBodyClass();
  // Hide tooltip when sidebar expands
  if (isExpanded.value) {
    hideTooltip();
  }
};

const isActiveRoute = (linkKey) => {
  // Check if the current route matches the link key
  if (!props.activeRoute || !linkKey) return false;
  
  // Exact match
  if (props.activeRoute === linkKey) return true;
  
  // Handle route name variations (e.g., 'admin.approvals' matches 'admin.dashboard' if they're the same page)
  // Also check if current route starts with the link key (for nested routes)
  if (props.activeRoute.startsWith(linkKey + '.') || linkKey.startsWith(props.activeRoute + '.')) {
    return true;
  }
  
  return false;
};

const handleLinkClick = (link, event) => {
  event.preventDefault();
  // Navigate using Inertia router
  if (link.href && link.href !== '#') {
    router.visit(link.href);
  }
};

const showTooltip = (event, label) => {
  if (isExpanded.value) return;
  
  const button = event.currentTarget;
  const rect = button.getBoundingClientRect();
  
  // Create or get tooltip element
  let tooltip = document.getElementById('sidebar-tooltip');
  let tooltipArrow = document.getElementById('sidebar-tooltip-arrow');
  
  if (!tooltip) {
    tooltip = document.createElement('div');
    tooltip.id = 'sidebar-tooltip';
    tooltip.className = 'sidebar-tooltip-overlay';
    document.body.appendChild(tooltip);
  }
  
  if (!tooltipArrow) {
    tooltipArrow = document.createElement('div');
    tooltipArrow.id = 'sidebar-tooltip-arrow';
    tooltipArrow.className = 'sidebar-tooltip-arrow';
    document.body.appendChild(tooltipArrow);
  }
  
  // Position tooltip
  const top = rect.top + (rect.height / 2);
  const left = rect.right + 12;
  
  tooltip.textContent = label;
  tooltip.style.top = `${top}px`;
  tooltip.style.left = `${left}px`;
  tooltip.style.transform = 'translateY(-50%)';
  tooltip.style.opacity = '1';
  tooltip.style.visibility = 'visible';
  
  // Position arrow
  tooltipArrow.style.top = `${top}px`;
  tooltipArrow.style.left = `${rect.right + 6}px`;
  tooltipArrow.style.transform = 'translateY(-50%)';
  tooltipArrow.style.opacity = '1';
  tooltipArrow.style.visibility = 'visible';
};

const hideTooltip = () => {
  const tooltip = document.getElementById('sidebar-tooltip');
  const tooltipArrow = document.getElementById('sidebar-tooltip-arrow');
  
  if (tooltip) {
    tooltip.style.opacity = '0';
    tooltip.style.visibility = 'hidden';
  }
  
  if (tooltipArrow) {
    tooltipArrow.style.opacity = '0';
    tooltipArrow.style.visibility = 'hidden';
  }
};

const handleLogoutClick = (event) => {
  // Allow form submission for logout
  return true;
};


const toggleProfileDropdown = () => {
  if (!isExpanded.value) {
    // If collapsed, expand sidebar first
    isExpanded.value = true;
    updateBodyClass();
    // Then show dropdown after a short delay
    setTimeout(() => {
      showProfileDropdown.value = true;
    }, 300);
  } else {
    showProfileDropdown.value = !showProfileDropdown.value;
  }
};

const openAccountSettings = (event) => {
  if (event) {
    event.preventDefault();
    event.stopPropagation();
  }
  showProfileDropdown.value = false;
  // Use nextTick to ensure DOM is ready
  setTimeout(() => {
    showAccountSettings.value = true;
  }, 100);
};

const handleLogout = () => {
  router.post(logoutHref.value, {}, {
    onFinish: () => {
      // Logout completed
    },
    onSuccess: () => {
      // Redirect handled by server
    },
    onError: (errors) => {
      console.error('Logout error:', errors);
      // If there's a CSRF error, try to reload and retry
      if (errors && (errors.message?.includes('419') || errors.message?.includes('CSRF'))) {
        window.location.reload();
      }
    },
  });
};

const handleAccountSave = async (data) => {
  try {
    const formData = new FormData();
    formData.append('first_name', data.first_name);
    formData.append('last_name', data.last_name);
    formData.append('email', data.email);
    if (data.newPassword && data.newPassword === data.confirmPassword) {
      formData.append('password', data.newPassword);
      formData.append('password_confirmation', data.confirmPassword);
    }
    // Add student-specific fields if user is a student
    if (props.user?.role === 'student') {
      if (data.student_number) {
        formData.append('student_number', data.student_number);
      }
      if (data.course_id) {
        formData.append('course_id', data.course_id);
      }
    }

    const response = await fetch('/profile/update', {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
        'Accept': 'application/json',
      },
      body: formData,
    });

    if (response.ok) {
      // Show success message and close modal
      alert('Profile updated successfully!');
      showAccountSettings.value = false;
      // Optionally reload to refresh sidebar data
      setTimeout(() => {
        window.location.reload();
      }, 500);
    } else {
      const errorData = await response.json();
      alert(errorData.message || 'Failed to update profile. Please try again.');
    }
  } catch (error) {
    console.error('Error updating profile:', error);
    alert('An error occurred while updating your profile. Please try again.');
  }
};

const updateBodyClass = () => {
  if (document && document.body) {
    document.body.classList.toggle('has-universal-sidebar-expanded', isExpanded.value);
  }
};

const handleClickOutside = (event) => {
  // Close profile dropdown if clicking outside
  if (showProfileDropdown.value && sidebarRef.value && !sidebarRef.value.contains(event.target)) {
    if (!event.target.closest('.account-settings-modal')) {
      showProfileDropdown.value = false;
    }
  }
  
  if (isExpanded.value && sidebarRef.value && !sidebarRef.value.contains(event.target)) {
    // Don't collapse if clicking on account settings overlay
    if (event.target.closest('.account-settings-modal')) {
      return;
    }
    isExpanded.value = false;
    updateBodyClass();
    showProfileDropdown.value = false;
  }
};

// Ensure sidebar stays fixed - AGGRESSIVE
const ensureSidebarFixed = () => {
  if (sidebarRef.value) {
    // Remove from any parent that might scroll
    if (sidebarRef.value.parentElement) {
      const parent = sidebarRef.value.parentElement;
      if (parent.style.position === 'relative' || parent.style.position === 'absolute') {
        parent.style.position = 'static';
      }
    }
    // Force fixed positioning with !important via setAttribute
    sidebarRef.value.setAttribute('style', 'position: fixed !important; top: 0 !important; left: 0 !important; z-index: 9999 !important; margin: 0 !important; transform: none !important;');
    // Also set via style for immediate effect
    sidebarRef.value.style.position = 'fixed';
    sidebarRef.value.style.top = '0';
    sidebarRef.value.style.left = '0';
    sidebarRef.value.style.zIndex = '9999';
    sidebarRef.value.style.margin = '0';
    sidebarRef.value.style.transform = 'none';
  }
};

onMounted(() => {
  updateBodyClass();
  document.addEventListener('click', handleClickOutside);
  
  // Ensure sidebar stays fixed on mount
  ensureSidebarFixed();
  
  // Re-apply fixed positioning on scroll and resize
  window.addEventListener('scroll', ensureSidebarFixed, { passive: true, capture: true });
  window.addEventListener('resize', ensureSidebarFixed);
});

onBeforeUnmount(() => {
  // Clean up tooltip elements
  hideTooltip();
  const tooltip = document.getElementById('sidebar-tooltip');
  const tooltipArrow = document.getElementById('sidebar-tooltip-arrow');
  if (tooltip) tooltip.remove();
  if (tooltipArrow) tooltipArrow.remove();
  document.removeEventListener('click', handleClickOutside);
  window.removeEventListener('scroll', ensureSidebarFixed);
  window.removeEventListener('resize', ensureSidebarFixed);
  if (document && document.body) {
    document.body.classList.remove('has-universal-sidebar-expanded');
  }
  document.body.style.overflow = '';
});

watch(() => props.defaultExpanded, (newVal) => {
  isExpanded.value = newVal;
  updateBodyClass();
  if (isExpanded.value) {
    hideTooltip();
  }
});

watch(isExpanded, (newVal) => {
  if (newVal) {
    hideTooltip();
  }
});

watch(showAccountSettings, (isOpen) => {
  if (document && document.body) {
    document.body.style.overflow = isOpen ? 'hidden' : '';
  }
});
</script>

<style scoped>
.universal-sidebar {
  position: fixed !important;
  top: 0 !important;
  left: 0 !important;
  height: 100vh !important;
  background: #149637 !important;
  box-shadow: 5px -10px 22.5px 2px rgba(0, 0, 0, 0.59);
  border-top-right-radius: 50px;
  z-index: 20 !important;
  padding: 2rem 0.5rem;
  display: flex !important;
  flex-direction: column;
  transition: width 0.3s cubic-bezier(0.215, 0.61, 0.355, 1);
  overflow-y: auto;
  overflow-x: hidden;
  cursor: pointer;
  width: 4rem;
  /* Ensure sidebar renders immediately */
  visibility: visible !important;
  opacity: 1 !important;
}

.universal-sidebar.is-expanded {
  width: 18rem;
}

.universal-sidebar__logo {
  position: relative;
  width: 100%;
  height: 5rem;
  margin-bottom: 1rem;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
  margin-top: 0;
}

.universal-sidebar.is-collapsed .universal-sidebar__logo {
  height: 3rem;
  margin-bottom: 1rem;
}

.universal-sidebar__logo-border {
  position: absolute;
  width: 100%;
  height: 5rem;
  object-fit: contain;
  opacity: 0;
  transition: opacity 0.3s ease;
}

.universal-sidebar__logo-solid {
  position: absolute;
  width: 100%;
  height: 2.5rem;
  object-fit: contain;
  transform: translateY(1.25rem);
  opacity: 1;
  transition: opacity 0.3s ease;
}

.universal-sidebar.is-expanded .universal-sidebar__logo-border {
  opacity: 1;
}

.universal-sidebar.is-expanded .universal-sidebar__logo-solid {
  opacity: 0;
}

.universal-sidebar__nav {
  flex: 1;
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
  overflow-y: auto;
  overflow-x: hidden;
  padding-right: 0.25rem;
  min-height: 0;
  margin-top: 0;
  padding-top: 0;
  scrollbar-width: thin;
  scrollbar-color: rgba(255, 255, 255, 0.3) transparent;
}

/* Add space for role when expanded, but keep nav position consistent */
.universal-sidebar.is-expanded .universal-sidebar__nav {
  padding-top: 0;
}

.universal-sidebar__nav::-webkit-scrollbar {
  width: 4px;
  height: 0;
}

.universal-sidebar__nav::-webkit-scrollbar:horizontal {
  display: none;
  height: 0;
}

.universal-sidebar__nav::-webkit-scrollbar-thumb {
  background: rgba(255, 255, 255, 0.3);
  border-radius: 2px;
}

.universal-sidebar__link-form {
  width: 100%;
  margin: 0;
  padding: 0;
}

.universal-sidebar__link {
  display: flex;
  align-items: center;
  width: 100%;
  height: 2.5rem;
  padding: 0 0.5rem;
  background: #166534;
  border-radius: 0.75rem;
  box-shadow: inset 0px 4px 4px 0px rgba(0, 0, 0, 0.25);
  color: white;
  text-decoration: none;
  transition: all 0.3s ease;
  cursor: pointer;
  position: relative;
  border: none;
  font-family: inherit;
  font-size: inherit;
}

.universal-sidebar__link--logout {
  background: transparent;
  box-shadow: none;
}

.universal-sidebar__link--logout:hover {
  background: rgba(220, 38, 38, 0.2);
}

.universal-sidebar__link:hover {
  background: #15803d;
}

.universal-sidebar__link.is-active {
  background: #22c55e;
  box-shadow: 0 0 0 2px rgba(34, 197, 94, 0.5);
}

.universal-sidebar.is-collapsed button.universal-sidebar__link:hover {
  background: #15803d;
}

.universal-sidebar.is-collapsed button.universal-sidebar__link.is-active {
  background: #22c55e;
  box-shadow: 0 0 0 2px rgba(34, 197, 94, 0.5);
}

.universal-sidebar__icon {
  width: 1.5rem;
  height: 1.5rem;
  flex-shrink: 0;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  font-size: 1.25rem;
  transition: all 0.3s ease;
  color: white;
}

.universal-sidebar__icon[src] {
  object-fit: contain;
  width: 1.5rem;
  height: 1.5rem;
}

/* Perfectly center icons when collapsed */
.universal-sidebar.is-collapsed .universal-sidebar__link {
  justify-content: center;
  padding: 0;
  overflow: visible;
}

.universal-sidebar.is-collapsed .universal-sidebar__icon {
  margin: 0;
  transform: none;
  display: block !important;
  opacity: 1 !important;
  visibility: visible !important;
  width: 1.5rem !important;
  height: 1.5rem !important;
}

.universal-sidebar.is-collapsed .universal-sidebar__icon[src] {
  display: block !important;
  opacity: 1 !important;
  visibility: visible !important;
}

/* When expanded, align icons to the left */
.universal-sidebar.is-expanded .universal-sidebar__link {
  justify-content: flex-start;
  padding: 0 0.75rem;
}

.universal-sidebar.is-expanded .universal-sidebar__icon {
  margin-right: 0.75rem;
}

.universal-sidebar__label {
  font-family: 'Kulim Park', sans-serif;
  font-size: 1.125rem;
  font-weight: 400;
  white-space: nowrap;
  opacity: 0;
  transform: translateX(-10px);
  transition: all 0.3s ease;
  padding-left: 0.5rem;
}

.universal-sidebar.is-expanded .universal-sidebar__label {
  opacity: 1;
  transform: translateX(0);
}

.universal-sidebar.is-collapsed .universal-sidebar__label {
  display: none;
  width: 0;
  height: 0;
  overflow: hidden;
}

/* Tooltip for collapsed sidebar - using JavaScript overlay */
.universal-sidebar.is-collapsed button.universal-sidebar__link {
  position: relative;
  overflow: visible;
  border: none;
  cursor: pointer;
  width: 100%;
  text-align: left;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 0;
  height: 2.5rem;
  background: #166534;
  border-radius: 0.75rem;
  box-shadow: inset 0px 4px 4px 0px rgba(0, 0, 0, 0.25);
  color: white;
  text-decoration: none;
  transition: all 0.3s ease;
  font-family: inherit;
  font-size: inherit;
}

.universal-sidebar__profile-wrapper {
  position: relative;
  margin-top: auto;
  margin-bottom: 0.5rem;
}

.universal-sidebar__footer {
  padding: 0.75rem 0.5rem;
  text-align: center;
  border-top: 1px solid rgba(255, 255, 255, 0.1);
  margin-top: 0.5rem;
  flex-shrink: 0;
  opacity: 1;
  transition: opacity 0.3s ease;
}

.universal-sidebar__footer-text {
  color: rgba(255, 255, 255, 0.7);
  font-size: 0.625rem;
  font-family: 'Kulim Park', sans-serif;
  font-weight: 400;
  margin: 0;

}

.universal-sidebar.is-expanded .universal-sidebar__footer {
  opacity: 1;
}

.universal-sidebar.is-collapsed .universal-sidebar__footer {
  opacity: 0;
  height: 0;
  padding: 0;
  margin: 0;
  border-top: none;
  overflow: hidden;
}

.universal-sidebar__profile {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  padding: 0.75rem;
  background: rgba(255, 255, 255, 0.1);
  border-radius: 0.75rem;
  cursor: pointer;
  transition: background 0.3s ease;
  position: relative;
  width: 100%;
}

/* Center profile when collapsed */
.universal-sidebar.is-collapsed .universal-sidebar__profile {
  justify-content: center;
  padding: 0.75rem 0.5rem;
  background: transparent;
}

.universal-sidebar.is-collapsed .universal-sidebar__profile-avatar {
  background: rgba(255, 255, 255, 0.2);
  border: 2px solid rgba(255, 255, 255, 0.3);
}

.universal-sidebar.is-expanded .universal-sidebar__profile {
  justify-content: flex-start;
}

.universal-sidebar__profile:hover {
  background: rgba(255, 255, 255, 0.15);
}

.universal-sidebar__profile-chevron {
  margin-left: auto;
  font-size: 0.75rem;
  transition: all 0.3s ease;
  color: rgba(255, 255, 255, 0.8);
  flex-shrink: 0;
  opacity: 0;
  transform: translateX(-10px);
}

.universal-sidebar.is-expanded .universal-sidebar__profile-chevron {
  opacity: 1;
  transform: translateX(0);
}

.universal-sidebar.is-collapsed .universal-sidebar__profile-chevron {
  display: none;
}

.universal-sidebar__profile-avatar {
  width: 2.5rem;
  height: 2.5rem;
  border-radius: 0.5rem;
  background: rgba(255, 255, 255, 0.2);
  border: 2px solid rgba(255, 255, 255, 0.3);
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
  overflow: hidden;
  box-sizing: border-box;
}

.universal-sidebar__profile-avatar img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  border-radius: 0.375rem;
}

.universal-sidebar__profile-placeholder {
  width: 100%;
  height: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  font-weight: 600;
  font-size: 0.875rem;
}

.universal-sidebar__profile-info {
  flex: 1;
  min-width: 0;
  opacity: 0;
  transform: translateX(-10px);
  transition: all 0.3s ease;
  overflow: hidden;
}

.universal-sidebar.is-expanded .universal-sidebar__profile-info {
  opacity: 1;
  transform: translateX(0);
}

.universal-sidebar__profile-name {
  font-weight: 600;
  font-size: 0.875rem;
  color: white;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

.universal-sidebar__profile-email {
  font-size: 0.75rem;
  color: rgba(255, 255, 255, 0.8);
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

/* Role Text (Plain Text) - Uses visibility to maintain space */
.universal-sidebar__role {
  padding: 0 0.50rem;

  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
  height: 1.5rem;
  visibility: hidden;
  opacity: 0;
  transition: all 0.3s ease;
}

.universal-sidebar.is-expanded .universal-sidebar__role {
  visibility: visible;
  opacity: 1;
  justify-content: flex-start;
}

.universal-sidebar__role-text {
  color: white;
  font-size: 0.875rem;
  font-weight: 400;
  font-family: 'Kulim Park', sans-serif;
  text-transform: uppercase;
  letter-spacing: 0.05em;
}

/* Profile Dropdown */
.universal-sidebar__profile-dropdown {
  position: absolute;
  bottom: 100%;
  left: 0 !important;
  right: auto;
  margin-bottom: 0.5rem;
  background: white;
  border-radius: 0.75rem;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
  overflow: hidden;
  z-index: 100;
  min-width: 100%;
  text-align: left;
  transform: translateX(0) !important;
}

.universal-sidebar__dropdown-item-form {
  width: 100%;
  margin: 0;
  padding: 0;
}

.universal-sidebar__dropdown-item {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  width: 100%;
  padding: 0.75rem 1rem;
  background: transparent;
  border: none;
  color: #1f2937 !important;
  font-size: 0.875rem;
  font-weight: 500;
  cursor: pointer;
  transition: background 0.2s ease;
  text-align: left;
  font-family: 'Kantumruy Pro', sans-serif;
}

.universal-sidebar__dropdown-item:hover {
  background: #f3f4f6;
}

.universal-sidebar__dropdown-item i {
  width: 1.25rem;
  color: #6b7280;
  font-size: 0.875rem;
}

.universal-sidebar__dropdown-item--logout {
  color: #dc2626;
  border-top: 1px solid #e5e7eb;
}

.universal-sidebar__dropdown-item--logout:hover {
  background: #fef2f2;
}

.universal-sidebar__dropdown-item--logout i {
  color: #dc2626;
}

/* Dropdown Animation */
.dropdown-fade-enter-active,
.dropdown-fade-leave-active {
  transition: all 0.2s ease;
}

.dropdown-fade-enter-from {
  opacity: 0;
  transform: translateY(10px);
}

.dropdown-fade-leave-to {
  opacity: 0;
  transform: translateY(10px);
}

/* Responsive */
@media (max-width: 768px) {
  .universal-sidebar {
    display: none;
  }
}
/* Global tooltip overlay styles (not scoped) */
</style>

<style>
.sidebar-tooltip-overlay {
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

.sidebar-tooltip-arrow {
  position: fixed;
  border: 6px solid transparent;
  border-right-color: #1f2937;
  opacity: 0;
  pointer-events: none;
  z-index: 99998 !important;
  transition: opacity 0.2s ease 0.1s, visibility 0.2s ease 0.1s;
  visibility: hidden;
  width: 0;
  height: 0;
}
</style>

