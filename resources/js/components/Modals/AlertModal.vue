<template>
    <div class="modal fade" :id="id" tabindex="-1" role="dialog" aria-labelledby="pp">
        <div class="modal-dialog" role="">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="pp">{{title}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="alert segment">
                        <slot name="content"></slot>
                    </div>
                </div>
                <div class="modal-footer">
                    <slot name="btn"></slot>
                    <button type="button" class="btn btn-link" data-dismiss="modal">Annuler</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props:{
            id:{
                default:'exampleModal'
            },
            title:{
                default:'Titre'
            }
        },
        name: "AlertModal"
    }
</script>

<style scoped>
    .segment{
        border-top: 2px orange solid;
        margin: 0;
    }
</style>
