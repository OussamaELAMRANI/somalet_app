<template>
    <div id="new-provider" class="container">
        <big-title title="Fournisseur"/>
        <div class="row d-flex justify-content-center">
            <div class="col-3 justify-content-center">
                <StepBox image="presentation.svg" classe="active-box"
                         title="Informations Professionelles" @step="checkStep(0)"
                         :classe="(box===0) ? 'active-box' : 'normal-box'"/>
            </div>
            <div class="col-3 justify-content-center">
                <StepBox title="Informations personnelle" image="information.svg" @step="checkStep(1)"
                         :classe="(box===1) ? 'active-box' : 'normal-box'"
                />
            </div>
            <div class="col-3 justify-content-center">
                <StepBox title="Contact" image="contact.svg" @step="checkStep(2)"
                         :classe=" (box === 2)  ? 'active-box' : 'normal-box'"/>
            </div>
        </div>

        <ProfessionalForm class="segment " v-show="box === 0" @update="getProvider" ref="ProfessionalForm"/>
        <PersonalForm class="segment " v-show="box === 1" @update="getProvider" ref="PersonalForm"/>
        <ContactForm class="segment" v-show="box === 2" @update="getProvider" ref="ContactForm"/>

        <!--</transition>-->
        <divider class="float-right">
            <button type="button" class="btn btn-outline-success" @click="submitProvider">VALIDER</button>
        </divider>

        <!--        <p><code>-->
        <!--            {{provider}}-->
        <!--        </code></p>-->
    </div>
</template>

<script>
    import ProfessionalForm from './../../components/Provider/ProfessionalForm'
    import PersonalForm from "../../components/Provider/PersonalForm";
    import ContactForm from "../../components/Provider/ContactForm";
    import Divider from "../../components/Divider";
    import StepBox from "../../components/Steps/StepBox";
    import BigTitle from "@/components/layouts/BigTitle";

    export default {
        name: "NewProvider",
        data() {
            return {
                box: 0,
                provider: {
                    professional: {},
                    personal: {},
                    contact: {},
                    id: 0,
                },
                isNew: true
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

                this.$refs.ProfessionalForm.sendToProvider()
                    .then(isValid => {
                        if (isValid) {
                            axios.post('/api/providers', this.provider)
                                .then(res => {
                                    console.log(res.data)
                                    this.$router.push({name: 'listProvider'})
                                })
                                .catch(err => this.$notification.error(err))
                        } else
                            this.$notification.error('Nom de la Societé est obligatoire !')
                    })

            }
        },
        mounted() {
            const id = this.$route.params['id']
            if (this.$route.params['id'] !== undefined) {
                this.isNew = false
                this.provider.id = id
                axios.get(`/api/providers/${id}`)
                    .then(res => {
                        const data = res.data
                        this.provider.professional = {
                            steName: data.steName,
                            numTva: data.numTva,
                            nSiret: data.nSiret,
                        }
                        this.provider.contact = {
                            cell: data.cell,
                            phone: data.phone,
                            email: data.email,
                            skype: data.skype,
                            icp: data.icp,
                        }
                        this.provider.personal = {
                            firstName: data.firstName,
                            lastName: data.lastName,
                            address: data.address,
                            cp: data.cp,
                            city: data.city,
                            country: data.country,
                        }
                        this.$refs.ProfessionalForm.setProfessional(this.provider.professional);
                        this.$refs.PersonalForm.setPersonal(this.provider.personal);
                        this.$refs.ContactForm.setContact(this.provider.contact);
                    })
                    .catch(err => {
                        this.$notification.error("Ce produit n'existe pas !")
                        this.$notification.error(err)
                        this.$router.push('/404')
                    })
            } else {
                this.provider = {
                    professional: {},
                    personal: {},
                    contact: {},
                    id: 0
                };
                this.isNew = true
            }
        },
        components: {
            BigTitle,
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
