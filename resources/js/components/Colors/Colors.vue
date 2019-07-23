<template lang="pug">
    #colorPack
        .segment.clearfix
            label Nom de couleur
            input.form-control(type='text' placeholder='Nouveau couleur ...' v-model='name')
            p Selectionnez une couleur
            .dropdown-divider
            Chrome(v-model='color')
            .dropdown-divider
            button.btn.btn-success.float-right(@click="addColor") Ajouter
</template>

<script>
    import ColorPicker from '@radial-color-picker/vue-color-picker';
    import StatusIndicator from 'vue-status-indicator'
    import {Chrome} from 'vue-color'

    export default {
        name: "Colors",
        components: {
            Chrome, ColorPicker, StatusIndicator
        },
        data() {
            return {
                color: {
                    hex: '#194d33',
                },
                name: ''
            };
        },
        methods: {
            addColor() {
                axios.post('/api/colors',
                    {
                        name: this.name,
                        color: this.color.hex,
                    })
                    .then(({data}) => {
                        if (data.hasColor)
                            this.$emit('getColors', data, true)
                        else
                            this.$emit('getColors', data, false)
                    })
            }
        },
    }
</script>

<style scoped>
    @import '~@radial-color-picker/vue-color-picker/dist/vue-color-picker.min.css';

    .segment {
        border-top: 2px solid #000000;
        border-bottom: 2px solid gray;
    }
</style>
