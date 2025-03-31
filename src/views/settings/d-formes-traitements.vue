<template>
    <d-base-page>
        <template v-slot:title>
            <d-page-title :icon="'settings'" :title="'Settings'"></d-page-title>
        </template>
        <template v-slot:header>
            <div class="panel br-6 p-2 layout-top-spacing mt-3">
                <div class="panel-body border-tab tabs">
                    <ul class="nav nav-tabs mt-1" id="border-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" href="#special-shape" role="tab" aria-controls="border-home" aria-selected="true">
                                Formes spéciales
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#special-treatments" role="tab" aria-controls="border-profile" aria-selected="false">
                                Traitements spéciaux
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content mb-4" id="border-tabsContent">
                        <div class="tab-pane fade show active" id="special-shape" role="tabpanel" aria-labelledby="border-home-tab">
                            <d-special-shape></d-special-shape>
                        </div>
                        <div class="tab-pane fade" id="special-treatments" role="tabpanel" aria-labelledby="border-home-tab">
                            <d-special-treatment></d-special-treatment>
                        </div>
                        
                    </div>
                </div>
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
    import dSpecialTreatment from "@/components/settings/d-special-treatment.vue";
    import DSpecialShape from "@/components/settings/d-special-shape.vue";

    useMeta({ title: "Settings" });
</script>

<style lang="scss">
    .pill-justify-right .nav-pills .nav-link.active{
        background-color: #0a0d26;
    }
</style>
