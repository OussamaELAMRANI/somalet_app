<template>
    <div id="users">
        <BreadCrumb/>
        <router-view/>
    </div>
</template>

<script>
    import BreadCrumb from "@/components/layouts/BreadCrumb";
    export default {
        name: "Users",
       components: {BreadCrumb}
    }
</script>

<style scoped>

</style>
