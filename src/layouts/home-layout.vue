<template>
    <div>
        <!--  BEGIN NAVBAR  -->
        <Header></Header>
        <!--  END NAVBAR  -->

        <!--  BEGIN MAIN CONTAINER  -->
        <div class="main-container" id="container">
            <div class="col-9 m-auto mt-home">
                <router-view></router-view>
            </div>
        </div>
    </div>
</template>

<script setup>
    import Header from "../components/layout/header.vue";
</script>
