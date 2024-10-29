import './bootstrap';
import { createApp } from 'vue/dist/vue.esm-bundler.js';
import CreateBatch from './components/Batch/CreateBatch.vue';
import EditBatch from './components/Batch/EditBatch.vue';
import ShowBatch from './components/Batch/ShowBatch.vue';

// Create app
const app = createApp({});

// Register components
app.component('create-batch', CreateBatch);
app.component('edit-batch', EditBatch);
app.component('show-batch', ShowBatch);

// Mount app
app.mount('#app');

