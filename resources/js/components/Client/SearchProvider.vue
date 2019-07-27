<template lang="pug">
    #search-client
        h5.text-success.mb-1 donnez le client de ce nouveau produit
        .dropdown-divider
        form.mb-3
            .input-group.m3
                input.form-control(v-model="searchTxt" @input="filter" placeholder="Recherche par Societe" autofocus)
                .input-group-append
                    button.btn.btn-outline-success
                        i( class="fa fa-search" aria-hidden="true")
                        strong Suivant
        .list-group.list-group-flush(v-if="clients")
            a.list-group-item.list-group-item-action.text-white.bg-secondary.text-center.text-uppercase
                strong {{ clients.length === 0 ? "No Client existe !" : "listes des client"}}
            a.list-group-item.list-group-item-action( v-for="p in clients", @click="addCheck" :id="p.id" ) {{p.steName}}
            router-link.list-group-item.text-success.text-center(v-if="clients.length === 0" :to="{name:'newClient'}") Ajouter nouveau client (Clic ici) !

</template>

<script>
    export default {
        name: "SearchClient",
        data() {
            return {
                searchTxt: '',
                clients: null,
                client: null,
                mySpan: `<span class="float-right text-success">+</span>`
            }
        },
        methods: {
            filter() {
                axios.get('/api/search', {
                    params: {
                        client: this.searchTxt
                    }
                }).then(res => {
                    this.clients = res.data

                }).catch(err => {
                    console.log(err.response)
                })
            },
            // Check with Remove !!
            addCheck(e) {
                const el_id = e.target.id
                this.createEl(el_id)
                this.client = _.find(this.clients, el => el.id === parseInt(el_id))
                this.$emit('client', this.client)
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
