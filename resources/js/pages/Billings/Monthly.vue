<template lang="pug">
   #monthly_bills
      .container
         .row
            .col-4

               .input-group
                  label.m-1 Transférer vers:
                  select.form-control(v-model="in_bank")
                     option(:value="null" disabled) Selectionnez
                     option(v-for="b in banksIN" :value="b.id") {{b.name}}
            .col-4
               .input-group
                  button.btn.btn.btn-primary(@click="transferToBank") Transférer

            .col-10
               .row
                  .col-12
                     //h4.text-secondary VALIDE
                     hr
                     table.table.table-striped.shadow.text-center
                        thead.small.text-white.bg-success
                           tr
                              th #
                              th Date d'encaissement
                              th Date d'echaillence
                              th Montant
                              th Type
                              th Numéro
                              th Designation
                              th Action
                        tbody
                           tr(v-for="(iv, i) in hasValid" :class="(cannotTransfer(iv.date_deadline) ? 'bg-light': 'bg-warning')")
                              td.align-middle
                                 .form-group(v-if="cannotTransfer(iv.date_deadline)")
                                    .form-check
                                       input.form-check-input.form-control-lg(type='checkbox' :id="'check_'+i" v-model="hasValid[i].checked")
                                       label.form-check-label.text-primary(:for="'check_'+i")
                                          | Sélectionner

                              td.align-middle {{iv.payed_at | humane_date}}
                              td.align-middle {{iv.date_deadline | humane_date}}
                              td.align-middle {{iv.amount}} DH
                              td.align-middle {{iv.type}}
                              td.align-middle {{iv.chq_number}}
                              td.align-middle
                                 p {{iv.client.nom}}
                                 p {{iv.designation}}
                              td.align-middle
                                 router-link.btn.btn-outline-secondary.btn-sm.shadow(:to="{name:'UpDeadline',params:{id:iv.id}}") Modifier
                                 //@click="validElement(iv.id,1)"
                  //.col-12
                     h4.text-secondary IMPAYÉ
                     hr
                     table.table.table-striped.shadow.text-center
                        thead.small.text-white.bg-primary
                           tr
                              th #
                              th Type
                              th Numéro
                              th Montant
                              th Client
                              th Action
                        tbody
                           tr(v-for="(v, i) in invalid")
                              td
                                 i.icon.fa.fa-times-circle.text-primary
                              td.align-middle {{v.amount}} DH
                              td {{v.type}}
                              td {{v.chq_number}}
                              td {{v.client.nom}}
                              td
                                 button.btn.btn-outline-primary.btn-sm.shadow(@click="validElement(v.id,0)") Valider


            .tag.col-2.text-center
               .segment.shadow.my-0
                  h3.text-primary {{$route.params.year}}
                  p.lead.text-secondary {{months_name[$route.params.month-1]}}


</template>

<script>
   import moment from 'moment'

   export default {
      name: "Monthly",
      data() {
         return {
            valid: [],
            hasValid: [],
            invalid: [],
            months_name: ['Janvier', 'février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
            banksIN: [],

            in_bank: null

         }
      },
      mounted() {
         const {year, month} = this.$route.params;

         axios.get(`/api/payments/valid-invalid`, {
            params: {
               year, month
            }
         })
            .then(({data}) => {
               const {valid, invalid} = data;

               valid.forEach(v => {
                  const check = {...v, checked: false};
                  this.hasValid.push(check);
               });

            })

         axios.get('/api/banks/internals')
            .then(({data}) => {
               this.banksIN = data
            }).catch(error => console.log(error))
      },
      methods: {
         validElement(id, type) {
            if (type) {
               this.invalid.unshift(...this.valid.filter(v => v.id === id))
               this.valid = this.valid.filter(v => v.id !== id)
            } else {
               this.valid.unshift(...this.invalid.filter(v => v.id === id))
               this.invalid = this.invalid.filter(v => v.id !== id)
            }
         },
         setImpaye(id) {
            axios.put(`/api/payments/${id}/impaye`)
               .then(({data}) => {
                  console.log(data);
                  const {id} = data
                  this.validElement(1, id)
               })
         },
         transferToBank() {
            const bank_id = this.in_bank;
            let ids = [];
            this.hasValid.forEach(v => {
               if (v.checked)
                  ids.push(v.id)
            });

            axios.put(`/api/payments/transfer-to/${bank_id}`, {ids})
               .then(({data}) => {
                  console.log(data);
                  this.$router.push({name: 'banks'})
               });

            console.log(ids);
         },
         cannotTransfer(d) {
            let now = moment(new Date()); //todays date
            let end = moment(d); // another date
            let duration = moment.duration(now.diff(end));
            console.log(duration.asDays())
            return (duration.asDays() > 0);
         }
      }
   }
</script>

<style scoped>
   .tag {
      height: 100px;
   }
</style>
