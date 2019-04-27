<template>
    <div class="container">
        <form @submit.prevent="login" class="segment">
            <div class="form-group">

                <label for="exampleInputEmail1">Email address</label>
                <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp"
                       placeholder="Enter votre email"
                       v-model="credentials.email" autofocus>
                <small id="emailHelp" class="form-text text-muted">Ne pas partager votre email
                </small>
                <div class="alert alert-danger" v-if="errors.email">
                    {{errors.email}}
                </div>
                <div class="dropdown-divider"></div>

            </div>
            <div class="form-group">
                <label for="exampleInputPassword1">Password</label>
                <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Mot de passe .."
                       v-model="credentials.password">
                <div class="alert alert-danger" v-if="errors.password">
                    {{errors.password}}
                </div>
            </div>
            <div class="form-check">
                <input type="checkbox" class="form-check-input" id="exampleCheck1" v-model="credentials.remember_me">
                <label class="form-check-label" for="exampleCheck1">Garder la session ouverte</label>
            </div>
            <div class="alert alert-danger" v-if="errors.error">
                {{errors.error}}
            </div>
            <div class="dropdown-divider"></div>

            <button type="submit" class="btn btn-primary">Log In</button>

        </form>

    </div>


</template>

<script>
    import {mapActions} from 'vuex'

    export default {
        name: "Login",
        data() {
            return {
                errors: {
                    email: null,
                    password: null,
                    error: null,
                },
                credentials: {
                    email: '',
                    password: '',
                    remember_me: false
                }
            }
        },
        methods: {
            clean() {
                this.errors = {
                    email: null,
                    password: null,
                    error: null,
                }
            },
            login() {
                this.clean()

                if (!this.credentials.email) {
                    this.errors['email'] = 'Enter votre Email !';
                }
                else if (!this.validEmail(this.credentials.email)) {
                    this.errors['email'] = 'Invalid Email !'
                }

                if (!this.credentials.password)
                    this.errors['password'] = 'Enter Votre Mot de passe !'

                if (!this.errors.password && !this.errors.email)

                    this.signIn(this.credentials)
                        .then(() => {
                            this.$router.push('/admin')
                        })
                        .catch(err => {
                            console.log(err.data)
                            this.errors['error'] = "Imposible de se connecter, votre mot de passe ou email est incorrect !"
                        })
            },
            ...mapActions({signIn: 'login'}),

            validEmail: function (email) {
                var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
                return re.test(email);
            }
        }
    }
</script>

