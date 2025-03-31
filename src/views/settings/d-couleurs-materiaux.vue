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
                            <a class="nav-link active" data-bs-toggle="tab" href="#color" role="tab" aria-controls="border-home" aria-selected="true">
                                Couleurs
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#dominant-colors" role="tab" aria-controls="border-profile" aria-selected="false">
                                Couleurs dominantes
                            </a>
                        </li> 
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#materials" role="tab" aria-controls="border-profile" aria-selected="false">
                                Matériaux
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#material-prices" role="tab" aria-controls="border-profile" aria-selected="false">
                                Prix des matériaux
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content mb-4" id="border-tabsContent">
                        <div class="tab-pane fade show active" id="color" role="tabpanel" aria-labelledby="border-home-tab">
                            <d-color></d-color>
                        </div>
                        <div class="tab-pane fade" id="dominant-colors" role="tabpanel" aria-labelledby="border-home-tab">
                            <d-dominant-color></d-dominant-color>
                        </div>
                        <div class="tab-pane fade" id="materials" role="tabpanel" aria-labelledby="border-home-tab">
                        </div>
                        <div class="tab-pane fade" id="material-prices" role="tabpanel" aria-labelledby="border-home-tab">
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
    import DColor from "@/components/settings/d-color.vue";
    import DDominantColor from "@/components/settings/d-dominant-color.vue";

    useMeta({ title: "Settings" });
</script>

<style lang="scss">
    .pill-justify-right .nav-pills .nav-link.active{
        background-color: #0a0d26;
    }
</style>
