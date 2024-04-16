<template>
    <div class="layout-px-spacing">
        <d-page-error :code="code" :message="message"></d-page-error>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import dPageError from '../../components/base/d-page-error.vue';
import { useRoute } from 'vue-router';

const route = useRoute();
const code = parseInt(route.params.code);
const message = ref(null)

switch (code) {
    case 404:
        message.value = "La page que vous avez demandée n'a pas été trouvée.";
        break;
    case 401:
        message.value = "Vous n'avez pas les droits nécessaires pour accéder à cette page.";
        break;
    case 500:
        message.value = "Internal server error.";
        break;
    default:
        break;
}
</script>
