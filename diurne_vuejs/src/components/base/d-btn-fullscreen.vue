<template>
    <div class="col-auto">
        <button :class="['btn', 'ms-0', 'btn-outline-custom', buttonClass]" @click="fullscreen">
            <span class="d-flex align-items-center" v-if="isFullscreen">
                <vue-feather type="minimize" size="18"></vue-feather>
                <span class="pe-1 ps-1"> Quitter mode plein écran </span>
            </span>
            <span class="d-flex align-items-center" v-else>
                <vue-feather type="maximize" size="18"></vue-feather>
                <span class="pe-1 ps-1"> Afficher le tableau en plein écran </span>
            </span>
        </button>
    </div>
</template>

<script>
    import VueFeather from 'vue-feather';

    export default {
        name: 'buttonOutlined',
        components: {
            VueFeather
        },
        props: {
            label: {
                type: [String, null],
                required: true
            },
            buttonClass: {
                type: [String, null],
                default: ''
            },
            icon: {
                type: String,
                required: true
            },
        },
        data() {
            return {
                isFullscreen: null,
            };
        },
        methods: {
            fullscreen() {
                const el = document.getElementById('fullscreen');
                el.style.overflowX = "hidden";
                if (document.fullscreenElement) {
                    document.exitFullscreen()
                } else {
                    el.style.overflowY = "auto";
                    this.isFullscreen = true;
                    el.requestFullscreen();
                }
            }
        },
        mounted() {
            document.addEventListener("fullscreenchange",(event)=>{
                if(!document.fullscreenElement){
                    event.target.style.overflowY = "hidden";
                    this.isFullscreen = false;
                }
            })
        },
    };
</script>

<style lang="scss" scoped>
    .btn-outline-custom {
        padding: 0px !important;
        i{
            padding: 5px !important;
            margin-left: 0px !important;
        }
    }
</style>
