<template>
    <div :class="{'row': true,
    'layout-top-spacing': true,
    'align-items-center': true,
    'justify-content-between': true,
    'pe-4': true,
    [className]: true
    }">
        <div class="col-auto ps-0">
            <h6 class="panel-title w-auto">{{ title }}</h6>
        </div>
        <div class="col-auto pe-0">
            <slot name="extraBtn"></slot>
        </div>
        <div class="col-auto" v-if="link">
            <router-link v-if="link" :to="link" class="w-auto d-flex align-items-center"><vue-feather type="arrow-left" size="26"></vue-feather>Retour</router-link>
        </div>
        
        
    </div>
    <div :class="{'row': true,
    'pe-4': true,
    [className]: true
    }">
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
        className:{
            type: String,
            default: "ps-4"
        }
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
