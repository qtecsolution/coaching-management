import './bootstrap';
import { createApp } from 'vue/dist/vue.esm-bundler.js';
import CreateBatch from './components/Batch/CreateBatch.vue';

// Create app
const app = createApp({});

// Register components
app.component('create-batch', CreateBatch);

// Mount app
app.mount('#app');

