<template>
  <AppLayout :title="title" :content-padding-classes="contentPaddingClasses">
    <slot />
  </AppLayout>
</template>

<script setup>
import AppLayout from './AppLayout.vue';

defineProps({
  title: {
    type: String,
    required: true,
  },
  contentPaddingClasses: {
    type: String,
    default: '',
  },
});
</script>

