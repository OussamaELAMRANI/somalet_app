<template>
    <div class="modal fade" :id="id" role="dialog">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{title}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <divider>
                        <slot></slot>
                    </divider>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                    <button type="button" class="btn btn-primary">Imprimer</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Divider from "@/components/Divider";

    export default {
        name: "ModalDetail",
        components: {Divider},
        props: {
            id: {
                default: 'infos'
            },
            title: {
                default: 'Detail d\'un Produit'
            },
        }
    }
</script>

<style scoped>

</style>
