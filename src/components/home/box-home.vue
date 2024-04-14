<template>
    <div class="panel-body">
        <div class="card component-card-home" :style="{ backgroundColor: props.color }">
            <div class="card-body">
                <img :src="'/assets/images/home/' + props.image" alt="">
                <h5 class="card-user_name text-uppercase pt-3">{{ $t(props.title) }}</h5>
            </div>
        </div>
    </div>
</template>

<script setup>
import "../../assets/sass/components/cards/card.scss";
const props = defineProps({
    title: {
        default: 'Recherche contacts',
        type: String
    },
    href: {
        type: String
    },
    color:{
        type: String,
        default: '#00000029'
    },
    image:{
        type: String,
        default: 'empty.png'
    }
});
</script>

<script>
import emptyPicture from "../common/svg/empty-picture.vue";

export default {
    components: {
        emptyPicture
    }
};
</script>
