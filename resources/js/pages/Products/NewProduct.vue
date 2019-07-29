<template>
    <div class="container" id="new-product">
        <product-base/>
    </div>
</template>

<script>
    import ProductBase from "@/components/Products/ProductBase";

    export default {
        name: "NewProduct",
        methods: {
            getImg(img) {
                try {
                    return `${process.env.MIX_APP_URL}/storage/${img}`;
                } catch (e) {
                    // return require("../assets/undefined.svg");
                }
            },

            insert() {
                const formData = new FormData()
                const images = this.$refs.portraits
                const colors = this.colorsId

                formData.append('img', images.file)

                formData.append('id', this.id)

                formData.append('reference', this.refr)
                formData.append('name', this.name)
                formData.append('description', this.desc)
                formData.append('remark', this.note)
                formData.append('color_id', this.color);
                formData.append('unit_id', this.unity)
                formData.append('type', this.type)
                formData.append('provider_id', this.provider.id)
                formData.append('alertQte', this.alertQte)
                formData.append('subcategory_id', this.subcategory_id)
                formData.append('rapport', this.rapport)

                for (let i = 0; i < colors.length; i++) {
                    console.log(colors[i])
                    formData.append('colors[]', colors[i])
                }

                axios.post('/api/products', formData)
                    .then(res => {
                        console.log(res.data)
                    })
                    .catch(err => console.log(err.response))
            },
        },
        mounted() {
            //pour determiner si l'etat de la vue en mode : Ajout ou modification
            //si id == null => Ajout
            //si id != null => Modification
            const id = this.$route.params['id'];
            if (this.$route.params['id'] !== undefined) {
                this.isNew = false
                this.id = id
                axios.get(`/api/products/${id}`)
                    .then(res => {
                        const data = res.data
                        this.provider = data.provider
                        this.name = data.name
                        this.refr = data.reference
                        this.desc = data.description
                        this.note = data.remark
                        this.color = data.color.id
                        // this.colors = data.colors
                        this.unity = data.unit.id
                        // this.unities = data.unities
                        this.type = data.type
                        this.alertQte = data.alertQte
                        this.id = data.id
                        this.rapport = data.rapport
                        this.setRapport(data.unit.name)
                        this.img = data.img
                        this.subcategory_id = data.subcategory.id
                        this.img_url = `${process.env.MIX_APP_URL}/storage/${this.img}`
                        // this.$refs.portraits.file = data.img
                    })
                    .catch(err => {
                        // this.$notification.error("Ce produit n'existe pas !")
                        this.$notification.error(err)
                        // this.$router.push('/404')
                    })

            } else {
                this.provider = null;
                this.isNew = true
                this.id = 0
            }
        },
        components: {ProductBase,}
    }

</script>

