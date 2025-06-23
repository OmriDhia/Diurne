<template>
    <div class="row layout-top-spacing justify-content-center align-items-center d-flex">
        <div class="col-auto" v-if="icon">
            <span v-if="icon === 'carpet' "><img src="/assets/images/menu/icon-tapis.png"></span>
            <vue-feather :type="icon" :stroke="color" :size="size" v-else></vue-feather>
        </div>
        <div class="col-auto">
            <h2 class="text-uppercase mb-0">{{ title }}</h2>
        </div>
    </div>
</template>
<script>
import VueFeather from 'vue-feather';

export default {
    components:{
        VueFeather
    },
    props: {
        icon: {
            type: [String, null],
        },
        title: {
            type: [String, null],
        },
        size: {
            type: Number,
            default: 36
        },
        color: {
            type: String,
            default: 'black'
        }
    },
};
</script>
