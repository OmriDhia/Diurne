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
                            <a class="nav-link active" data-bs-toggle="tab" href="#collection-group" role="tab" aria-controls="border-home" aria-selected="true">
                                Groupes de collections
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#carpet-collection" role="tab" aria-controls="border-profile" aria-selected="false">
                                Collections de tapis
                            </a>
                        </li> 
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#collection-group-price" role="tab" aria-controls="border-profile" aria-selected="false">
                                Prix des groupes de collections
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content mb-4" id="border-tabsContent">
                        <div class="tab-pane fade show active" id="collection-group" role="tabpanel" aria-labelledby="border-home-tab">
                            <d-collection-group></d-collection-group>
                        </div>
                        <div class="tab-pane fade" id="carpet-collection" role="tabpanel" aria-labelledby="border-home-tab">
                            <d-carpet-collection></d-carpet-collection>
                        </div>
                        <div class="tab-pane fade" id="collection-group-price" role="tabpanel" aria-labelledby="border-home-tab">
                            <d-collection-group-price></d-collection-group-price>
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
    import dCollectionGroup from "@/components/settings/d-collection-group.vue";
    import dCollectionGroupPrice from "@/components/settings/d-collection-group-price.vue";
    import DCarpetCollection from "@/components/settings/d-carpet-collection.vue";

    useMeta({ title: "Settings" });
</script>

<style lang="scss">
    .pill-justify-right .nav-pills .nav-link.active{
        background-color: #0a0d26;
    }
</style>
