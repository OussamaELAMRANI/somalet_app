<template>
   <div class="combo-box">
      <div class="input-box">
         <input type="text" class="form-control"
                @focus="switchInOutAdapter"  @abort="switchInOutAdapter" v-model="valueSelected" @input="search">
         <!--         @blur="switchInOutAdapter" -->
      </div>
      <div class="detail-box " :ref="k">
         <div class="list-group list-group-flush">
            <div class="list-group-item list-group-item-action" v-if="loading">
               <div class="row justify-content-center">
                  <div class="col-5">
                     <half-circle-spinner color="#ddd" :size="30"/>
                  </div>
               </div>
            </div>
            <template v-else>
               <div v-for="p in products" class="list-group-item list-group-item-action"
                    @click="getValue(p.name)">{{p.name}}
               </div>
               <div class="list-group-item list-group-item-action bg-warning text-white" v-if="!hasData">Pas de résultat
                  !
               </div>
            </template>

         </div>
      </div>
   </div>
</template>

<script>
   import HalfCircleSpinner from "epic-spinners/src/components/lib/HalfCircleSpinner";

   export default {
      name: "ComboBox",
      components: {HalfCircleSpinner},
      data() {
         return {
            k: 'combo',
            products: [],
            valueSelected: '',
            loading: false,
            hasData: false
         }
      },
      mounted() {

      },
      methods: {
         switchInOutAdapter(e) {
            const type = e.type
            const detail = this.$refs[this.k]
            console.log(type)
            if (type === 'blur') {
               detail.classList.remove("__active")
               console.log(type)

            } else {
               detail.classList.add("__active")
            }
         },
         getValue(v) {
            this.valueSelected = v
            this.$refs[this.k].classList.remove("__active")
         },
         search(e) {
            this.products = []
            this.loading = true
            axios.get(`/api/receptions/search/${e.target.value}`)
               .then(({data}) => {
                  if (data.length > 0) this.hasData = true;
                  else this.hasData = false;

                  this.products = data
                  console.log(data)
                  this.loading = false

               })
            console.log(e.target.value)
         }
      }
   }
</script>

<style lang="scss" scoped>
   .combo-box {
      position: relative;
      display: inline-block;
   }

   .detail-box {
      display: none;
      position: absolute;
      background-color: #f9f9f9;
      min-width: 100%;
      box-shadow: 0 8px 16px 0 rgba(0, 0, 0, 0.2);
      padding: 0;
      z-index: 1;
   }

   .__active {
      display: block;
      height: 200px;
      overflow: auto;
   }

</style>
