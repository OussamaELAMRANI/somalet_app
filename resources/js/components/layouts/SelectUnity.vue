<template lang="pug">
   .row
      .col-4
         p.text-uppercase.text-black.mb-0 Unité de ce produit
         ul
            li
               small.text-secondary  Pour ajouter (Nouvelle unité) clic sur
                  strong  Nouveau ++
            li
               small.text-secondary Sinon vous pouvez selectionnner une unité dans la liste
      .col-4
         label(for='unity') Selectionnez l&apos;unit&eacute;
         select#unity.form-control(v-on:change='getUnity' v-model="unit")
            option(selected='selected' disabled='disabled') Selectionnez...
            option NOUVEAU ++
            option(v-for=' c in unities' :id="'unity_'+c.id" :selected='(c.id === unity)')
               | {{ c.name }}
      transition( name="fade")
         .col-4(v-if='isNewUnity')
            label(for='new-unity') Nouvelle Unité
            .input-group
               input#new-unity.form-control(type='text' placeholder='Unité ...' name='newUnity' v-model='unity'
                  v-on:keyup.enter='addUnity')
               button.btn-success.btn-group-sm(@click="addUnity") Ajouter

</template>

<script>
   export default {
      name: "SelectUnity",
      props: {
         selectedUnity: {
            default: null
         }

      },
      data() {
         return {
            isNewUnity: false,
            hasRapport: true,
            unity: null,
            unit: null,
            unities: ''
         }
      },
      methods: {
         getUnity(ev) {
            let val = ev.target.value
            let id = ev.target.options[ev.target.options.selectedIndex].id
            id = id.split('_')
            if (val === 'NOUVEAU ++') {
               this.unity = null
               this.isNewUnity = true
            } else {
               this.isNewUnity = false
               this.unity = parseInt(id[1])
               this.$emit('sendUnity', this.unity, val)
            }
            // this.setRapport(val);
         },
         addUnity() {
            const _this = this
            axios.post('/api/unities',
               {
                  name: this.unity
               }
            ).then(function (response) {
               _this.unities = []
               _this.unities = response.data.unities
               _this.isNewUnity = false;
               _this.unity = ''
            })
               .catch(function (error) {
                  console.log(error.response);
               })

         },
      },
      mounted() {
         axios.get('/api/unities')
            .then(res => {
               this.unities = res.data
            })
            .catch(err => console.log(err.response))
      },
      watch: {
         selectedUnity: function (u) {
            this.unit = u
         }
      }
   }
</script>

<style scoped>
   .fade-enter-active, .fade-leave-active {
      transition: opacity .5s;
   }

   .fade-enter, .fade-leave-to /* .fade-leave-active below version 2.1.8 */
   {
      opacity: 0;
   }
</style>
