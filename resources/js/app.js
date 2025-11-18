import './bootstrap';
import { createApp } from 'vue';
import GlobalLoadingBar from './components/GlobalLoadingBar.vue';

const loaderRoot = document.getElementById('global-loading-root');

if (loaderRoot) {
    createApp(GlobalLoadingBar).mount(loaderRoot);
}
