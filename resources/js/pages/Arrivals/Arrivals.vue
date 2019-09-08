<template>
    <div id="arrivals">
       <BreadCrumb/>
        <router-view/>
    </div>
</template>

<script>
    import BreadCrumb from "@/components/layouts/BreadCrumb";
    export default {
        name: "Arrivals",
       components: {BreadCrumb}
    }
</script>

<style scoped>

</style>
