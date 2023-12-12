<script setup>
defineProps({
    value: {
        type: String,
    },
    class: {
        type: String,
    }
});
</script>

<template>
    <label class={{class}} >
        <span v-if="value">{{ value }}</span>
        <span v-else><slot /></span>
    </label>
</template>
