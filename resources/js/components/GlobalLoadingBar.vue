<template>
  <transition name="loader-fade">
    <div
      v-if="isVisible"
      class="global-loader fixed top-0 left-0 right-0 h-1.5 z-[100]"
      role="status"
      aria-live="polite"
    >
      <span
        class="block h-full rounded-full bg-gradient-to-r from-emerald-400 via-lime-300 to-green-600 shadow-lg shadow-emerald-500/30"
        :style="{ width: progress + '%' }"
      ></span>
    </div>
  </transition>
</template>

<script setup>
import { onBeforeUnmount, onMounted, ref } from 'vue';

const isVisible = ref(false);
const progress = ref(0);

let bumpTimer = null;
let hideTimer = null;

const start = () => {
  if (hideTimer) {
    clearTimeout(hideTimer);
    hideTimer = null;
  }
  if (!isVisible.value) {
    isVisible.value = true;
    progress.value = 8;
  }
  if (!bumpTimer) {
    bumpTimer = setInterval(() => {
      if (progress.value < 90) {
        const increment = Math.max(1, (100 - progress.value) * 0.05);
        progress.value = Math.min(90, progress.value + increment);
      }
    }, 250);
  }
};

const finish = () => {
  progress.value = 100;
  if (bumpTimer) {
    clearInterval(bumpTimer);
    bumpTimer = null;
  }
  hideTimer = setTimeout(() => {
    isVisible.value = false;
    progress.value = 0;
  }, 350);
};

const handleLoadingEvent = (event) => {
  const isActive = Boolean(event?.detail?.active);
  if (isActive) {
    start();
  } else {
    finish();
  }
};

const showUntilLoaded = () => {
  if (document.readyState !== 'complete') {
    start();
    window.addEventListener(
      'load',
      () => {
        finish();
      },
      { once: true },
    );
  }
};

onMounted(() => {
  window.addEventListener('app:loading', handleLoadingEvent);
  showUntilLoaded();
});

onBeforeUnmount(() => {
  window.removeEventListener('app:loading', handleLoadingEvent);
  if (bumpTimer) clearInterval(bumpTimer);
  if (hideTimer) clearTimeout(hideTimer);
});
</script>

<style scoped>
.loader-fade-enter-active,
.loader-fade-leave-active {
  transition: opacity 200ms ease, transform 250ms ease;
}

.loader-fade-enter-from,
.loader-fade-leave-to {
  opacity: 0;
  transform: translateY(-4px);
}
</style>


