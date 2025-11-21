<template>
  <button
    :type="type"
    :disabled="disabled"
    :class="[
      'ui-button',
      `ui-button--${variant}`,
      {
        'ui-button--loading': loading,
        'ui-button--full-width': fullWidth,
      }
    ]"
    @click="handleClick"
  >
    <span v-if="loading" class="ui-button__spinner"></span>
    <slot v-if="!loading"></slot>
    <span v-else-if="loadingText">{{ loadingText }}</span>
  </button>
</template>

<script setup>
const props = defineProps({
  variant: {
    type: String,
    default: 'primary',
    validator: (value) => ['primary', 'secondary', 'danger', 'outline'].includes(value),
  },
  type: {
    type: String,
    default: 'button',
  },
  disabled: {
    type: Boolean,
    default: false,
  },
  loading: {
    type: Boolean,
    default: false,
  },
  loadingText: {
    type: String,
    default: '',
  },
  fullWidth: {
    type: Boolean,
    default: false,
  },
});

const emit = defineEmits(['click']);

const handleClick = (event) => {
  if (!props.disabled && !props.loading) {
    emit('click', event);
  }
};
</script>

<style scoped>
.ui-button {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 0.5rem;
  padding: 0.75rem 1.5rem;
  font-size: 0.875rem;
  font-weight: 500;
  line-height: 1.5;
  border-radius: 0.5rem;
  border: none;
  cursor: pointer;
  transition: all 0.2s ease;
  font-family: 'Inter', sans-serif;
  white-space: nowrap;
}

.ui-button:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

.ui-button--full-width {
  width: 100%;
}

/* Primary Variant */
.ui-button--primary {
  background: #22c55e;
  color: white;
}

.ui-button--primary:hover:not(:disabled) {
  background: #16a34a;
}

.ui-button--primary:active:not(:disabled) {
  background: #15803d;
}

/* Secondary Variant */
.ui-button--secondary {
  background: #f3f4f6;
  color: #374151;
}

.ui-button--secondary:hover:not(:disabled) {
  background: #e5e7eb;
}

.ui-button--secondary:active:not(:disabled) {
  background: #d1d5db;
}

/* Danger Variant */
.ui-button--danger {
  background: #ef4444;
  color: white;
}

.ui-button--danger:hover:not(:disabled) {
  background: #dc2626;
}

.ui-button--danger:active:not(:disabled) {
  background: #b91c1c;
}

/* Outline Variant */
.ui-button--outline {
  background: transparent;
  color: #22c55e;
  border: 1px solid #22c55e;
}

.ui-button--outline:hover:not(:disabled) {
  background: #22c55e;
  color: white;
}

.ui-button--outline:active:not(:disabled) {
  background: #16a34a;
  border-color: #16a34a;
}

.ui-button__spinner {
  width: 1rem;
  height: 1rem;
  border: 2px solid currentColor;
  border-top-color: transparent;
  border-radius: 50%;
  animation: spin 0.6s linear infinite;
}

@keyframes spin {
  to {
    transform: rotate(360deg);
  }
}
</style>




