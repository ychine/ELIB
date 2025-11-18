import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

let activeRequests = 0;
let pendingDispatch;

const dispatchLoadingEvent = () => {
    window.dispatchEvent(
        new CustomEvent('app:loading', {
            detail: {
                active: activeRequests > 0,
                total: activeRequests,
            },
        }),
    );
};

const scheduleDispatch = () => {
    if (pendingDispatch) return;
    pendingDispatch = requestAnimationFrame(() => {
        dispatchLoadingEvent();
        pendingDispatch = null;
    });
};

window.addEventListener('beforeunload', () => {
    activeRequests = 0;
    dispatchLoadingEvent();
});

axios.interceptors.request.use(
    (config) => {
        activeRequests += 1;
        scheduleDispatch();
        return config;
    },
    (error) => {
        activeRequests = Math.max(0, activeRequests - 1);
        scheduleDispatch();
        return Promise.reject(error);
    },
);

axios.interceptors.response.use(
    (response) => {
        activeRequests = Math.max(0, activeRequests - 1);
        scheduleDispatch();
        return response;
    },
    (error) => {
        activeRequests = Math.max(0, activeRequests - 1);
        scheduleDispatch();
        return Promise.reject(error);
    },
);
