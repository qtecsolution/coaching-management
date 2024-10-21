import './bootstrap';
import { createApp } from 'vue/dist/vue.esm-bundler.js';
import CreateBatch from './components/Batch/CreateBatch.vue';

const app = createApp({});

// Register components
app.component('create-batch', CreateBatch);

app.mount('#app');

