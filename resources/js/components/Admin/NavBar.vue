<template>
    <nav class="navbar navbar-expand-sm navbar-dark bg-dark">
        <!--        <div class="container">-->
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive"
                aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="#">Dashboard
                        <span class="sr-only">(current)</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">About</a>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                       data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Dropdown
                    </a>
                    <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                        <li class="dropdown-item">img</li>
                        <li class="dropdown-item">user Name</li>
                        <div class="dropdown-divider"></div>
                        <li class="dropdown-item">Logout</li>
                    </ul>
                </li>

            </ul>
        </div>
        <!--        </div>-->
    </nav>
</template>

<script>
    export default {
        name: "NavBar"
    }
</script>

<style scoped>

</style>
