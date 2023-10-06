<script setup>
    defineProps({
        show: {
            type: Boolean,
            default: false
        }
    });
</script>
<template>
    <div v-if="show" class="spinner-grow" role="status">
        <span class="visually-hidden">Loading...</span>
    </div>
</template>
