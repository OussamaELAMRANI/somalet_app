<template lang="pug">
    #provider_detail.container
        h3 Detail Fournisseur
            span.text-primary {{': ' + getDetail.steName}}
        hr
        //table-layout(:head-table="infosHeader", :data="getDetail" )
           tr
              td {{1}}
              td {{(detail == null) ? null : detail.steName}}
              td {{(detail == null) ? null : detail.numTva}}
              td {{(detail == null) ? null : detail.firstName}} {{(detail==null) ? null : detail.lastName}}

        table-layout(:head-table="porductsHeader", theme="bg-success text-white" empty-text="Sans Produits" :data="getProducts")
            tr(v-for="( p,i ) in products").text-center
                td {{i+1}}
                td
                    img(:src="'/storage/'+p.img" height="90")
                td {{p.reference}}
                td {{p.name}}
                td
                    p(v-if="(p.color.name) === null") SANS
                    p(v-else).m-0
                        .koora.rounded-pill.shadow(:style="{backgroundColor:p.color.color}")
                td {{p.unit.name}}

</template>

<script>
    import TableLayout from "@/components/layouts/TableLayout";

    export default {
        name: "ProviderDetail",
        components: {TableLayout},
        data() {
            return {
                detail: {},
                infosHeader: ['#', 'Sociéte', 'TVA', 'Nom Prénom'],
                porductsHeader: ['#', 'image', 'Reference', 'Designation', 'Couleurs', 'Unité'],
                products: {}
            }
        },
        created() {
            const id = this.$route.params.id
            console.log(id)

            axios.get(`/api/providers/${id}`)
                .then(({data}) => {
                    const {products} = data
                    console.log('------ PRODUCTS -----')
                    console.log(products)
                    console.log('------ PRODUCTS -----')
                    this.detail = data
                    this.products = products;
                    // console.log(data)
                })
                .catch(err => {
                    console.log(err.response)
                    this.$router.push('/404')
                })
        },
        computed: {
            getProducts() {
                return this.products;
            },
            getDetail() {
                return this.detail;
            }
        }
    }
</script>

<style scoped>
    .koora {
        height: 20px;
    }
</style>
