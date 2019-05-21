<template>
    <div id="Users">
        <h1 class="text-info m-4 text-lg-center">Gestion des Utilisateurs</h1>
        <div class="dropdown-divider"></div>
        <h2 class="h2 text-info m-3">Listes des utilisateur : </h2>
        <ag-grid-vue style="width: 982px; height: 500px;"
                     class="ag-theme-balham"
                     :columnDefs="columnDefs"
                     :rowData="rowData">
        </ag-grid-vue>
    </div>
</template>

<script>
    import {AgGridVue} from "ag-grid-vue";

    export default {
        name: "Users",
        data() {
            return {
                columnDefs: null,
                rowData: null,
                gridOptions: null,
                gridApi: null,
                gridColumnApi:null,
                defaultColDef:null,
            }
        },
        beforeMount() {
            this.gridOptions = {};
            this.columnDefs = [
                {headerName: 'id', field: 'id', sortable: true, filter: true,width:80},
                {headerName: 'username', field: 'username', width:180,sortable: true, filter: true},
                {headerName: 'first_name', field: 'first_name', width:180,sortable: true, filter: true},
                {headerName: 'last_name', field: 'last_name', width:180,sortable: true, filter: true},
                {headerName: 'type_user', field: 'type_user', width:180,sortable: true, filter: true},
                {headerName: 'Actions', field: 'Actions', width:180,sortable: false, filter: false},
            ];

            fetch('../api/auth/allUsers')
                .then(result => result.json())
                .then(rowData => this.rowData = rowData);
            this.defaultColDef = { resizable: true };
        },
        components: {AgGridVue},
        mounted() {
            this.gridApi = this.gridOptions.api;
            this.gridColumnApi = this.gridOptions.columnApi;
            this.sizeToFit();
        },
        methods: {
            sizeToFit() {
                this.gridApi.sizeColumnsToFit();
            },
        }
    }
</script>

<style lang="scss">
    @import "../../../../node_modules/ag-grid-community/dist/styles/ag-grid.css";
    @import "../../../../node_modules/ag-grid-community/dist/styles/ag-theme-balham.css";
    /*@import "../../../../node_modules/ag-grid-community/dist/styles/ag-theme-bootstrap.css";*/
</style>
