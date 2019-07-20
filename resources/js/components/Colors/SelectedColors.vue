<template lang="pug">
    #selected-colors
        .row.mt-3.justify-content-center.align-items-center
            .col-5.h-limit.segment
                ul.list-group(v-for="c in initColors")
                    li.list-group-item.d-flex.justify-content-between.align-items-center.list-group-item-action(v-on:dblclick="switchColor(c.id,true)")
                        | {{c.name}}
                        span.rounded-circle(:style="{backgroundColor:c.color}")
            .col-1.m-0
                button.btn-success.btn.btn-sm
                    i.fa.fa-angle-right
            .col-5.h-limit.segment
                ul.list-group(v-for="s in selectColors")
                    li.list-group-item.d-flex.justify-content-between.align-items-center.list-group-item-action(v-on:dblclick="switchColor(s.id,false)")
                        | {{s.name}}
                        span.rounded-circle(:style="{backgroundColor:s.color}")

</template>

<script>
    export default {
        name: "SelectedColors",
        props: {
            newColor: {
                type: Object,
                default: {name: 'salam'}
            }
        },
        data() {
            return {
                initColors: [],
                selectColors: [],
            }
        },
        mounted() {
            this.getColors()
        },
        watch: {
            newColor: function (val) {
                this.initColors.push(val)
            }
        },
        methods: {
            getColors() {
                axios.get('/api/colors').then(({data}) => {
                    this.initColors = data
                })
            },
            switchColor(id, inverse) {
                if (inverse)
                    this.selectColors.push(..._.remove(this.initColors, (c) => c.id === id))
                else
                    this.initColors.push(..._.remove(this.selectColors, (d) => d.id === id))
                this.$emit('giveColors', this.selectColors)
            }
        }
    }
</script>

<style scoped>
    span {
        height: 20px;
        width: 20px;
        box-shadow: 0 1px 1px 0 rgba(60, 64, 67, .08), 0 1px 3px 1px rgba(60, 64, 67, .16);
    }

    .h-limit {
        height: 300px;
        overflow: auto;
    }

    li {
        cursor: pointer;
    }
</style>
