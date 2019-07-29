<template>
   <div class="container" id="show-product">
      <h3>Detail du produit</h3>
      <div class="dropdown-divider"></div>
      <div class="row">
         <div class="col-12 m-2 text-center">
            <img :src="host+''+product.img" style="height: 200px;width: 200px;" alt="img"/>
            <div class="dropdown-divider"></div>
         </div>
         <div class="col m-2">
            <div class="text-uppercase"><strong>Fournisseur :</strong><span
               class="ml-3">{{product.provider.steName}}</span>
            </div>
         </div>
      </div>
      <div class="row">
         <div class="col m-2">
            <div class="text-uppercase"><strong>Reference :</strong><span class="ml-3">{{product.reference}}</span>
            </div>
         </div>
         <div class="col m-2">
            <div class="text-uppercase"><strong>Nom du produit :</strong><span
               class="ml-3">{{product.name}}</span></div>
         </div>
      </div>
      <div class="row">
         <div class="col m-2">
            <div class="text-uppercase"><strong>Description :</strong><span
               class="ml-3">{{product.description}}</span>
            </div>
         </div>
         <div class="col m-2">
            <div class="text-uppercase"><strong>Remarque :</strong><span
               class="ml-3">{{product.remark}}</span></div>
         </div>
      </div>
      <div class="row">
         <div class="col m-2">
            <div class="text-uppercase"><strong>Couleur :</strong><span class="ml-3">{{product.color.name}}</span>
            </div>
         </div>
         <div class="col m-2">
            <div class="text-uppercase"><strong>Unité :</strong><span
               class="ml-3">{{product.unit.name}}</span></div>
         </div>
      </div>
      <div class="row">
         <div class="col m-2">
            <div class="text-uppercase"><strong>Alert quantité minimum :</strong><span
               class="ml-3">{{product.alertQte}}</span>
            </div>
         </div>
         <div class="col m-2">
            <div class="text-uppercase"><strong>Type du produit :</strong><span
               class="ml-3">{{product.type}}</span></div>
         </div>
      </div>
   </div>
</template>

<script>
   export default {
      name: "showProduct",
      props: {
         product: {
            type: Object,
         }
      },
      data() {
         return {
            // host:`${process.env.MIX_APP_URL}/storage/`
            host: `/storage/`
         }
      }
   }
</script>

<style scoped>

</style>
