<template>
    <div class="row layout-top-spacing justify-content-center align-items-center d-flex">
        <div class="col-auto" v-if="icon">
            <vue-feather :type="icon" :stroke="color" :size="size"></vue-feather>
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
