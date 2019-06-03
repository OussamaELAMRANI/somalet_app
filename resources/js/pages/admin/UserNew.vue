<template>
    <div id="UserNew" class="segment">
        <h2 class="mb-4">Nouveau utilisateur : </h2>
        <form @submit.prevent="register">
            <div class="row">
                <div class="col">
                    <div class="form-group">
                        <label>Login</label>
                        <input class="form-control" name="username" type="text" v-model="username"
                               v-validate="'required'"/>
                        <i class="fa fa-exclamation-triangle text-danger" v-show="errors.has('username')"></i>
                        <span class="help text-danger" v-show="errors.has('username')">{{ errors.first('username') }}</span>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="form-group">
                        <label>Prenom</label>
                        <input class="form-control" name="lName" type="text" v-model="lName"
                               v-validate="'required'"/>
                        <i class="fa fa-exclamation-triangle text-danger" v-show="errors.has('lName')"></i>
                        <span class="help text-danger" v-show="errors.has('lName')">{{ errors.first('lName') }}</span>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="form-group">
                        <label>Nom</label>
                        <input class="form-control" name="fName" type="text" v-model="fName"
                               v-validate="'required'"/>
                        <i class="fa fa-exclamation-triangle text-danger" v-show="errors.has('fName')"></i>
                        <span class="help text-danger" v-show="errors.has('fName')">{{ errors.first('fName') }}</span>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="form-group">
                        <label>mot de passe</label>
                        <input class="form-control" name="passwd" type="password" placeholder="Mot de passe ..."
                               v-model="password" ref="password"
                               v-validate="'required'"/>
                        <i class="fa fa-exclamation-triangle text-danger" v-show="errors.has('passwd')"></i>
                        <span class="help text-danger" v-show="errors.has('passwd')">{{ errors.first('passwd') }}</span>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="form-group">
                        <label>Confirmation du mot de passe</label>
                        <input class="form-control" name="passwd2" type="password" placeholder="confirmer votre Mot de passe ..."
                               v-model="password2" ref="password"
                               v-validate="'required|confirmed:password'" />
                        <i class="fa fa-exclamation-triangle text-danger" v-show="errors.has('passwd2')"></i>
                        <span class="help text-danger" v-show="errors.has('passwd2')">{{ errors.first('passwd2') }}</span>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <button class="btn btn btn-outline-success" type="submit">Enregistrer</button>
                </div>
            </div>
        </form>
    </div>
</template>

<script>
    import {mapActions} from 'vuex'

    export default {
        name: "UserNew",
        data() {
            return {
                username: null,
                fName: null,
                lName: null,
                password: null,
                password2: null,
                isNew : false
            }
        },
        mounted() {
            // Custom Messages for the Validation
            const dict = {
                custom: {
                    username: {
                        required: () => 'Vous devez saisire votre Login !'
                    },
                    lName: {
                        required: () => 'Vous devez saisire votre Prénom !'
                    },
                    fName: {
                        required: () => 'Vous devez saisire votre Nom !'
                    },
                    passwd: {
                        required: () => 'Vous devez saisire votre Mot de Passe !'
                    },
                    passwd2: {
                        required: () => 'Vous devez confirmer votre Mot de Passe !',
                        confirmed: () => 'Les deux Mots de Passe ne correspandent pas!'
                    }
                }
            };
            this.$validator.localize('en', dict);
            //Edition
            const id = this.$route.params['id'];
            if (this.$route.params['id'] !== undefined) {
                this.isNew = false
                this.id = id
                axios.get(`/api/products/${id}`)
                    .then(res => {
                        const data = res.data
                        this.provider = data.provider
                        this.name = data.name
                        this.refr = data.reference
                        this.desc = data.description
                        this.note = data.remark
                        this.color = data.color.id
                        // this.colors = data.colors
                        this.unity = data.unit.id
                        // this.unities = data.unities
                        this.type = data.type
                        this.alertQte = data.alertQte
                        this.id = data.id
                        this.img = data.img
                        this.img_url = `${process.env.MIX_APP_URL}/storage/${this.img}`
                        // this.$refs.portraits.file = data.img
                    })
                    .catch(err => {
                        // this.$notification.error("Ce produit n'existe pas !")
                        this.$notification.error(err)
                        // this.$router.push('/404')
                    })

            } else {
                this.provider = null;
                this.isNew = true
                this.id = 0
            }
        },
        methods: {
            register() {
                return this.$validator.validateAll().then((result) => {
                    const credentials = {
                        username: this.username,
                        first_name: this.fName,
                        last_name: this.lName,
                        password: this.password,
                        password_confirmation: this.password2
                    };
                    this.SignUp(credentials)
                        .then(() => {
                            // this.$router.push('/login')
                            this.$notification.new("l'utilisateur a ete bien enregister. ",
                                {
                                    timer: 10,
                                    showCloseIcn: true,
                                    type: 'success'
                                }
                            )
                            ;
                            this.$router.push({name: 'list_users'})
                        })
                        .catch(err => {
                            console.log(err.data)
                            // this.errorConnection = "Imposible de se connecter, votre mot de passe ou username est incorrect !"
                        })
                });
            },
            ...mapActions({SignUp: 'userStore/sign_up'}),
        }

    }

</script>

<style scoped>

</style>
