<template>
    <div class="container" id="show-user">
        <h3>Utilisateur : {{user.username}}</h3>
        <div class="dropdown-divider"></div>
        <div class="row">
            <div class="col-12 m-2">
                <div class="text-uppercase"><strong>Login :</strong><span class="ml-3">{{user.username}}</span>
                </div>
            </div>
            <div class="col-12 m-2">
                <div class="text-uppercase"><strong>Nom :</strong><span
                    class="ml-3">{{user.first_name}}</span></div>
            </div>
            <div class="col-12 m-2">
                <div class="text-uppercase"><strong>Prenom :</strong><span
                    class="ml-3">{{user.last_name}}</span>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12 m-2">
                <div class="text-uppercase"><strong>Type :</strong><span
                    class="ml-3">{{user.type_user}}</span></div>
            </div>
            <div class="col-12 m-2">
                <div class="text-uppercase input-group"><strong>Etat :</strong>
                    <span class="ml-3">
                        <toggle-switch :options="myOptions" v-model="toggle"/>
                    </span>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "showUser",
        props: {
            user: {
                type: Object
            }
        },
        data() {
            return {
                toggle: 'active',
                myOptions: {
                    layout: {
                        color: 'black',
                        backgroundColor: 'lightgray',
                        selectedColor: 'white',
                        selectedBackgroundColor: 'green',
                        borderColor: 'black',
                        fontFamily: 'Arial',
                        fontWeight: 'normal',
                        fontWeightSelected: 'bold',
                        squareCorners: false,
                        noBorder: false
                    },
                    size: {
                        fontSize: 0.7,
                        height: 2,
                        // padding: 7,
                        // width: 500
                    },
                    items: {
                        delay: .4,
                        preSelected: 'unknown',
                        disabled: true,
                        labels: [
                            {name: 'active', color: 'white', backgroundColor: 'green'},
                            {name: 'desactive', color: 'white', backgroundColor: 'red'}
                        ]
                    }
                }
            }
        },
        mounted() {
            if (this.user.is_active === 0) {
                this.toggle = 'desactive'
            } else {
                this.toggle = 'active'
            }
        }
    }

</script>

<style>

</style>
