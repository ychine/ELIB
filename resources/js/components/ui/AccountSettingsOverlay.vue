<template>
  <!-- Custom Modal - No Ionic -->
  <Teleport to="body">
    <Transition name="modal-fade">
      <div
        v-if="modelValue"
        class="account-settings-modal-overlay"
        @click.self="handleClose"
      >
        <div class="account-settings-modal-container">
          <div class="account-settings-modal-content">
            <!-- Header -->
            <header class="account-settings-modal-header">
              <div>
                <h2 class="account-settings-modal-title">Account Settings</h2>
                <p class="account-settings-modal-subtitle">Update your personal information and keep your credentials secure.</p>
              </div>
              <button
                type="button"
                class="account-settings-modal-close"
                @click="handleClose"
                aria-label="Close"
              >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
              </button>
            </header>

            <!-- Content -->
    <div class="account-settings-modal__content">
      <section class="account-settings-modal__section">
        <p class="account-settings-modal__eyebrow">Profile</p>
        <div class="account-settings-modal__profile">
          <div class="flex items-center gap-4">
            <div class="relative inline-block">
              <img
                v-if="profilePicture"
                :src="profilePicture"
                alt="Profile"
                class="w-20 h-20 rounded-full object-cover border-2 border-white"
                @error="handleImageError"
              >
              <div
                v-else
                class="w-20 h-20 rounded-full bg-gray-300 flex items-center justify-center text-2xl font-semibold text-gray-600 border-2 border-white"
              >
                {{ userInitials }}
              </div>
              <label
                class="absolute bottom-0 right-0 bg-green-600 text-white rounded-full p-2 cursor-pointer hover:bg-green-700"
                title="Change profile picture"
              >
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                <input
                  type="file"
                  accept="image/*"
                  class="hidden"
                  @change="handleProfilePictureChange"
                >
              </label>
            </div>
            <div>
              <p class="account-settings-modal__profile-title">{{ formData.name || 'Anonymous User' }}</p>
              <p class="account-settings-modal__profile-subtitle">{{ formData.email || 'No email on file' }}</p>
            </div>
          </div>
        </div>

        <div class="account-settings-modal__grid">
          <IonTextField
            v-model="formData.name"
            label="Full Name"
            placeholder="Enter your full name"
            required
          />
          <IonTextField
            v-model="formData.email"
            label="Email"
            placeholder="Enter your email"
            type="email"
            required
          />
          <IonTextField
            v-model="formData.campus"
            label="Campus"
            placeholder="Your campus"
            disabled
          />
        </div>
      </section>

      <section class="account-settings-modal__section">
        <p class="account-settings-modal__eyebrow">Security</p>
        <div class="account-settings-modal__grid account-settings-modal__grid--two">
          <IonTextField
            v-model="formData.newPassword"
            label="New Password"
            placeholder="Enter new password"
            type="password"
          />
          <IonTextField
            v-model="formData.confirmPassword"
            label="Confirm New Password"
            placeholder="Re-enter new password"
            type="password"
          />
        </div>
      </section>
            </div>

            <!-- Footer -->
            <footer class="account-settings-modal-footer">
              <button
                type="button"
                class="account-settings-modal-btn account-settings-modal-btn-secondary"
                @click="handleClose"
              >
                Cancel
              </button>
              <button
                type="button"
                class="account-settings-modal-btn account-settings-modal-btn-primary"
                @click="handleSave"
              >
                Save Changes
              </button>
            </footer>
          </div>
        </div>
      </div>
    </Transition>
  </Teleport>
</template>

<script setup>
import { computed, ref, watch } from 'vue';
import IonTextField from './IonTextField.vue';

const props = defineProps({
  modelValue: {
    type: Boolean,
    default: false,
  },
  user: {
    type: Object,
    required: true,
    default: () => ({
      name: 'John Doe',
      email: 'john.doe@example.com',
      avatar: null,
      campus: 'Main Campus',
    }),
  },
});

const emit = defineEmits(['update:modelValue', 'save']);

const formData = ref({
  name: props.user.name || '',
  email: props.user.email || '',
  campus: props.user.campus || '',
  newPassword: '',
  confirmPassword: '',
});

const getProfilePicturePath = (profilePicture) => {
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

const profilePicture = ref(props.user?.profile_picture ? getProfilePicturePath(props.user.profile_picture) : null);

// Watch for changes in user prop to update profile picture
watch(() => props.user?.profile_picture, (newValue) => {
  profilePicture.value = getProfilePicturePath(newValue);
}, { immediate: true });

const handleImageError = (event) => {
  // If image fails to load, hide it and show placeholder
  event.target.style.display = 'none';
  profilePicture.value = null;
};

const userInitials = computed(() => {
  if (!formData.value.name) return 'U';
  const names = formData.value.name.split(' ');
  if (names.length >= 2) {
    return (names[0][0] + names[names.length - 1][0]).toUpperCase();
  }
  return formData.value.name.substring(0, 2).toUpperCase();
});

const handleClose = () => {
  document.body.classList.remove('modal-open');
  document.body.style.overflow = '';
  emit('update:modelValue', false);
};

const handleSave = () => {
  emit('save', { ...formData.value });
  handleClose();
};

const handleProfilePictureChange = async (event) => {
  const file = event.target.files[0];
  if (!file) return;

  const formData = new FormData();
  formData.append('profile_picture', file);

  try {
    const response = await fetch('/profile/picture', {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
      },
      body: formData,
    });

    if (response.ok) {
      const data = await response.json();
      if (data.profile_picture) {
        profilePicture.value = getProfilePicturePath(data.profile_picture);
        // Update the user prop if it exists
        if (props.user) {
          props.user.profile_picture = data.profile_picture;
        }
        // Show success message
        alert('Profile picture updated successfully!');
      }
      // Don't reload - just update the image
    } else {
      const errorData = await response.json();
      alert(errorData.message || 'Failed to upload profile picture. Please try again.');
    }
  } catch (error) {
    console.error('Error uploading profile picture:', error);
  }
};

watch(() => props.user, (newUser) => {
  if (newUser) {
    formData.value = {
      name: newUser.name || '',
      email: newUser.email || '',
      campus: newUser.campus || '',
      newPassword: '',
      confirmPassword: '',
    };
    // Update profile picture when user prop changes
    profilePicture.value = getProfilePicturePath(newUser.profile_picture);
  }
}, { deep: true, immediate: true });

// Lock body when modal opens
watch(() => props.modelValue, (isOpen) => {
  if (isOpen) {
    document.body.classList.add('modal-open');
    document.body.style.overflow = 'hidden';
  } else {
    document.body.classList.remove('modal-open');
    document.body.style.overflow = '';
  }
});

defineExpose({
  close: handleClose,
});
</script>

<style scoped>
/* Custom Modal Overlay */
.account-settings-modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0, 0, 0, 0.5);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 10000;
  padding: 1rem;
  overflow-x: hidden;
  overflow-y: auto;
}

.account-settings-modal-container {
  width: 100%;
  max-width: 640px;
  max-height: 85vh;
  display: flex;
  align-items: center;
  justify-content: center;
}

.account-settings-modal-content {
  background: white;
  border-radius: 1rem;
  box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
  width: 100%;
  max-width: 100%;
  max-height: 85vh;
  display: flex;
  flex-direction: column;
  overflow: hidden;
  box-sizing: border-box;
}

.account-settings-modal-header {
  display: flex;
  align-items: flex-start;
  justify-content: space-between;
  padding: 1rem 1.25rem;
  border-bottom: 1px solid #e5e7eb;
  flex-shrink: 0;
}

.account-settings-modal-title {
  font-size: 1.25rem;
  font-weight: 700;
  color: #0f172a;
  margin: 0 0 0.375rem 0;
  font-family: 'Kulim Park', sans-serif;
}

.account-settings-modal-subtitle {
  font-size: 0.875rem;
  color: rgba(15, 23, 42, 0.7);
  margin: 0;
}

.account-settings-modal-close {
  background: transparent;
  border: none;
  font-size: 1.25rem;
  color: #6b7280;
  cursor: pointer;
  padding: 0.5rem;
  border-radius: 0.5rem;
  transition: all 0.2s ease;
  width: 2rem;
  height: 2rem;
  display: flex;
  align-items: center;
  justify-content: center;
}

.account-settings-modal-close:hover {
  background: #f3f4f6;
  color: #0f172a;
}

.account-settings-modal-footer {
  display: flex;
  gap: 0.75rem;
  padding: 1rem 1.25rem;
  border-top: 1px solid #e5e7eb;
  flex-shrink: 0;
  justify-content: flex-end;
}

.account-settings-modal-btn {
  padding: 0.625rem 1.5rem;
  border-radius: 0.5rem;
  font-weight: 600;
  font-size: 0.875rem;
  cursor: pointer;
  transition: all 0.2s ease;
  border: none;
}

.account-settings-modal-btn-secondary {
  background: white;
  color: #374151;
  border: 1px solid #d1d5db;
}

.account-settings-modal-btn-secondary:hover {
  background: #f9fafb;
  border-color: #9ca3af;
}

.account-settings-modal-btn-primary {
  background: #22c55e;
  color: white;
}

.account-settings-modal-btn-primary:hover {
  background: #16a34a;
}

.account-settings-modal-avatar-btn {
  padding: 0.5rem 1rem;
  border-radius: 999px;
  font-size: 0.875rem;
  font-weight: 500;
  background: white;
  border: 1px solid #d1d5db;
  color: #374151;
  cursor: pointer;
  transition: all 0.2s ease;
}

.account-settings-modal-avatar-btn:hover {
  background: #f9fafb;
  border-color: #9ca3af;
}

.account-settings-modal-content > div:not(.account-settings-modal-header):not(.account-settings-modal-footer) {
  flex: 1;
  overflow-y: auto;
  overflow-x: hidden;
  padding: 1.25rem;
  box-sizing: border-box;
  min-width: 0;
}

/* Force label colors in account settings modal */
.account-settings-modal-content .modern-ion-field__label {
  color: rgba(15, 23, 42, 0.85) !important;
}

.account-settings-modal-content .modern-ion-field__label ion-label,
.account-settings-modal-content ion-label {
  color: rgba(15, 23, 42, 0.85) !important;
  --color: rgba(15, 23, 42, 0.85) !important;
}

.account-settings-modal-content ion-item ion-label {
  color: rgba(15, 23, 42, 0.85) !important;
  --color: rgba(15, 23, 42, 0.85) !important;
}

.account-settings-modal__content {
  display: flex;
  flex-direction: column;
  gap: 2rem;
}

.account-settings-modal__section {
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
  text-align: left;
  align-items: flex-start;
}

.account-settings-modal__eyebrow {
  text-transform: uppercase;
  letter-spacing: 0.12em;
  font-size: 0.75rem;
  color: rgba(15, 23, 42, 0.5);
  margin: 0;
}

.account-settings-modal__profile {
  display: grid;
  grid-template-columns: auto 1fr auto;
  gap: 1rem;
  align-items: center;
  padding: 0.875rem;
  border-radius: 0.75rem;
  background: rgba(15, 23, 42, 0.03);
  border: 1px solid rgba(15, 23, 42, 0.05);
}

.account-settings-modal__avatar {
  width: 3.5rem;
  height: 3.5rem;
  border-radius: 0.75rem;
  background: rgba(15, 23, 42, 0.06);
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.25rem;
  font-weight: 600;
  color: #0f172a;
  overflow: hidden;
}

.account-settings-modal__avatar img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.account-settings-modal__profile-title {
  font-size: 1.05rem;
  font-weight: 600;
  color: #0f172a;
  margin: 0;
}

.account-settings-modal__profile-subtitle {
  margin: 0.1rem 0 0;
  color: rgba(15, 23, 42, 0.6);
  font-size: 0.9rem;
}

.account-settings-modal__avatar-button::part(native) {
  border-radius: 999px;
}

.account-settings-modal__grid {
  display: grid;
  grid-template-columns: 1fr;
  gap: 1.25rem;
  text-align: left;
  width: 100%;
}

.account-settings-modal__grid--two {
  grid-template-columns: 1fr;
  gap: 1.25rem;
}

@media (min-width: 640px) {
  .account-settings-modal__grid {
    grid-template-columns: repeat(2, 1fr);
  }
  
  .account-settings-modal__grid--two {
    grid-template-columns: repeat(2, 1fr);
  }
}

/* Modal transitions */
.modal-fade-enter-active,
.modal-fade-leave-active {
  transition: opacity 0.2s ease;
}

.modal-fade-enter-from,
.modal-fade-leave-to {
  opacity: 0;
}

.modal-fade-enter-active .account-settings-modal-content,
.modal-fade-leave-active .account-settings-modal-content {
  transition: transform 0.2s ease, opacity 0.2s ease;
}

.modal-fade-enter-from .account-settings-modal-content,
.modal-fade-leave-to .account-settings-modal-content {
  transform: scale(0.95);
  opacity: 0;
}

@media (max-width: 640px) {
  .account-settings-modal-container {
    max-width: 100%;
    padding: 0;
  }
  
  .account-settings-modal-content {
    border-radius: 1rem 1rem 0 0;
    max-height: 95vh;
  }
  
  .account-settings-modal__profile {
    grid-template-columns: 1fr;
    text-align: center;
  }

  .account-settings-modal__avatar {
    margin: 0 auto;
  }

  .account-settings-modal__grid {
    grid-template-columns: 1fr;
  }
  
  .account-settings-modal-footer {
    flex-direction: column-reverse;
  }
  
  .account-settings-modal-btn {
    width: 100%;
  }
}
</style>




