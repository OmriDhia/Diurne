<template>
    <div class="col-auto">
        <button :class="['btn', 'ms-0', 'btn-outline-custom', buttonClass]" v-on:click="$emit('click',$event)">
            {{ label }}
            <vue-feather :type="icon" size="14"></vue-feather>
        </button>
    </div>
</template>

<script>
    import VueFeather from 'vue-feather';
    export default {
        name: 'buttonOutlined',
        components:{
            VueFeather 
        },
        props: {
            label: {
                type: [String,null],
                required: true
            },
            buttonClass: {
                type: [String,null],
                default: ''
            },
            icon: {
                type: String,
                required: true
            },
        },
    };
</script>
