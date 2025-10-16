import './bootstrap';
import { createApp } from 'vue';

import ExampleComponent from './components/ExampleComponent.vue';

const app = createApp({});

// Register components
app.component('example-component', ExampleComponent);

// Mount the app to an element in your Blade file
app.mount('#app');
