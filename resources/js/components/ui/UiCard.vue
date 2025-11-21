<template>
  <div :class="['ui-card', { 'ui-card--hover': hover, 'ui-card--shadow': shadow }]">
    <div v-if="$slots.header" class="ui-card__header">
      <slot name="header"></slot>
    </div>
    <div class="ui-card__body">
      <slot></slot>
    </div>
    <div v-if="$slots.footer" class="ui-card__footer">
      <slot name="footer"></slot>
    </div>
  </div>
</template>

<script setup>
defineProps({
  hover: {
    type: Boolean,
    default: false,
  },
  shadow: {
    type: Boolean,
    default: true,
  },
});
</script>

<style scoped>
.ui-card {
  background: white;
  border-radius: 0.75rem;
  overflow: hidden;
  transition: all 0.3s ease;
}

.ui-card--shadow {
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1), 0 1px 2px rgba(0, 0, 0, 0.06);
}

.ui-card--hover:hover {
  transform: translateY(-2px);
  box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
}

.ui-card__header {
  padding: 1.5rem;
  border-bottom: 1px solid #e5e7eb;
}

.ui-card__body {
  padding: 1.5rem;
}

.ui-card__footer {
  padding: 1.5rem;
  border-top: 1px solid #e5e7eb;
  background: #f9fafb;
}
</style>




