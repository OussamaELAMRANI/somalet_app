<template>
   <table class="table table-hover table-striped">
      <thead :class="theme + ' text-center table-bordered'">
      <tr>
         <th v-for="h in headTable">{{h}}</th>
      </tr>
      </thead>
      <tbody class="table-bordered table-striped text-center table-hover segment">

      <tr v-if="data == null">
         <td :colspan="headTable.length">
            <div class="row justify-content-center">
               <div class="col-2">
                  <loading :animation-duration="6000" :size="40" color="#ff1d5e"/>
               </div>
            </div>
         </td>
      </tr>

      <tr v-else-if="data.length === 0 || data === {}">
         <td :colspan="headTable.length">
            <h6 class="text-center text-secondary m-5">{{emptyText}}</h6>
         </td>
      </tr>
      <template v-else>
         <slot></slot>
      </template>


      </tbody>
   </table>
</template>

<script>
   import {SelfBuildingSquareSpinner} from 'epic-spinners'

   export default {
      name: "TableLayout",
      props: {
         headTable: {},
         data: {
            default: null
         },
         emptyText: {},
         theme: {
            default: 'thead-dark'
         },
      },
      components: {
         'loading': SelfBuildingSquareSpinner,
      }
   }
</script>

<style scoped>

</style>
