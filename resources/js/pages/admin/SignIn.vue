<template>
   <ValidationObserver v-slot="{ handleSubmit }">

      <form @submit.prevent="handleSubmit(login)">
         <ValidationProvider name="E-mail" rules="required" v-slot="{ errors }">
            <div class="wrap-input100 validate-input">
               <input class="input100" type="text" name="username" placeholder="username..."
                      autofocus v-model="credentials.username"/>
               <span class="focus-input100"></span>
               <span class="symbol-input100"><i class="fa fa-user" aria-hidden="true"></i></span>
            </div>
            <small class="text-danger m-2">{{ errors[0] }}</small>
         </ValidationProvider>

         <ValidationProvider name="Mot de Passe" rules="required" v-slot="{ errors }">
            <div class="wrap-input100 validate-input">
               <input class="input100" type="password" name="passwd" placeholder="Mot de passe ..."
                      v-model="credentials.password">
               <span class="focus-input100"></span>
            </div>
            <small class="text-danger m-2">{{ errors[0] }}</small>
         </ValidationProvider>

         <small class="text-danger">{{ errorConnection }}</small>

         <div class="container-login100-form-btn">
            <button class="login100-form-btn" type="submit">
               Login
            </button>
         </div>
      </form>
   </ValidationObserver>
</template>

<script>

   import {mapActions} from 'vuex'
   import {ValidationProvider, ValidationObserver} from 'vee-validate'

   export default {
      name: "SignIn",
      components: {ValidationObserver},
      data() {
         return {
            credentials: {
               username: '',
               password: '',
               remember_me: true
            },
            errorConnection: null,
            redirect: undefined,
            otherQuery: {},
         }
      },
      methods: {
         login() {
            this.signIn(this.credentials)
               .then((res) => {
                  this.$router.go();
                  this.$router.push({
                     path: this.redirect || '/',
                     query: this.otherQuery
                  })
               })
               .catch(err => {
                  console.log(err)
                  this.errorConnection = "Imposible de se connecter, votre mot de passe ou username est incorrect !"
               })
         },
         getOtherQuery(query) {
            return Object.keys(query).reduce((acc, cur) => {
               if (cur !== 'redirect') {
                  acc[cur] = query[cur];
               }
               return acc;
            }, {});
         },
         ...mapActions({signIn: 'userStore/login'}),
      },
      watch: {
         $route: {
            handler: function (route) {
               const query = route.query;
               if (query) {
                  this.redirect = query.redirect;
                  this.otherQuery = this.getOtherQuery(query);
               }
            },
            immediate: true,
         },
      },
   }
</script>

<style scoped>

</style>
