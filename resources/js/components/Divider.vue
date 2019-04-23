<template>
    <div class="segment divider-item">
        <slot></slot>
    </div>
</template>

<script>
    export default {
        name: "Divider"
    }
</script>

<style scoped>
    .divider-item {
        background: rgba(255, 143, 110, 0.44);
        color: rgba(0, 0, 0, .6);
    }

    .segment {
        margin-top: -16px;
        padding: 4px;
    }
</style>
