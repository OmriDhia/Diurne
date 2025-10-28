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
                            <a class="nav-link active" data-bs-toggle="tab" href="#tarif-groups" role="tab" aria-controls="border-home" aria-selected="true">
                                Groupes tarifaires
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#currency" role="tab" aria-controls="border-profile" aria-selected="false">
                                Devises
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#conversions" role="tab" aria-controls="border-profile" aria-selected="false">
                                Conversions
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#manufacturer-price-grid" role="tab" aria-controls="border-profile" aria-selected="false">
                                Grille tarifaire fournisseur
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content mb-4" id="border-tabsContent">
                        <div class="tab-pane fade show active" id="tarif-groups" role="tabpanel" aria-labelledby="border-home-tab">
                            <d-tarif-group></d-tarif-group>
                        </div>
                        <div class="tab-pane fade" id="currency" role="tabpanel" aria-labelledby="border-home-tab">
                            <d-devise></d-devise>
                        </div>
                        <div class="tab-pane fade" id="conversions" role="tabpanel" aria-labelledby="border-home-tab">
                            <d-conversion></d-conversion>
                        </div>
                        <div class="tab-pane fade" id="manufacturer-price-grid" role="tabpanel" aria-labelledby="border-home-tab">
                            <d-manufacturer-price-grid></d-manufacturer-price-grid>
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
    import dConversion from "@/components/settings/d-conversion.vue";
    import DTarifGroup from "@/components/settings/d-tarif-group.vue";
    import DDevise from "@/components/settings/d-devise.vue";
    import DManufacturerPriceGrid from "@/components/settings/d-manufacturer-price-grid.vue";

    useMeta({ title: "Settings" });
</script>

<style lang="scss">
    .pill-justify-right .nav-pills .nav-link.active{
        background-color: #0a0d26;
    }
</style>
