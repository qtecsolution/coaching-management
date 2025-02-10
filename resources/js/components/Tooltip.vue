<template>
    <span
        ref="tooltipEl"
        data-bs-toggle="tooltip"
        :data-bs-placement="position"
        data-bs-custom-class="custom-tooltip"
        :title="message"
    >
        <i class="bi bi-info-circle"></i>
    </span>
</template>

<script setup>
import { onMounted, ref } from "vue";
import { Tooltip } from "bootstrap";

const { position, message } = defineProps(["position", "message"]);
const tooltipEl = ref(null);

onMounted(() => {
    if (tooltipEl.value) {
        new Tooltip(tooltipEl.value);
    }
});
</script>

<style>
span {
    margin-left: 5px;
}
</style>