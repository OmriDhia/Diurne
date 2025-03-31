<template>
    <d-base-page>
        <template v-slot:title>
        </template>
        <template v-slot:header>
            <d-page-title :icon="'settings'" :title="'Settings'"></d-page-title>
            <div class="panel br-6 p-2 layout-top-spacing mt-3">
                <d-payment-type></d-payment-type>
            </div>
        </template>

        <template v-slot:body>
            <d-contact-list v-if="type === 'contact'"></d-contact-list>
            <d-event-list v-if="type === 'event'"></d-event-list>
        </template>

    </d-base-page>
</template>

<script setup>

import '@/assets/sass/scrollspyNav.scss';
import { useMeta } from "/src/composables/use-meta";
import '@/assets/sass/components/tabs-accordian/custom-tabs.scss';
import dBasePage from "@/components/base/d-base-page.vue";
import dPageTitle from "@/components/common/d-page-title.vue";
import DPaymentType from "@/components/settings/d-payment-type.vue";

useMeta({ title: "Types de paiement" });

</script>