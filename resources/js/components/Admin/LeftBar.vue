<template>
    <!--    <sidebar-menu :menu="menu"/>-->
    <sidebar-menu :menu="menu" :collapsed="true" @collapse="onCollapse" @itemClick="onItemClick" width="200px"/>

</template>

<script>
    import {SidebarMenu} from 'vue-sidebar-menu'
    import 'vue-sidebar-menu/dist/vue-sidebar-menu.css'
    import menu from './menu'

    export default {
        name: "LeftBar",
        data() {
            return {
                menu
            }
        },
        methods: {
            onCollapse(collapsed) {
                console.log(collapsed)
            },
            onItemClick(event, item) {
                console.log(item)
            }
        },
        components: {
            SidebarMenu
        }
    }
</script>

<style scoped>

</style>
