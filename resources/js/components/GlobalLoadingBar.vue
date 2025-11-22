<template>
  <div class="global-loading-bar" :class="{ 'is-loading': isLoading }">
    <div class="loading-bar" :style="{ width: progress + '%' }"></div>
  </div>
</template>

<script>
export default {
  name: 'GlobalLoadingBar',
  data() {
    return {
      isLoading: true, // Start as loading to show on page load
      progress: 0,
      progressInterval: null,
      progressTimeout: null
    }
  },
  mounted() {
    // Always track initial page load
    this.trackPageLoad()
    
    // Listen to app:loading events from navigation interceptor (for link clicks, etc.)
    this.handleLoadingEvent = (event) => {
      const active = event.detail?.active ?? event.detail ?? false
      if (active) {
        // Start progress for navigation
        this.startProgress()
      } else {
        // Complete progress when navigation finishes
        // Wait a bit to ensure page is rendered
        setTimeout(() => {
          this.completeProgress()
        }, 200)
      }
    }
    window.addEventListener('app:loading', this.handleLoadingEvent)
    document.addEventListener('app:loading', this.handleLoadingEvent)
  },
  beforeUnmount() {
    // Clean up event listener
    if (this.handleLoadingEvent) {
      window.removeEventListener('app:loading', this.handleLoadingEvent)
      document.removeEventListener('app:loading', this.handleLoadingEvent)
    }
    this.clearProgress()
  },
  methods: {
    trackPageLoad() {
      this.isLoading = true
      this.progress = 0
      
      if (document.readyState === 'loading') {
        // Page is still loading - track real progress
        this.progress = 10
        
        // Track DOM parsing progress gradually
        let progressInterval = setInterval(() => {
          if (document.readyState === 'loading') {
            // Gradually increase while loading DOM (10% to 40%)
            if (this.progress < 40) {
              this.progress = Math.min(40, this.progress + 1.5)
            }
          } else {
            clearInterval(progressInterval)
          }
        }, 100)
        
        // DOMContentLoaded - 60%
        document.addEventListener('DOMContentLoaded', () => {
          clearInterval(progressInterval)
          this.progress = 60
        }, { once: true })
        
        // Window load - 90% (all resources loaded)
        window.addEventListener('load', () => {
          clearInterval(progressInterval)
          this.progress = 90
          // Wait a bit to ensure everything is rendered before completing
          setTimeout(() => {
            this.completeProgress()
          }, 300)
        }, { once: true })
      } else if (document.readyState === 'interactive') {
        // DOMContentLoaded already fired, but page not fully loaded
        this.progress = 60
        window.addEventListener('load', () => {
          this.progress = 90
          setTimeout(() => {
            this.completeProgress()
          }, 300)
        }, { once: true })
      } else {
        // Page already complete - don't show loading bar
        this.isLoading = false
        this.progress = 0
      }
    },
    trackReadyStateProgress() {
      // If page is still loading, track it
      if (document.readyState === 'loading') {
        // Start at 10%
        this.progress = 10
        
        // DOMContentLoaded - 50%
        document.addEventListener('DOMContentLoaded', () => {
          this.progress = 50
        }, { once: true })
        
        // Window load - 90%
        window.addEventListener('load', () => {
          this.progress = 90
          // Complete after a brief moment
          setTimeout(() => {
            this.completeProgress()
          }, 300)
        }, { once: true })
      } else if (document.readyState === 'interactive') {
        // DOMContentLoaded already fired, but page not fully loaded
        this.progress = 50
        window.addEventListener('load', () => {
          this.progress = 90
          setTimeout(() => {
            this.completeProgress()
          }, 300)
        }, { once: true })
      } else {
        // Page already fully loaded - show briefly then hide
        this.progress = 100
        setTimeout(() => {
          this.completeProgress()
        }, 500)
      }
    },
    startProgress() {
      this.clearProgress()
      this.isLoading = true
      this.progress = 10
      
      // Simulate progress from 10% to 90% gradually
      let currentProgress = 10
      const targetProgress = 90
      
      this.progressInterval = setInterval(() => {
        if (currentProgress < targetProgress) {
          // Gradually increase progress
          const increment = Math.max(1, (targetProgress - currentProgress) * 0.15)
          currentProgress = Math.min(targetProgress, currentProgress + increment)
          this.progress = Math.round(currentProgress)
        } else {
          // Stop at 90% and wait for completion
          clearInterval(this.progressInterval)
          this.progressInterval = null
        }
      }, 30)
    },
    completeProgress() {
      this.clearProgress()
      // Jump to 100% and then hide
      this.progress = 100
      this.progressTimeout = setTimeout(() => {
        this.isLoading = false
        this.progress = 0
      }, 400)
    },
    clearProgress() {
      if (this.progressInterval) {
        clearInterval(this.progressInterval)
        this.progressInterval = null
      }
      if (this.progressTimeout) {
        clearTimeout(this.progressTimeout)
        this.progressTimeout = null
      }
    },
    show() {
      this.startProgress()
    },
    hide() {
      this.completeProgress()
    }
  }
}
</script>

<style scoped>
.global-loading-bar {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 4px;
  z-index: 99999;
  overflow: hidden;
  pointer-events: none;
  opacity: 0;
  transition: opacity 0.3s ease;
  background: transparent;
}

.global-loading-bar.is-loading {
  opacity: 1;
}

.loading-bar {
  height: 100%;
  width: 0%;
  background: linear-gradient(90deg, #4ade80, #22c55e, #16a34a, #15803d);
  box-shadow: 0 0 15px rgba(34, 197, 94, 0.8), 0 0 30px rgba(34, 197, 94, 0.4);
  transition: width 0.2s linear;
}
</style>
