<template lang="pug">
    .row
        .col
            label {{label}}
            input(:type="type" v-model="mode" class="form-control")
</template>

<script>
    export default {
        name: "DynamicForm",
        props: {
            type: {
                default: 'text'
            },
            label: {
                default: 'label'
            },
        },
        data() {
            return {
                mode:''
            }
        }


    }
</script>

<style scoped>

</style>
