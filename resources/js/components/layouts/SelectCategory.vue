<template lang="pug">
    .col
            label(for='category') Selectionnez la categorie
            select#category.form-control(v-on:change='getCategory')
                option(selected='selected' disabled='disabled') Selectionnez...
                template(v-for='t in categories')
                    optgroup(:label='t.category_name')
                        option(v-for='sub in t.sub_categories' :id="'subcategory_'+sub.id" :selected='(sub.id == subcategory_id)')
                            | {{sub.sub_category}}
</template>

<script>
    export default {
        name: "SelectCategory",
        data() {
            return {
                categories: null,
                subcategory_id: null,
                newSubCategory: false,
            }
        },
        methods: {
            getCategory(ev) {
                let val = ev.target.options[ev.target.options.selectedIndex].value
                let id = ev.target.options[ev.target.options.selectedIndex].id
                id = id.split('_')
                if (val === 'NOUVEAU ++') {
                    this.subcategory_id = null
                    this.newSubCategory = true
                } else {
                    this.newSubCategory = false
                    this.subcategory_id = parseInt(id[1])
                    this.$emit('sendSubCategory', this.subcategory_id)
                }
            },

        },
        mounted() {
            axios.get('/api/categories').then(res => {
                this.categories = res.data
            })
        }
    }
</script>

<style scoped>

</style>
