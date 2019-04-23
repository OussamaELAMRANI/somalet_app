<template lang="pug">
    .step-box(:class="classe" @click="sendIt")
        img(:src="'/storage/boxes/'+image" )
        .dropdown-divider
        h5 {{title}}

</template>

<script>
    export default {
        name: "StepBox",
        props: {
            image: {
                type: String,
            },
            title: {
                type: String,
                default: 'Step 1'
            },
            classe: {
                type: String,
                default: 'normal-box'
            },
            to:{}
        },
        methods:{
            sendIt(){
                this.$emit('step', this.to)
            }
        }
    }
</script>

<style scoped>
    .step-box {
        position: relative;
        background: #fff;
        box-shadow: 0 1px 2px 0 rgba(34, 36, 38, .15);
        margin: 1rem 0;
        padding: 1em 1em;
        border-radius: .28571429rem;
        border: 1px solid rgba(34, 36, 38, .15);
        height: 150px;
        width: 150px;
        text-align: center;
        cursor: pointer;
    }

    img {
        height: 80px;
    }

    h5 {
        font-size: 1em;
    }

    .active-box {
        background-color: #6c757d38;
        color: #fff;
    }

    .normal-box {
        background-color: #fff;
    }
</style>
