<template>
    <div class="layout-px-spacing dash_1">
        <div class="row layout-top-spacing">
            <div class="col-4 layout-spacing">
                <box-home></box-home>
            </div>
            <div class="col-4 layout-spacing">
                <box-home title="Recherche contremarque"></box-home>
            </div>
            <div class="col-4 layout-spacing">
                <box-home></box-home>
            </div>
            <div class="col-4 layout-spacing">
                <box-home></box-home>
            </div>
            <div class="col-4 layout-spacing">
                <box-home></box-home>
            </div>
            <div class="col-4 layout-spacing">
                <box-home></box-home>
            </div>
            <div class="col-4 layout-spacing">
                <box-home></box-home>
            </div>
            <div class="col-4 layout-spacing">
                <box-home></box-home>
            </div>
            <div class="col-4 layout-spacing">
                <box-home></box-home>
            </div>
        </div>
    </div>
</template>

<script setup>
import { useMeta } from "/src/composables/use-meta";
useMeta({ title: "Acceuil" });
</script>

<script>
import boxHome from "./../components/home/box-home.vue";

export default {
    components: {
        boxHome
    }
};
</script>
