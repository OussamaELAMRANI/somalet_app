<template lang="pug">
   #setting_payments
      h3 TYPE DE PIEMENTS
      .row.justify-content-sm-center
         .col-4
            table.table.shadow.table-striped.text-center
               thead.bg-primary
                  tr
                     th #
                     th Type
               tbody
                  tr(v-for="(p,i) in types")
                     td {{i+1}}
                     td {{p.type}}

         .col-3
            .segment.m-0.shadow-lg
               h5.font-weight-light.text-primary NOUVEAU
               hr
               .form-group
                  label Type
                  input.rounded-pill.form-control(type="text", placeholder="Exemple: CHQ, EFF ..." v-model="type", ref="newType")
               .form-group
                  button.btn.bg-primary.btn-block.rounded-pill(@click="addType") Ajouter

</template>

<script>
    export default {
        name: "PaymentsType",
        data() {
            return {
                types: [],
                type: '',
                isExist: true
            }
        },
        mounted() {
            axios.get('/api/payments-type')
                .then(({data}) => {
                    this.types = data;
                })
                .catch(error => console.log(error))
        },
        methods: {
            addType() {
                const type = this.type;
                axios.post('/api/payments-type', {type})
                    .then(({data}) => {
                        console.log(data)
                        this.types.push(data);
                        this.clearType();
                    })
                    .catch(error => {
                        console.log(error)
                        this.isExist = !this.isExist;
                    })
            },
            clearType() {
                this.type = '';
                this.$refs.newType.focus();
            }
        }
    }
</script>

<style scoped>
   .bounce-enter-active {
      animation: bounce-in .5s;
   }

   .bounce-leave-active {
      animation: bounce-in .5s reverse;
   }

   @keyframes bounce-in {
      0% {
         transform: scale(1);
      }
      50% {
         transform: scale(1.5);
      }
      100% {
         transform: scale(1);
      }
   }
</style>
