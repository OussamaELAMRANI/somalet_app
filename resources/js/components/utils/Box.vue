<template lang="pug">
    router-link(:to="{name:link} || '#'")
        .segment.box
            .row.align-items-end
                .col-4
                    img(:src="getImage(img)", alt="img" height='80px')
                .col-8.text-center.ar
                    h5 {{title}}
                    p.text-secondary {{subtitle}}
            .row
                .col.text-right
                    span.badge.badge-primary.mr-1 0
                    span.badge.badge-success 0
</template>

<script>
    export default {
        name: "Box",
        props: ['img', 'title', 'subtitle', 'link'],
        methods: {
            getImage(img) {
                return `/storage/${img}`
                // return `${process.env.MIX_APP_URL}/storage/${img}`
            }
        },
    }
</script>

<style scoped>
    .box {
        height: 160px;
    }

    .box:hover {
        cursor: pointer;
        background: #eef4fa;
    }

</style>
