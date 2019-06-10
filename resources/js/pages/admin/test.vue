<template>
    <div id="UserNew">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#/" class="">Home</a></li>
            <li class="breadcrumb-item"><a href="#/forms" class="open active">Forms</a></li>
            <li class="breadcrumb-item"><span class="active">Basic Forms</span></li>
        </ol>
        <!--                            large Input toute la taille du contenant -->
        <div role="group">
            <label for="input">Input : </label>
            <input id="input" placeholder="" class="form-control">
        </div>
        <!--                            large Input toute la taille du contenant -->
        <!--large Inputs toute la taille du contenant-->
        <div class="row">
            <div class="col-sm-8">
                <fieldset role="group" class="b-form-group form-group">
                    <div role="groue">
                        <label for="input1">input 1 :</label>
                        <input type="text" placeholder="Enter your city" id="input1"
                               class="form-control">
                    </div>
                </fieldset>
            </div>
            <div class="col-sm-4">
                <fieldset role="group" class="b-form-group form-group">
                    <div role="group">
                        <label for="input2">input 2 :</label>
                        <input type="text" placeholder="Enter your city" id="input2"
                               class="form-control">
                    </div>
                </fieldset>
            </div>
        </div>
        <!--large Inputs toute la taille du contenant-->
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class='card'>
                        <div class="card-header">
                            <div>
                                <strong>Titre </strong>
                                <small>sous titre</small>
                            </div>
                        </div>
                        <div class="card-body">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import VueBreadcrumbs from 'vue-2-breadcrumbs';

    export default {
        name: "test",
        data() {
            return {}
        },
        components: VueBreadcrumbs
    }

</script>

<style scoped>

</style>
