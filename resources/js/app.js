import './bootstrap';
import './navigation-interceptor';
import { createApp as createVueApp, h } from 'vue';
import { createInertiaApp, router as inertiaRouter } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import '@ionic/vue/css/core.css';
import '@ionic/vue/css/normalize.css';
import '@ionic/vue/css/structure.css';
import '@ionic/vue/css/typography.css';
import '@ionic/vue/css/padding.css';
import '@ionic/vue/css/float-elements.css';
import '@ionic/vue/css/text-alignment.css';
import '@ionic/vue/css/text-transformation.css';
import '@ionic/vue/css/flex-utils.css';
import '@ionic/vue/css/display.css';
import GlobalLoadingBar from './components/GlobalLoadingBar.vue';
import UniversalSidebar from './components/ui/UniversalSidebar.vue';
import MobileBottomNav from './components/ui/MobileBottomNav.vue';
import AccountSettingsOverlay from './components/ui/AccountSettingsOverlay.vue';
import UiInput from './components/ui/UiInput.vue';
import UiButton from './components/ui/UiButton.vue';
import UiTable from './components/ui/UiTable.vue';
import UiCard from './components/ui/UiCard.vue';
import IonTextField from './components/ui/IonTextField.vue';
import ModernIonModal from './components/ui/ModernIonModal.vue';

const appName = import.meta.env.VITE_APP_NAME || 'ISU StudyGo';

function dispatchLoadingEvent(active) {
    const event = new CustomEvent('app:loading', {
        detail: { active },
        bubbles: true,
        cancelable: true,
    });
    window.dispatchEvent(event);
    document.dispatchEvent(event);
}

inertiaRouter.on('start', () => {
    dispatchLoadingEvent(true);
});
inertiaRouter.on('finish', () => {
    // Small delay to ensure page is rendered
    setTimeout(() => {
        dispatchLoadingEvent(false);
    }, 100);
});
inertiaRouter.on('error', () => {
    setTimeout(() => {
        dispatchLoadingEvent(false);
    }, 100);
});

function initInertia() {
    if (!document.getElementById('app')) {
        return;
    }

    createInertiaApp({
        title: (title) => (title ? `${title} | ${appName}` : appName),
        resolve: (name) => resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob('./Pages/**/*.vue')
        ),
        setup({ el, App, props, plugin }) {
            const vueApp = createVueApp({ render: () => h(App, props) });
            vueApp.use(plugin);

            vueApp.component('UniversalSidebar', UniversalSidebar);
            vueApp.component('MobileBottomNav', MobileBottomNav);
            vueApp.component('AccountSettingsOverlay', AccountSettingsOverlay);
            vueApp.component('UiInput', UiInput);
            vueApp.component('UiButton', UiButton);
            vueApp.component('UiTable', UiTable);
            vueApp.component('UiCard', UiCard);
            vueApp.component('IonTextField', IonTextField);
            vueApp.component('ModernIonModal', ModernIonModal);

            vueApp.mount(el);
        },
        progress: false,
    });
}

function initGlobalLoadingBar() {
    const loaderRoot = document.getElementById('global-loading-root');
    if (loaderRoot && !loaderRoot._vueApp) {
        loaderRoot._vueApp = createVueApp(GlobalLoadingBar);
        loaderRoot._vueApp.mount(loaderRoot);
    }
}

function initStandaloneSidebar() {
    const sidebarRoot = document.getElementById('universal-sidebar-root');
    if (!sidebarRoot) {
        return;
    }

    // Prevent double initialization
    if (sidebarRoot._vueApp) {
        return;
    }

    // Force sidebar to stay fixed - AGGRESSIVE
    function ensureSidebarFixed() {
        if (sidebarRoot) {
            // Move sidebar to body if it's inside a scrolling container
            const parent = sidebarRoot.parentElement;
            if (parent && parent !== document.body) {
                // Check if parent might scroll or is a flex container
                const parentStyle = window.getComputedStyle(parent);
                const isScrollingContainer = parentStyle.overflow === 'auto' || 
                    parentStyle.overflow === 'scroll' || 
                    parentStyle.overflowY === 'auto' || 
                    parentStyle.overflowY === 'scroll' ||
                    parent.classList.contains('flex') ||
                    parent.classList.contains('container') ||
                    parent.classList.contains('wrapper');
                
                if (isScrollingContainer) {
                    // Move to body to escape scrolling context
                    document.body.appendChild(sidebarRoot);
                }
            }
            // Force fixed positioning
            sidebarRoot.style.cssText = 'position: fixed !important; top: 0 !important; left: 0 !important; z-index: 9999 !important; margin: 0 !important; padding: 0 !important; transform: none !important; width: auto !important; height: 100vh !important;';
        }
        const sidebar = document.querySelector('.universal-sidebar');
        if (sidebar) {
            sidebar.style.cssText = 'position: fixed !important; top: 0 !important; left: 0 !important; z-index: 9999 !important; margin: 0 !important; transform: none !important;';
        }
    }

    // Ensure fixed positioning before mounting
    ensureSidebarFixed();

    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content
        || document.querySelector('input[name="_token"]')?.value
        || '';

    const sidebarApp = createVueApp(UniversalSidebar, {
        menuItems: JSON.parse(sidebarRoot.dataset.menuItems || '[]'),
        activeRoute: sidebarRoot.dataset.activeRoute || '',
        user: JSON.parse(sidebarRoot.dataset.user || '{}'),
        logos: JSON.parse(sidebarRoot.dataset.logos || '{}'),
        defaultExpanded: sidebarRoot.dataset.defaultExpanded === 'true',
        csrfToken,
        logoutUrl: sidebarRoot.dataset.logoutUrl || '/logout',
        role: sidebarRoot.dataset.role || '',
    });

    sidebarRoot._vueApp = sidebarApp;
    sidebarApp.mount(sidebarRoot);

    // Ensure fixed after mount and on scroll
    setTimeout(ensureSidebarFixed, 100);
    window.addEventListener('scroll', ensureSidebarFixed, { passive: true, capture: true });
    window.addEventListener('resize', ensureSidebarFixed);
}

function initStandaloneBottomNav() {
    const bottomNavRoot = document.getElementById('mobile-bottom-nav-root');
    if (!bottomNavRoot) {
        console.warn('Mobile bottom nav root element not found');
        return;
    }

    // Prevent double initialization
    if (bottomNavRoot._vueApp) {
        console.log('Mobile bottom nav already initialized');
        return;
    }

    try {
        const menuItems = JSON.parse(bottomNavRoot.dataset.menuItems || '[]');
        const activeRoute = bottomNavRoot.dataset.activeRoute || '';
        const profile = JSON.parse(bottomNavRoot.dataset.profile || '{}');
        const profileMenu = JSON.parse(bottomNavRoot.dataset.profileMenu || '[]');
        const logoutUrl = bottomNavRoot.dataset.logoutUrl || '/logout';

        // Initialize silently

        const bottomNavApp = createVueApp(MobileBottomNav, {
            menuItems,
            activeRoute,
            profile,
            profileMenu,
            logoutUrl,
        });

        bottomNavRoot._vueApp = bottomNavApp;
        bottomNavApp.mount(bottomNavRoot);
        console.log('Mobile bottom nav Vue app mounted successfully');
    } catch (error) {
        console.error('Error initializing mobile bottom nav:', error);
        console.error('Stack:', error.stack);
    }
}

// Make function available globally for inline scripts
if (typeof window !== 'undefined') {
    window.initStandaloneBottomNav = initStandaloneBottomNav;
}

// Prioritize sidebar loading - it's critical for UX
// Try to initialize sidebar immediately, even before DOM is ready
function prioritizeSidebar() {
    const sidebarRoot = document.getElementById('universal-sidebar-root');
    if (sidebarRoot && !sidebarRoot._vueApp) {
        try {
            initStandaloneSidebar();
        } catch (e) {
            console.warn('Early sidebar init failed, will retry:', e);
        }
    }
}

// Try immediately
prioritizeSidebar();

// Function to initialize bottom nav with retries
function initBottomNavWithRetry(retries = 20, delay = 100) {
    const bottomNavRoot = document.getElementById('mobile-bottom-nav-root');
    if (bottomNavRoot) {
        if (!bottomNavRoot._vueApp) {
            try {
                initStandaloneBottomNav();
            } catch (error) {
                console.error('Error initializing bottom nav:', error);
                if (retries > 0) {
                    setTimeout(() => {
                        initBottomNavWithRetry(retries - 1, delay);
                    }, delay);
                }
            }
        }
        return; // Found and initialized, stop retrying
    }
    
    // Not found yet, retry if we have retries left
    if (retries > 0) {
        setTimeout(() => {
            initBottomNavWithRetry(retries - 1, delay);
        }, delay);
    }
}

// Use MutationObserver to watch for the element
function watchForBottomNav() {
    const observer = new MutationObserver((mutations) => {
        const bottomNavRoot = document.getElementById('mobile-bottom-nav-root');
        if (bottomNavRoot && !bottomNavRoot._vueApp) {
            initStandaloneBottomNav();
            observer.disconnect();
        }
    });

    if (document.body) {
        observer.observe(document.body, {
            childList: true,
            subtree: true,
        });
    }

    // Also try immediately
    setTimeout(() => {
        const bottomNavRoot = document.getElementById('mobile-bottom-nav-root');
        if (bottomNavRoot && !bottomNavRoot._vueApp) {
            initStandaloneBottomNav();
        }
        observer.disconnect();
    }, 100);
}

// Initialize on DOM ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        if (!document.getElementById('universal-sidebar-root')?._vueApp) {
            initStandaloneSidebar();
        }
        watchForBottomNav();
        initGlobalLoadingBar();
    });
} else {
    if (!document.getElementById('universal-sidebar-root')?._vueApp) {
        initStandaloneSidebar();
    }
    watchForBottomNav();
    initGlobalLoadingBar();
}

// Fallback: try on window load
window.addEventListener('load', () => {
    const bottomNavRoot = document.getElementById('mobile-bottom-nav-root');
    if (bottomNavRoot && !bottomNavRoot._vueApp) {
        initStandaloneBottomNav();
    }
});

// Initialize Inertia (less critical, can load after sidebar)
// Delay Inertia slightly to prioritize sidebar
setTimeout(() => {
    initInertia();
}, 0);

// Make Inertia router available globally for non-Inertia pages
if (typeof window !== 'undefined') {
    // Wait for Inertia to be fully initialized
    const setupInertia = () => {
        if (inertiaRouter && typeof inertiaRouter.visit === 'function') {
            window.Inertia = inertiaRouter;
        } else {
            // Retry if not ready yet
            setTimeout(setupInertia, 100);
        }
    };
    setupInertia();
}

export {
    UniversalSidebar,
    MobileBottomNav,
    AccountSettingsOverlay,
    UiInput,
    UiButton,
    UiTable,
    UiCard,
    IonTextField,
    ModernIonModal,
};
