<template>
    <div class="container">
        <form @submit.prevent="login" class="segment">
            <div class="form-group">

                <label for="exampleInputusername1">username address</label>
                <input type="text" class="form-control" id="exampleInputusername1" aria-describedby="usernameHelp"
                       placeholder="Enter votre username"
                       v-model="credentials.username" autofocus>
                <small id="usernameHelp" class="form-text text-muted">Ne pas partager votre username
                </small>
                <div class="alert alert-danger" v-if="errors.username">
                    {{errors.username}}
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
                    username: null,
                    password: null,
                    error: null,
                },
                credentials: {
                    username: '',
                    password: '',
                    remember_me: false
                }
            }
        },
        methods: {
            clean() {
                this.errors = {
                    username: null,
                    password: null,
                    error: null,
                }
            },
            login() {
                this.clean()

                if (!this.credentials.username) {
                    this.errors['username'] = 'Enter votre username !';
                }
                // else if (!this.validusername(this.credentials.username)) {
                //     this.errors['username'] = 'Invalid username !'
                // }

                if (!this.credentials.password)
                    this.errors['password'] = 'Enter Votre Mot de passe !'

                if (!this.errors.password && !this.errors.username)

                    this.signIn(this.credentials)
                        .then(() => {
                            this.$router.push('/admin')
                        })
                        .catch(err => {
                            console.log(err.data)
                            this.errors['error'] = "Imposible de se connecter, votre mot de passe ou username est incorrect !"
                        })
            },
            ...mapActions({signIn: 'login'}),

            // validEmail: function (email) {
            //     var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            //     return re.test(email);
            // }
        }
    }
</script>

