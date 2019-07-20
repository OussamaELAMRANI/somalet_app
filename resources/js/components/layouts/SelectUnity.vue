<template lang="pug">
    .row.bg-light
        .col
            label(for='unity') Selectionnez l&apos;unit&eacute;
            select#unity.form-control(v-on:change='getUnity')
                option(selected='selected' disabled='disabled') Selectionnez...
                option(@click="isNewUnity=true") NOUVEAU ++
                option(v-for=' c in unities' :id="'unity_'+c.id" :selected='(c.id == unity)')
                    | {{ c.name }}
        .col(v-if='isNewUnity')
            label(for='new-unity') Nouvelle Unite
            input#new-unity.form-control(type='text' placeholder='Unite ...' v-model='unity' v-on:keyup.enter='addUnity')
        // .col(v-if='hasRapport')
            label(for='rapport') {{ labelRapport }}
            input#rapport.form-control(type='text' :placeholder='labelRapport' v-model='rapport')
        .dropdown-divider.m-3

</template>

<script>
    export default {
        name: "SelectUnity",
        data() {
            return {
                isNewUnity: false,
                hasRapport: true,
                unity: '',
                rapport: '',
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
                    this.newUnity = true
                } else {
                    this.newUnity = false
                    this.unity = parseInt(id[1])
                }
                this.setRapport(val);
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
                    _this.newUnity = false;
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
        }
    }
</script>

<style scoped>

</style>
