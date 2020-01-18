<template>
    <div id="new-client" class="container">

        <h3 class="text-success mt-4 text-lg-center">
            Client
        </h3>
        <div class="dropdown-divider"></div>

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

        <!--<transition name="bounce">-->

        <ProfessionalForm class="segment " v-show="box === 0" @update="getClient" ref="ProfessionalForm" >
            <button class="btn btn-info" @click="checkStep(1)" type="button">Suivant</button>
        </ProfessionalForm>

        <PersonalForm class="segment " v-show="box === 1" @update="getClient" ref="PersonalForm">
            <button class="btn btn-info" @click="checkStep(0)"  type="button">precedant</button>
            <button class="btn btn-info" @click="checkStep(2)" type="button">Suivant</button>

        </PersonalForm>
        <ContactForm class="segment" v-show="box === 2" @update="getClient" ref="ContactForm">
            <button class="btn btn-info" @click="checkStep(1)"  type="button">precedant</button>
        </ContactForm>
        <!--</transition>-->
        <divider class="float-right">
            <button type="button" class="btn btn-outline-success" @click="submitClient">VALIDER</button>
        </divider>

    </div>
</template>

<script>
    import ProfessionalForm from './../../components/Client/ProfessionalForm'
    import PersonalForm from "../../components/Client/PersonalForm";
    import ContactForm from "../../components/Client/ContactForm";
    import Divider from "../../components/Divider";
    import StepBox from "../../components/Steps/StepBox";

    export default {
        name: "NewClient",
        data() {
            return {
                box: 0,
                client: {
                    professional: {},
                    personal: {},
                    contact: {},
                    id:0,
                },
                isNew:true
            }
        },
        methods: {
            getClient(data) {
                const key = Object.keys(data)
                this.client[key] = data[key]
            },
            checkStep(step) {
                this.box = step
            },
            submitClient() {
                axios.post('/api/clients', this.client)
                    .then(res => {
                        console.log(res.data)
                        this.$router.push({name: 'listClient'})
                    })
                    .catch(err => this.$notification.error(err))
            }
        },
        mounted(){
            const id = this.$route.params['id']
            if(this.$route.params['id'] !== undefined) {
                this.isNew = false
                this.client.id = id
                axios.get(`/api/clients/${id}`)
                    .then(({data}) => {
                        this.client.professional = {
                            firstName: data.firstName,
                            lastName: data.lastName,
                           old_sold: data.old_sold,
                        }
                        this.client.contact = {
                            cell: data.cell,
                            phone: data.phone,
                            email: data.email,
                            skype: data.skype,
                            icp: data.icp,
                        }
                        this.client.personal ={
                            address: data.address,
                            cp: data.cp,
                            city: data.city,
                            country: data.country,
                        }
                        this.$refs.ProfessionalForm.setProfessional(this.client.professional);
                        this.$refs.PersonalForm.setPersonal(this.client.personal);
                        this.$refs.ContactForm.setContact(this.client.contact);
                    })
                    .catch(err => {
                        this.$notification.error("Ce produit n'existe pas !")
                        this.$notification.error(err)
                        this.$router.push('/404')
                    })
                }
                else {
                    this.client = {
                        professional: {},
                        personal: {},
                        contact: {},
                        id:0
                    };
                    this.isNew = true
                }
        },
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
