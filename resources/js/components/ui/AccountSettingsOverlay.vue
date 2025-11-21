<template>
  <Teleport to="body">
    <div class="account-settings-overlay" @click="handleOverlayClick">
      <div class="account-settings-overlay__panel" @click.stop>
        <div class="account-settings-overlay__header">
          <h2 class="account-settings-overlay__title">Account Settings</h2>
          <button
            class="account-settings-overlay__close"
            @click="$emit('close')"
            aria-label="Close"
          >
            <i class="fas fa-times"></i>
          </button>
        </div>

        <div class="account-settings-overlay__content">
          <!-- Profile Section -->
          <div class="account-settings-overlay__section">
            <h3 class="account-settings-overlay__section-title">Profile Information</h3>
            <div class="account-settings-overlay__avatar-section">
              <div class="account-settings-overlay__avatar-large">
                <img
                  v-if="user.avatar"
                  :src="user.avatar"
                  :alt="user.name"
                />
                <div v-else class="account-settings-overlay__avatar-placeholder">
                  {{ userInitials }}
                </div>
              </div>
              <button class="account-settings-overlay__change-avatar-btn">
                Change Avatar
              </button>
            </div>

            <div class="account-settings-overlay__form">
              <div class="account-settings-overlay__form-group">
                <label class="account-settings-overlay__label">Full Name</label>
                <UiInput
                  v-model="formData.name"
                  type="text"
                  placeholder="Enter your full name"
                />
              </div>

              <div class="account-settings-overlay__form-group">
                <label class="account-settings-overlay__label">Email</label>
                <UiInput
                  v-model="formData.email"
                  type="email"
                  placeholder="Enter your email"
                />
              </div>

              <div class="account-settings-overlay__form-group">
                <label class="account-settings-overlay__label">Campus</label>
                <UiInput
                  v-model="formData.campus"
                  type="text"
                  placeholder="Your campus"
                  disabled
                />
              </div>
            </div>
          </div>

          <!-- Security Section -->
          <div class="account-settings-overlay__section">
            <h3 class="account-settings-overlay__section-title">Security</h3>
            <div class="account-settings-overlay__form">
              <div class="account-settings-overlay__form-group">
                <label class="account-settings-overlay__label">Current Password</label>
                <UiInput
                  v-model="formData.currentPassword"
                  type="password"
                  placeholder="Enter current password"
                />
              </div>

              <div class="account-settings-overlay__form-group">
                <label class="account-settings-overlay__label">New Password</label>
                <UiInput
                  v-model="formData.newPassword"
                  type="password"
                  placeholder="Enter new password"
                />
              </div>

              <div class="account-settings-overlay__form-group">
                <label class="account-settings-overlay__label">Confirm New Password</label>
                <UiInput
                  v-model="formData.confirmPassword"
                  type="password"
                  placeholder="Confirm new password"
                />
              </div>
            </div>
          </div>

          <!-- Actions -->
          <div class="account-settings-overlay__actions">
            <UiButton variant="secondary" @click="$emit('close')">
              Cancel
            </UiButton>
            <UiButton variant="primary" @click="handleSave">
              Save Changes
            </UiButton>
          </div>
        </div>
      </div>
    </div>
  </Teleport>
</template>

<script setup>
import { computed, ref, watch } from 'vue';
import UiInput from './UiInput.vue';
import UiButton from './UiButton.vue';

const props = defineProps({
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

const emit = defineEmits(['close', 'save']);

const formData = ref({
  name: props.user.name || '',
  email: props.user.email || '',
  campus: props.user.campus || '',
  currentPassword: '',
  newPassword: '',
  confirmPassword: '',
});

const userInitials = computed(() => {
  if (!formData.value.name) return 'U';
  const names = formData.value.name.split(' ');
  if (names.length >= 2) {
    return (names[0][0] + names[names.length - 1][0]).toUpperCase();
  }
  return formData.value.name.substring(0, 2).toUpperCase();
});

const handleOverlayClick = (event) => {
  if (event.target.classList.contains('account-settings-overlay')) {
    emit('close');
  }
};

const handleSave = () => {
  // Emit save event with form data
  emit('save', { ...formData.value });
  // For now, just close
  emit('close');
};

watch(() => props.user, (newUser) => {
  formData.value = {
    name: newUser.name || '',
    email: newUser.email || '',
    campus: newUser.campus || '',
    currentPassword: '',
    newPassword: '',
    confirmPassword: '',
  };
}, { deep: true, immediate: true });
</script>

<style scoped>
.account-settings-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0, 0, 0, 0.5);
  backdrop-filter: blur(4px);
  z-index: 1000;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 1rem;
  animation: fadeIn 0.3s ease;
}

@keyframes fadeIn {
  from {
    opacity: 0;
  }
  to {
    opacity: 1;
  }
}

.account-settings-overlay__panel {
  background: white;
  border-radius: 1rem;
  width: 100%;
  max-width: 600px;
  max-height: 90vh;
  display: flex;
  flex-direction: column;
  box-shadow: 0 20px 50px rgba(0, 0, 0, 0.3);
  animation: slideUp 0.3s ease;
  overflow: hidden;
}

@keyframes slideUp {
  from {
    transform: translateY(20px);
    opacity: 0;
  }
  to {
    transform: translateY(0);
    opacity: 1;
  }
}

.account-settings-overlay__header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 1.5rem;
  border-bottom: 1px solid #e5e7eb;
}

.account-settings-overlay__title {
  font-size: 1.5rem;
  font-weight: 600;
  color: #1f2937;
  font-family: 'Kulim Park', sans-serif;
}

.account-settings-overlay__close {
  width: 2rem;
  height: 2rem;
  border-radius: 0.5rem;
  border: none;
  background: #f3f4f6;
  color: #6b7280;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: all 0.2s ease;
}

.account-settings-overlay__close:hover {
  background: #e5e7eb;
  color: #1f2937;
}

.account-settings-overlay__content {
  flex: 1;
  overflow-y: auto;
  padding: 1.5rem;
}

.account-settings-overlay__content::-webkit-scrollbar {
  width: 8px;
}

.account-settings-overlay__content::-webkit-scrollbar-thumb {
  background: #d1d5db;
  border-radius: 4px;
}

.account-settings-overlay__content::-webkit-scrollbar-thumb:hover {
  background: #9ca3af;
}

.account-settings-overlay__section {
  margin-bottom: 2rem;
}

.account-settings-overlay__section:last-of-type {
  margin-bottom: 0;
}

.account-settings-overlay__section-title {
  font-size: 1.125rem;
  font-weight: 600;
  color: #1f2937;
  margin-bottom: 1rem;
  font-family: 'Kulim Park', sans-serif;
}

.account-settings-overlay__avatar-section {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 1rem;
  margin-bottom: 1.5rem;
}

.account-settings-overlay__avatar-large {
  width: 6rem;
  height: 6rem;
  border-radius: 0.75rem;
  background: white;
  border: 2px solid #e5e7eb;
  display: flex;
  align-items: center;
  justify-content: center;
  overflow: hidden;
}

.account-settings-overlay__avatar-large img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.account-settings-overlay__avatar-placeholder {
  width: 100%;
  height: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
  color: #000;
  font-weight: 600;
  font-size: 1.5rem;
}

.account-settings-overlay__change-avatar-btn {
  padding: 0.5rem 1rem;
  background: #f3f4f6;
  border: 1px solid #d1d5db;
  border-radius: 0.5rem;
  color: #374151;
  font-size: 0.875rem;
  cursor: pointer;
  transition: all 0.2s ease;
}

.account-settings-overlay__change-avatar-btn:hover {
  background: #e5e7eb;
  border-color: #9ca3af;
}

.account-settings-overlay__form {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.account-settings-overlay__form-group {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

.account-settings-overlay__label {
  font-size: 0.875rem;
  font-weight: 500;
  color: #374151;
}

.account-settings-overlay__actions {
  display: flex;
  gap: 0.75rem;
  justify-content: flex-end;
  margin-top: 2rem;
  padding-top: 1.5rem;
  border-top: 1px solid #e5e7eb;
}

@media (max-width: 640px) {
  .account-settings-overlay__panel {
    max-height: 95vh;
    border-radius: 0.75rem;
  }

  .account-settings-overlay__header {
    padding: 1rem;
  }

  .account-settings-overlay__content {
    padding: 1rem;
  }

  .account-settings-overlay__actions {
    flex-direction: column-reverse;
  }

  .account-settings-overlay__actions button {
    width: 100%;
  }
}
</style>




