<template>
    <div id="new-provider" class="container">

        <h3 class="text-success mt-4 text-lg-center">
            Fournisseur
        </h3>
        <div class="dropdown-divider"></div>

        <div class="row justify-content-center">
            <div class="col-3">
                <StepBox image="presentation.svg" classe="active-box"
                         title="Informations Professionelles" @step="checkStep(0)"
                         :classe="(box===0) ? 'active-box' : 'normal-box'"/>
            </div>
            <div class="col-3">
                <StepBox title="Informations personnelle" image="information.svg" @step="checkStep(1)"
                         :classe="(box===1) ? 'active-box' : 'normal-box'"
                />
            </div>
            <div class="col-3">
                <StepBox title="Contact" image="contact.svg" @step="checkStep(2)"
                         :classe=" (box === 2)  ? 'active-box' : 'normal-box'"/>
            </div>
        </div>

        <!--<transition name="bounce">-->

        <ProfessionalForm class="segment " v-show="box === 0" @update="getProvider">
            <button class="btn btn-info" @click="checkStep(1)" type="button">Suivant</button>
        </ProfessionalForm>

        <PersonalForm class="segment " v-show="box === 1" @update="getProvider">
            <button class="btn btn-info" @click="checkStep(0)"  type="button">precedant</button>
            <button class="btn btn-info" @click="checkStep(2)" type="button">Suivant</button>

        </PersonalForm>
        <ContactForm class="segment" v-show="box === 2" @update="getProvider">
            <button class="btn btn-info" @click="checkStep(1)"  type="button">precedant</button>
        </ContactForm>
        <!--</transition>-->
        <divider class="float-right">
            <button type="button" class="btn btn-outline-success" @click="submitProvider">VALIDER</button>
        </divider>

        <p><code>
            {{provider}}
        </code></p>
    </div>
</template>

<script>
    import ProfessionalForm from './../../components/Provider/ProfessionalForm'
    import PersonalForm from "../../components/Provider/PersonalForm";
    import ContactForm from "../../components/Provider/ContactForm";
    import Divider from "../../components/Divider";
    import StepBox from "../../components/Steps/StepBox";

    export default {
        name: "NewProvider",
        data() {
            return {
                box: 0,
                provider: {
                    professional: {},
                    personal: {},
                    contact: {},
                }


            }
        },

        methods: {
            getProvider(data) {
                const key = Object.keys(data)
                this.provider[key] = data[key]
            },
            checkStep(step) {
                this.box = step
            },
            submitProvider() {
                axios.post('/api/providers', this.provider)
                    .then(res => {
                        // console.log(res.data)
                        this.$router.push({name: 'listProvider'})
                    })
                    .catch(err => console.log(err.response))
            }
        },
        computed: {},
        components: {
            StepBox,
            Divider,
            ContactForm,
            PersonalForm,
            ProfessionalForm
        },
    }
</script>

<style scoped>
    .bounce-enter-active {
        animation: bounce-in .2s;
    }

    .bounce-leave-active {
        animation: bounce-in .2s reverse;
    }

    @keyframes bounce-in {
        0% {
            opacity: 0;
        }
        100% {
            opacity: 1;
        }
    }
</style>
