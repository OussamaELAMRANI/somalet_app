<template>
    <div class="">
        <div class="limiter">
            <div class="container-login100">
                <div class="wrap-login100">
                    <div class="login100-pic">
                        <img src="/storage/boxes/logo.png" alt="IMG" id="logo_img">
                    </div>
                    <div class="login100-form validate-form">
					<span class="login100-form-title">
						 Societé MOLSAT
					</span>

                        <transition name="logIn">
                            <sign-in v-if="isMember"/>
                            <sign-up @switchUser="switchLogin" v-else/>
                        </transition>

                        <div class="text-center pt-5">
                            <button class="txt2 btn-sm btn btn-link" type="button" v-if="!isMember"
                                    @click="isMember= !isMember">
                                Se connecter maintenant
                                <i class="fa fa-user ml-5" aria-hidden="true"></i>
                            </button>
                            <button class="txt2 btn-sm btn btn-link" type="button" v-else @click="isMember= !isMember">
                                Crée nouveau compte
                                <i class="fa fa-address-card ml-5" aria-hidden="true"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


</template>

<script>
    import {mapActions} from 'vuex'
    import SignIn from "./SignIn";
    import SignUp from "./SignUp";

    export default {
        name: "Login",
        components: {SignUp, SignIn},
        data() {
            return {
                isMember: true,
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
            switchLogin() {
                this.isMember = true
            },
            login() {
                this.clean();
                this.signIn(this.credentials)
                    .then(() => {
                        this.$router.push('/admin')
                    })
                    .catch(err => {
                        console.log(err.data);
                        this.errors['error'] = "Imposible de se connecter, votre mot de passe ou username est incorrect !"
                    })
            },
            ...mapActions({signIn: 'userStore/login'}),

        }
    }
</script>
<style lang="scss">
    .alert {
        border-radius: 25px;
    }

    /*---------------------------------------------*/
    input {
        outline: none;
        border: none;
    }

    /*---------------------------------------------*/
    button {
        outline: none !important;
        border: none;
        background: transparent;

        &:hover {
            cursor: pointer;
        }

        &.container-login100-form-btn {
            width: 100%;
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            padding-top: 20px;
        }

        &.login100-form-btn {
            font-size: 15px;
            line-height: 1.5;
            color: #fff;
            text-transform: uppercase;
            width: 100%;
            height: 50px;
            border-radius: 25px;
            background: #FA9D59;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 0 25px;
            transition: all 0.4s;
        }

        &.login100-form-btn:hover {
            background: #333333;
        }
    }


    /*//////////////////////////////////////////////////////////////////
    [ Utility ]*/
    a {
        &.txt {
            font-family: Poppins-Regular;
            font-size: 13px;
            line-height: 1.5;
            margin-top: 2px;

            &1 {
                @extend .txt;
                color: #999999;
            }

            &2 {
                @extend .txt;
                color: #666666;
            }
        }
    }


    /*//////////////////////////////////////////////////////////////////
    [ login ]*/

    .limiter {
        width: 100%;
        margin: 0 auto;

        .container-login100 {
            width: 100%;
            min-height: 100vh;
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            align-items: center;
            padding: 15px;
            background: #9053c7;
            background: linear-gradient(-135deg, #F99B5B, #494684);
        }

        .wrap-login100 {
            width: 960px;
            background: #fff;
            border-radius: 10px;
            overflow: hidden;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            padding: 130px 130px 130px 90px;
        }
    }

    /*------------------------------------------------------------------
    [  ]*/
    .login100-form {
        width: 300px;

        &-title {
            font-family: Poppins-Bold;
            font-size: 24px;
            color: #333333;
            line-height: 1.2;
            text-align: center;
            width: 100%;
            display: block;
            padding-bottom: 54px;
        }
    }


    /*---------------------------------------------*/
    .wrap-input100 {
        position: relative;
        width: 100%;
        z-index: 1;
        margin-bottom: 10px;
    }

    .input100 {
        font-family: Poppins-Medium;
        font-size: 15px;
        line-height: 1.5;
        color: #666666;
        display: block;
        width: 100%;
        background: #e6e6e6;
        height: 50px;
        border-radius: 25px;
        padding: 0 30px 0 68px;
    }

    /*------------------------------------------------------------------
    [ Focus ]*/
    .focus-input100 {
        display: block;
        position: absolute;
        border-radius: 25px;
        bottom: 0;
        left: 0;
        z-index: -1;
        width: 100%;
        height: 100%;
        box-shadow: 0px 0px 0px 0px;
        color: #FA9D59;
    }

    .input100:focus + .focus-input100 {
        -webkit-animation: anim-shadow 0.5s ease-in-out forwards;
        animation: anim-shadow 0.5s ease-in-out forwards;
    }

    @-webkit-keyframes anim-shadow {
        to {
            box-shadow: 0px 0px 70px 25px;
            opacity: 0;
        }
    }

    @keyframes anim-shadow {
        to {
            box-shadow: 0px 0px 70px 25px;
            opacity: 0;
        }
    }

    .symbol-input100 {
        font-size: 15px;

        display: -webkit-box;
        display: -webkit-flex;
        display: -moz-box;
        display: -ms-flexbox;
        display: flex;
        align-items: center;
        position: absolute;
        border-radius: 25px;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 100%;
        padding-left: 35px;
        pointer-events: none;
        color: #666666;

        -webkit-transition: all 0.4s;
        -o-transition: all 0.4s;
        -moz-transition: all 0.4s;
        transition: all 0.4s;
    }

    .input100:focus + .focus-input100 + .symbol-input100 {
        color: #FA9D59;
        padding-left: 28px;
    }

    /*------------------------------------------------------------------
    [ Button ]*/


    /*------------------------------------------------------------------
    [ Responsive ]*/


    @media (max-width: 992px) {
        .wrap-login100 {
            padding: 177px 90px 33px 85px;
        }

        .login100-pic {
            width: 35%;
        }

        .login100-form {
            width: 50%;
        }
    }

    @media (max-width: 768px) {
        .wrap-login100 {
            padding: 100px 80px 33px 80px;
        }

        .login100-pic {
            display: none;
        }

        .login100-form {
            width: 100%;
        }
    }

    @media (max-width: 576px) {
        .wrap-login100 {
            padding: 100px 15px 33px 15px;
        }
    }

    /*------------------------------------------------------------------
    [ Alert validate ]*/

    .validate-input {
        position: relative;
    }

    .alert-validate::before {
        content: attr(data-validate);
        position: absolute;
        max-width: 70%;
        background-color: white;
        border: 1px solid #c80000;
        border-radius: 13px;
        padding: 4px 25px 4px 10px;
        top: 50%;
        -webkit-transform: translateY(-50%);
        -moz-transform: translateY(-50%);
        -ms-transform: translateY(-50%);
        -o-transform: translateY(-50%);
        transform: translateY(-50%);
        right: 8px;
        pointer-events: none;

        font-family: Poppins-Medium;
        color: #c80000;
        font-size: 13px;
        line-height: 1.4;
        text-align: left;

        visibility: hidden;
        opacity: 0;

        -webkit-transition: opacity 0.4s;
        -o-transition: opacity 0.4s;
        -moz-transition: opacity 0.4s;
        transition: opacity 0.4s;
    }

    .alert-validate::after {
        content: "\f06a";
        font-family: FontAwesome;
        display: block;
        position: absolute;
        color: #c80000;
        font-size: 15px;
        top: 50%;
        -webkit-transform: translateY(-50%);
        -moz-transform: translateY(-50%);
        -ms-transform: translateY(-50%);
        -o-transform: translateY(-50%);
        transform: translateY(-50%);
        right: 13px;
    }

    .alert-validate:hover:before {
        visibility: visible;
        opacity: 1;
    }

    @media (max-width: 992px) {
        .alert-validate::before {
            visibility: visible;
            opacity: 1;
        }
    }

    /*    ================== Animation ====================*/
    .logIn-enter-active {
        animation: bounce-in .5s;
    }

    .logIn-leave-active {
        animation: bounce-in 0s;
    }

    @keyframes bounce-in {
        0% {
            /*transform: scale(0);*/
            opacity: 0;
        }
        50% {
            opacity: .5;
        }
        100% {
            /*transform: scale(1);*/
            opacity: 1;
        }
    }
    #logo_img{
       height: 140px;
    }
</style>
