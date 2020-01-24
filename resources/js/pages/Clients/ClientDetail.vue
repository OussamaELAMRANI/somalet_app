<template lang="pug">
   #client_detail.container
      .row
         .col-12
            .card
               .card-header
                  h4.text-uppercase
                     | - Client:
                     strong.text-primary
                        | {{' '+client.nom}}
                        | ({{client.id}})
               .card-body
                  .row
                     .col-2
                        h4.text-secondary.text-uppercase.text-right Remises
                     .col
                        hr
                  .row
                     .col-9
                        table-layout(:head-table="hRemise", theme='bg-success text-white', :data="discounts")
                           tr(v-for="(p,i) in discounts")
                              td {{p.name}}
                              td {{p.pivot.discount}}
                              td
                                 button.btn.btn-sm.btn-outline-danger.mx-1.rounded-pill.shadow(@click="deleteDiscount(p.id)")
                                    i.fa.fa-trash
                     .col-3.segment.shadow.mt-0
                        .form-group
                           label Désignation
                           cool-select(:items='prods' @search='onSearch' :loading='loading'
                              item-text='name' name='produit' v-model='newDiscount.product_id' item-value='id')
                           template(slot='item' slot-scope='{ item:p }')
                              .d-flex
                                 div
                                    p {{ p.name }} -
                                       span.text-primary.font-weight-bolder {{ p.reference }}
                           template(slot='no-data') {{ noData ? "Aucun produit trouvée" :"Chercher par nom de produit" }}
                        .form-group
                           label Reference
                           input.form-control(type="text" placeholder="Montant en DH ..." v-model="newDiscount.discount")
                        .form-group
                           hr
                           button.btn-outline-success.btn.btn-block.rounded-pill(@click="addDiscount") Ajouter une Remise



      h4.text-uppercase.text-primary.my-2 Mouvements client
      hr
      .row.justify-content-center.text-center
         .col-4
            router-link.btn.btn-primary(:to="{name:'movementClient',params:{id: $route.params.id}}")
               i.fa.fa-list.mx-2
               |  Afficher les mouvements en detail
</template>

<script>
    import TableLayout from "@/components/layouts/TableLayout";
    import SelfBuildingSquareSpinner from "epic-spinners/src/components/lib/SelfBuildingSquareSpinner";
    import {CoolSelect} from 'vue-cool-select'

    export default {
        name: "ClientDetail",
        components: {SelfBuildingSquareSpinner, TableLayout, CoolSelect},
        data() {
            return {
                client: [],
                hRemise: ['Produit', 'Remise en (DH) ', 'Supprimer'],
                discounts: [],
                products: [],
                newDiscount: {
                    discount: null,
                    product_id: 0,

                },
                prods: Array(),
                loading: false,
                noData: false,
                client_id: this.$route.params.id
            }
        },
        created() {
            this.getDiscounting(this.client_id)
        },
        methods: {
            postDiscount(id, product_id, discount) {
                axios.post(`/api/clients/${id}/discount`, {product_id, discount})
                    .then(({data}) => {
                        console.log(data)
                        this.getDiscounting(id)
                        this.$notification.success("Remise a été bien ajouté ")
                    })
                    .catch((err) => {
                        console.log(err.response)
                    })
            },
            addDiscount() {
                const id = this.client_id
                const product_id = this.newDiscount.product_id;
                const {discount} = this.newDiscount;
                this.postDiscount(id, product_id, discount)
            },

            deleteDiscount(id) {
                this.postDiscount(this.client_id, id, 0)
            },
            getDiscounting(id) {
                axios.get(`/api/clients/${id}`)
                    .then(({data}) => {
                        const {products} = data
                        this.client = data
                        this.discounts = products
                    })
                    .catch(({response}) => {
                        console.log(response)
                    })
            },
            onSearch(search) {
                // let rejectId = ""
                const lettersLimit = 2;

                this.noData = false;
                if (search.length < lettersLimit) {
                    this.prods = [];
                    this.loading = false;
                    return;
                }
                this.loading = true;

                axios.get(`/api/products/${search}/search`, {
                    params: {
                        // client_id: this.$route.params.id
                    }
                }).then(({data}) => {
                    this.prods = data;
                    if (data.length === 0)
                        this.noData = true

                    this.loading = false;
                })
            },
        },
        computed: {}
    }
</script>

<style scoped>
   p > i {
      margin-right: 8px;
   }
</style>
