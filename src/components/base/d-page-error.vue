<template>
    <div class="error404 text-center">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-4 me-auto mt-5 text-md-start text-center">
                    <a href="/home" class="ms-md-5">
                        <img alt="image-404" src="/assets/images/logo/DIURNE.png" class="theme-logo" />
                    </a>
                </div>
            </div>
        </div>
        <div class="container-fluid error-content">
            <div class="">
                <h1 class="error-number">{{ props.code }}</h1>
                <p class="mini-text">Oups!</p>
                <p class="error-text mb-4 mt-1">{{ $t(props.message) }}</p>
                <a href="/" class="btn btn-primary mt-5">{{ $t('Retourner') }}</a>
            </div>
        </div>
    </div>
</template>

<script setup>
import "/src/assets/sass/pages/error/style-400.scss";

import { useMeta } from "/src/composables/use-meta";

const props = defineProps({
    code: {
        type: Number,
        default: 404
    },
    message: { 
        type: String, 
        default: "" 
    },
})

useMeta({ title: "Error " + props.code });
</script>
