// Navigation interceptor for loading bar
// This intercepts link clicks and form submissions to show loading bar

(function() {
  'use strict';

  let isNavigating = false;

  // Dispatch loading start event
  const startLoading = () => {
    if (!isNavigating) {
      isNavigating = true;
      const event = new CustomEvent('app:loading', { 
        detail: { active: true },
        bubbles: true,
        cancelable: true
      });
      window.dispatchEvent(event);
      document.dispatchEvent(event);
      console.log('Loading started');
    }
  };

  // Dispatch loading end event
  const stopLoading = () => {
    if (isNavigating) {
      isNavigating = false;
      const event = new CustomEvent('app:loading', { 
        detail: { active: false },
        bubbles: true,
        cancelable: true
      });
      window.dispatchEvent(event);
      document.dispatchEvent(event);
      console.log('Loading stopped');
    }
  };

  // Intercept link clicks
  document.addEventListener('click', (e) => {
    const link = e.target.closest('a[href]');
    if (!link) return;

    const href = link.getAttribute('href');
    if (!href || href === '#' || href.startsWith('javascript:') || href.startsWith('mailto:') || href.startsWith('tel:')) {
      return;
    }

    // Check if it's an internal link
    try {
      const url = new URL(href, window.location.origin);
      const isInternal = url.origin === window.location.origin;
      
      if (isInternal && !link.hasAttribute('download') && link.target !== '_blank') {
        startLoading();
        
        // If navigation doesn't happen (e.g., prevented), stop loading after a delay
        setTimeout(() => {
          if (isNavigating) {
            // Check if we're still on the same page
            if (window.location.href === url.href) {
              stopLoading();
            }
          }
        }, 100);
      }
    } catch (e) {
      // Invalid URL, ignore
    }
  }, true);

  // Intercept form submissions (both GET and POST forms that cause navigation)
  document.addEventListener('submit', (e) => {
    const form = e.target;
    // Show loading bar for all form submissions
    if (form.tagName === 'FORM') {
      startLoading();
      // Don't prevent form submission - let it proceed normally
    }
  }, true);

  // Don't interfere with initial page load - let the component handle it
  // Only handle navigation-triggered loading, not initial page load
  // The component will track the actual page load progress

  // Handle browser back/forward
  window.addEventListener('popstate', () => {
    startLoading();
    setTimeout(stopLoading, 100);
  });

  // Handle page visibility (for when user switches tabs during navigation)
  document.addEventListener('visibilitychange', () => {
    if (!document.hidden && isNavigating) {
      // Page became visible, check if navigation completed
      setTimeout(stopLoading, 100);
    }
  });
})();


