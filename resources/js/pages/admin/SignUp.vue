<template>
   <ValidationObserver v-slot="{ handleSubmit }">
      <form @submit.prevent="handleSubmit(register)">
         <ValidationProvider name="" rules="required" v-slot="{ errors }">
            <div class="wrap-input100 validate-input">
               <input class="input100" type="text" name="username" placeholder="username..."
                      autofocus v-model="username"/>
               <span class="focus-input100"></span>
               <span class="symbol-input100"><i class="fa fa-user"></i></span>
            </div>
            <small class="text-danger m-2">{{ errors[0] }}</small>
         </ValidationProvider>


         <ValidationProvider name="E-mail" rules="required" v-slot="{ errors }">
            <div class="wrap-input100 validate-input">
               <input class="input100" type="text" name="l_name" placeholder="Nom..."
                      v-model="lName"/>
               <span class="focus-input100"></span>
               <span class="symbol-input100"><i class="fa fa-user"></i></span>
            </div>
         </ValidationProvider>

         <div class="wrap-input100 validate-input">
            <input class="input100" type="text" name="f_name" placeholder="Prénom..."
                   data-vv-delay="500" v-model="fName"/>
            <span class="focus-input100"></span>
            <span class="symbol-input100"><i class="fa fa-user"></i></span>
         </div>


         <div class="wrap-input100 validate-input">
            <input class="input100" type="password" name="passwd" placeholder="Mot de passe ..." ref="password"
                   v-model="password">
            <span class="focus-input100"></span>
            <span class="symbol-input100"><i class="fa fa-lock"></i></span>
         </div>

         <div class="wrap-input100 validate-input">
            <input class="input100" type="password" name="passwd2" placeholder="confirmer votre Mot de passe ..."
                   v-model="password2">
            <span class="focus-input100"></span>
            <span class="symbol-input100"><i class="fa fa-lock"></i></span>
         </div>

         <div class="container-login100-form-btn">
            <button class="login100-form-btn" type="submit">
               Enregistrer
            </button>
         </div>
      </form>
   </ValidationObserver>
</template>

<script>
   import {mapActions} from 'vuex'
   import {ValidationProvider, ValidationObserver} from 'vee-validate'

   export default {
      name: "SignUp",
      components: {ValidationObserver},

      data() {
         return {
            username: null,
            fName: null,
            lName: null,
            password: null,
            password2: null,
         }
      },
      methods: {
         register() {
            const credentials = {
               username: this.username,
               first_name: this.fName,
               last_name: this.lName,
               password: this.password,
               password_confirmation: this.password2
            };
            this.SignUp(credentials)
               .then(() => {
                  this.$notification.new("l'utilisateur a ete bien enregister. ",
                     {
                        timer: 10,
                        showCloseIcn: true,
                        type: 'success'
                     }
                  )
                  ;
                  this.$emit('switchUser')
               })
               .catch(err => {
                  console.log(err.data)
               })
         },
         ...mapActions({SignUp: 'userStore/sign_up'}),
      }

   }
</script>

<style scoped>

</style>
