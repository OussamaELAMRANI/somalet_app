<template lang="pug">
    #search-provider
        h5.text-secondary.mb-1 Donnez le fournisseur de ce produit
        .dropdown-divider
        form.mb-3(@submit.prevent="")
            .input-group.m3
                input.form-control(v-model="searchTxt" @input="filter" placeholder="Recherche par Societe" autofocus)
                .input-group-append
                    button.btn.btn-outline-success
                        i( class="fa fa-search" aria-hidden="true")
                        strong Suivant
        .list-group.list-group-flush(v-if="providers")
            a.list-group-item.list-group-item-action.text-white.bg-secondary.text-center.text-uppercase
                strong {{ providers.length === 0 ? "No Fournisseur existe !" : "listes des fournisseur"}}
            a.list-group-item.list-group-item-action( v-for="p in providers", @click="addCheck" :id="p.id" ) {{p.steName}}
            router-link.list-group-item.text-success.text-center(v-if="providers.length === 0" :to="{name:'newProvider'}") Ajouter nouveau fournisseur (Clic ici) !

</template>

<script>
    export default {
        name: "SearchProvider",
        data() {
            return {
                searchTxt: '',
                providers: null,
                provider: null,
                mySpan: `<span class="float-right text-success">+</span>`
            }
        },
        methods: {
            filter() {
                axios.get('/api/search', {
                    params: {
                        provider: this.searchTxt
                    }
                }).then(res => {
                    this.providers = res.data

                }).catch(err => {
                    console.log(err.response)
                })
            },
            // Check with Remove !!
            addCheck(e) {
                const el_id = e.target.id
                this.createEl(el_id)
                this.provider = _.find(this.providers, el => el.id === parseInt(el_id))
                this.$emit('provider', this.provider)
            },
            createEl(id) {
                const el = document.getElementById(id)
                const span = document.createElement('span')
                const i = document.createElement('i')
                span.classList = ["float-right text-success"]
                i.classList = ["fa fa-check"]
                span.appendChild(i)
                el.appendChild(span);
            },
        }
    }
</script>

<style scoped>
    .list-group {
        height: 70vh;
        overflow: auto;
        margin-bottom: 70px;
    }
</style>
