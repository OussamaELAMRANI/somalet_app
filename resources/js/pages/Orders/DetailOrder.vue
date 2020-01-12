<template lang="pug">
   #order_detail.my-5
      router-link(:to="{name:'newOrder'}").btn.btn-sm.btn-outline-success
         i.fa.fa-backward
         | {{' Nouveau Commande'}}
      big-title(title="Détail Commande")
      .row
         .col-8
            .row
               .col-12
                  table-layout(:head-table="tableItems", empty-text='Pas de commande', :data='order' key="cmd-header")
                     tr
                        td {{client.id}}
                        td {{ order.cmd_number}}
                        td {{ order.date_cmd | humane_date}}
                        td
                           strong {{client.nom}}
                        td
                           .form-group(v-if="!order.is_canceled")
                              .form-check
                                 input.form-check-input(id="annuler" type="checkbox" v-model="isAnnulate")
                                 label.form-check-label.text-primary(for="annuler") Annuler
                           p(v-else).text-danger EST ANNULÉ

               .col-12(v-if="order.status === 'OUT'")
                  h4.text-uppercase.text-secondary Détail des produits
                  hr
                  table-layout(:head-table="detailItems", theme='bg-primary text-white', empty-text='Pas de Detail', :data='products' key="cmd-detail")
                     tr(v-for='(p,i ) in products')
                        td {{i+1}}
                        td {{p==null ? null: p.reference}}
                        td {{p==null ? null: p.name}}
                        td
                           ul.list-group.list-group-horizontal
                              li.list-group-item.m-1.p-0.w-50 {{p.pivot.qte}}
                              li.list-group-item.my-1.p-0.bg-success
                              li.list-group-item.m-1.p-0.w-50.bg-success.text-white {{p.pivot.qte_rapport}}
                              li.list-group-item.my-1.p-0.bg-warning
                              li.list-group-item.my-1.p-0.bg-warning
                              li.list-group-item.m-1.p-0.w-50.bg-primary.text-white {{p.pivot.qte * p.pivot.qte_rapport}}
                        td {{p==null ? null: p.pivot.discount}}
                        td {{p==null ? null: p.pivot.price}}
                        td {{ (p.pivot.price - p.pivot.discount )* p.pivot.qte * p.pivot.qte_rapport}}
                     tr.segment
                        td(:colspan="detailItems.length -1") TOTAL
                        td.bg-primary.text-white {{orderTotal}} DH
               .col-12(v-else)
                  table-layout(:head-table="['#','Produit','Pointure','Quantité','Remise','Prix','Mantant']", theme='bg-primary text-white', empty-text='Pas de Detail', :data='product_size' key="cmd-detail")
                     tr(v-for=" (s,i) in product_size")
                        td {{i+1}}
                        td {{s.product | getProductName}}
                        td {{s.size | getByChoice('size')}}
                        td {{s.pivot | getByChoice('qte')}}
                        td {{s.pivot | getByChoice('discount')}}
                        td {{s.pivot | getByChoice('price')}} DH
                        td.font-weight-bolder {{sumOfArticle(s.pivot)}} DH
                     tr.segment
                        td(colspan="6") TOTAL
                        td.font-weight-bolder.bg-primary.text-white {{count}} DH

         .col(v-if="isAnnulate")
            .segment.m-0
               h4.text-secondary.text-uppercase Annuler cette Commande !
               hr
               .form-group
                  label Description
                  textarea.form-control(v-model="description")
               .form-group.text-right
                  button.btn.btn-warning.text-white(@click="annulateOrder") ANNULER

</template>

<script>
   import TableLayout from "@/components/layouts/TableLayout";
   import BigTitle from "@/components/layouts/BigTitle";

   export default {
      name: "DetailOrder",
      components: {BigTitle, TableLayout},
      data() {
         return {
            tableItems: ['ID', 'Livrison', 'Date', 'Client', 'ACTIONS'],
            detailItems: ['#', 'Reference', 'Designation', 'Quantité', 'Remise', 'Prix', 'Mantant'],
            order: {
               cmd_number: '',
               date_cmd: '',
               is_canceled: 0,
            },
            products: [],
            product_size: [],
            count: 0,
            payments: [],
            client: {
               id: '',
               nom: '',
            },
            orderTotal: 0,
            paymentTotal: 0,
            isAnnulate: false,
            description: '',

         }
      },
      mounted() {
         this.getOrder()
      },
      methods: {
         getOrder() {
            const id = this.$route.params.id;
            axios.get(`/api/orders/${id}`)
               .then(({data}) => {
                  const {product, client, product_size} = data;

                  this.client = client;
                  this.products = product;
                  this.product_size = product_size;

                  if (product.length > 0)
                     _.forEach(product, (p) => {
                        this.orderTotal += (p.pivot.price - p.pivot.discount) * p.pivot.qte * p.pivot.qte_rapport;
                     });
                  if (product_size.length > 0)
                     _.forEach(product_size, (s) => {
                        this.count += this.sumOfArticle(s.pivot)
                     });

                  this.order = data
               })
               .catch(err => {
                  console.log(err.response)
                  this.$router.push("/404")
                  this.$notification.error("cette commande n'existe pas !")
               })
         },
         annulateOrder() {
            const id = this.$route.params.id;

            axios.post(`/api/orders/annulate/${id}`, {'description': this.description})
               .then(({data}) => {
                  console.log(data)
               })
               .catch(err => {
                  console.log(err.response)
               })
         },
         sumOfArticle(p) {
            return _.cloneDeep((p.price - p.discount) * p.qte);
         },
         getTotal(p) {
            this.count += this.sumOfArticle(p);
            return this.count
         }
      },
      watch: {
         product_size: function (p) {

         }
      }
   }
</script>

<style scoped>
   .segment {
      border-bottom: 2px orange solid;
   }
</style>
