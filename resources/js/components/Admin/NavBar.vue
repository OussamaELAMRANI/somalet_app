<template>
   <nav class="navbar navbar-expand-sm navbar-dark bg-dark mb-2">

<!--      <ul class="navbar-nav mr-auto" v-for="(r,j) in routes">-->
<!--         <template v-if="r.child == null">-->
<!--            <li class="nav-item active">-->
<!--               <router-link :to="r.href" class="nav-link">{{r.title}}</router-link>-->
<!--            </li>-->
<!--         </template>-->
<!--         <template v-else>-->
<!--            <li class="nav-item dropdown">-->
<!--               <a class="nav-link dropdown-toggle text-white" href="#" :id="'navbarDropdown'+j" role="button"-->
<!--                  data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">-->
<!--                  {{r.title}}-->
<!--               </a>-->
<!--               <div class="dropdown-menu" :aria-labelledby="'navbarDropdown'+j">-->
<!--                  <router-link class="dropdown-item" :to="c.href" v-for="(c,i) in r.child" :key="i">{{c.title}}</router-link>-->
<!--               </div>-->
<!--            </li>-->
<!--         </template>-->


<!--      </ul>-->


      <!--        <div class="container">-->
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive"
              aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
         <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarResponsive">
         <ul class="navbar-nav ml-auto">
            <!--            <li class="nav-item active">-->
            <!--               <a class="nav-link" href="#">Acceuil-->
            <!--                  <span class="sr-only">(current)</span>-->
            <!--               </a>-->
            <!--            </li>-->
            <li class="nav-item dropdown">
               <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                  data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  {{ user.username }}
               </a>
               <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                  <li class="dropdown-item text-center">
                     <img style="height: 120px;width: 120px;" src="https://picsum.photos/200/300"
                          alt="photo d'utilisateur" class="rounded-circle mx-auto d-block mb-2">
                     <p>
                        {{ user.first_name }} - {{ user.last_name }}
                     <p>
                        <small>{{ user.type_user }}</small>
                     </p>
                  </li>
                  <div class="dropdown-divider"></div>
                  <li class="" style="display: inline-list-item">
                     <div style="float: left; display: block">
                        <a href="#" class="btn btn-light">Profile</a>
                     </div>
                     <div style="float: right; display: block">
                        <a href="#" class="btn btn-light" @click="logout">Sign out</a>
                     </div>
                  </li>
               </ul>
            </li>

         </ul>
      </div>
   </nav>
</template>

<script>
   import store from "@/store"
   import {mapActions} from 'vuex'

   export default {
      name: "NavBar",
      data() {
         return {
            // menu: []
         }
      },
      created() {
         // this.menu = store.getters.permission_routes
      },
      methods: {
         logout() {
            this.logOut()
               .then(() => {
                  this.$router.push('/login')
               }).catch(err => console.log(err))
         },
         ...mapActions({logOut: 'userStore/logout'}),
      },
      computed: {
         user() {
            return store.getters.user
         },
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
            console.log(r2)
            return r2
         }

      }
   }
</script>

<style scoped>
   .dropdown-menu{
      margin-top: 10px;
   }
</style>
