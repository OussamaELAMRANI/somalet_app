<template>
   <!--    <sidebar-menu :menu="menu"/>-->
   <sidebar-menu :menu="routes" :collapsed="false" @collapse="onCollapse" @itemClick="onItemClick" width="200px"/>

</template>

<script>
   import {SidebarMenu} from 'vue-sidebar-menu'
   import 'vue-sidebar-menu/dist/vue-sidebar-menu.css'
   import store from '@/store'

   export default {
      name: "LeftBar",
      data() {
         return {
            menu: []
         }
      },
      created() {
         this.menu = store.getters.permission_routes
      },
      computed: {
         routes() {
            const r = _.filter(this.menu, route => {
               return !route.hidden
            })
            let r2 = []
            _.forEach(r, route => {
               r2.push({
                  href: route.path,
                  title: route.meta.title,
                  icon: route.meta.icon
               })
               _.forEach(route.children, child => {
                  if (!child.hidden) {
                     if (!child.children)
                        r2.push({
                           href: child.path,
                           title: child.meta.title,
                           icon: child.meta.icon,

                        })
                     else {
                        const children = []
                        _.forEach(child.children, c => {
                           if (!c.hidden)
                              children.push({
                                 href: {name: c.meta.name},
                                 title: c.meta.title,
                              })
                        })
                        r2.push({
                           // href: child.path,
                           //todo add dashbord href
                           title: child.meta.title,
                           icon: child.meta.icon,
                           child: children
                        })
                     }


                  }


               })
            })
            return r2
         }
      },
      methods: {
         onCollapse(collapsed) {
            // console.log(collapsed)
         },
         onItemClick(event, item) {
            // console.log(item)
         }
      },
      components: {
         SidebarMenu
      }
   }
</script>

<style>
   .v-sidebar-menu, .vsm-default {
      box-shadow: 0 2px 4px rgba(0, 0, 0, .5);
   }
</style>
