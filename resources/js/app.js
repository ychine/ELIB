import './bootstrap';
import './navigation-interceptor';
import { createApp } from 'vue';
import GlobalLoadingBar from './components/GlobalLoadingBar.vue';
import UniversalSidebar from './components/ui/UniversalSidebar.vue';
import AccountSettingsOverlay from './components/ui/AccountSettingsOverlay.vue';
import UiInput from './components/ui/UiInput.vue';
import UiButton from './components/ui/UiButton.vue';
import UiTable from './components/ui/UiTable.vue';
import UiCard from './components/ui/UiCard.vue';

// Initialize Global Loading Bar - ensure it's always available
function initGlobalLoadingBar() {
    const loaderRoot = document.getElementById('global-loading-root');
    if (loaderRoot && !loaderRoot._vueApp) {
        console.log('Initializing GlobalLoadingBar component');
        loaderRoot._vueApp = createApp(GlobalLoadingBar);
        loaderRoot._vueApp.mount(loaderRoot);
    } else if (!loaderRoot) {
        console.warn('global-loading-root element not found');
    }
}

// Try to initialize immediately
initGlobalLoadingBar();

// Also try on DOMContentLoaded in case the element isn't ready yet
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initGlobalLoadingBar);
}

// Initialize Universal Sidebar if root exists
const sidebarRoot = document.getElementById('universal-sidebar-root');
if (sidebarRoot) {
    // Get CSRF token from meta tag or input
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || 
                     document.querySelector('input[name="_token"]')?.value || '';
    
    const sidebarApp = createApp(UniversalSidebar, {
        menuItems: JSON.parse(sidebarRoot.dataset.menuItems || '[]'),
        activeRoute: sidebarRoot.dataset.activeRoute || '',
        user: JSON.parse(sidebarRoot.dataset.user || '{}'),
        logos: JSON.parse(sidebarRoot.dataset.logos || '{}'),
        defaultExpanded: sidebarRoot.dataset.defaultExpanded === 'true',
        csrfToken: csrfToken,
        logoutUrl: sidebarRoot.dataset.logoutUrl || '/logout',
        role: sidebarRoot.dataset.role || '',
    });
    sidebarApp.mount(sidebarRoot);
}

// Export components for use in other Vue apps
export {
    UniversalSidebar,
    AccountSettingsOverlay,
    UiInput,
    UiButton,
    UiTable,
    UiCard,
};
