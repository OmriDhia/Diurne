<template>
    <div class="row layout-top-spacing align-items-center justify-content-between ps-4 pe-4">
        <h6 class="panel-title w-auto">{{ title }}</h6>
        <router-link v-if="link" :to="link" class="w-auto d-flex align-items-center"><vue-feather type="arrow-left" size="26"></vue-feather>Retour</router-link>
    </div>
    <div class="row ps-4 pe-4">
        <hr/>
    </div>
    
</template>
<script>
import VueFeather from 'vue-feather';

export default {
    components: {
        VueFeather
    },
    props: {
        title: {
            type: [String, null],
        },
        link: {
            type: [String, null],
        },
    },
};
</script>
<style>
    h6.panel-title{
        color: #000000;
        font-size: 20px;
        font-weight: 500;
        padding: 0px;
    }
    hr{
        border-top: 2px solid #000000;
    }
</style>
