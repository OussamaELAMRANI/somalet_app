<template lang="pug">
    #loading_page.segment
        #loading_core
            half-circle-spinner(color="#FF952F", :size="80")
</template>

<script>
    // import SelfBuildingSquareSpinner from "epic-spinners/src/components/lib/SelfBuildingSquareSpinner";
    import HalfCircleSpinner from "epic-spinners/src/components/lib/HalfCircleSpinner";

    export default {
        name: "LoadingPage",
        components: {HalfCircleSpinner}
    }
</script>

<style scoped>
    #loading_page {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 90vh;
    }

    #loading_core {
        max-width: 200px;
        text-align: center;
        background: rgba(255, 255, 255, 0.5);
        margin: .25rem;
        padding: .25rem;
        align-self: center;

    }
</style>
