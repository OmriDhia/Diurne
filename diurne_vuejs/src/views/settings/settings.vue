<template>
    <d-base-page>
        <template v-slot:title>
            <d-page-title :icon="'settings'" :title="'Settings'"></d-page-title>
        </template>
        <template v-slot:header>
            <div class="panel br-6 p-2">
                <div class="panel-body border-tab tabs">
                    <ul class="nav nav-tabs mt-3" id="border-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" href="#collections" role="tab" aria-controls="border-home" aria-selected="true">
                                Collections
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#model" role="tab" aria-controls="border-profile" aria-selected="false">
                                Model
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#carrier" role="tab" aria-controls="border-profile" aria-selected="false">
                                Transporteur
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link"  data-bs-toggle="tab" href="#currency" role="tab" aria-controls="border-contact" aria-selected="false">
                                Devise
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#dominant-color" role="tab" aria-controls="border-contact" aria-selected="false">
                                Couleur dominante
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#image-type" role="tab" aria-controls="border-contact" aria-selected="false">
                                Type d'image
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#materials" role="tab" aria-controls="border-contact" aria-selected="false">
                                Matérials
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#manufactury" role="tab" aria-controls="border-contact" aria-selected="false">
                                Fabricant
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content mb-4" id="border-tabsContent">
                        <div class="tab-pane fade show active" id="collections" role="tabpanel" aria-labelledby="border-home-tab">
                            <h4 class="mb-4">We move your world!</h4>
                            <p class="mb-4">
                                Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis
                                nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
                            </p>

                            <p>
                                Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis
                                nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
                            </p>
                        </div>
                        <div class="tab-pane fade" id="model" role="tabpanel" aria-labelledby="border-home-tab">
                            <d-model></d-model>
                        </div>
                        <div class="tab-pane fade" id="carrier" role="tabpanel" aria-labelledby="border-profile-tab">
                            <d-carrier></d-carrier>
                        </div>
                        <div class="tab-pane fade" id="currency" role="tabpanel" aria-labelledby="border-contact-tab">
                            <d-devise></d-devise>
                        </div>
                        <div class="tab-pane fade" id="dominant-color" role="tabpanel" aria-labelledby="border-contact-tab">
                            <p class="dropcap dc-outline-primary">
                                Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis
                                nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu
                                fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
                            </p>
                        </div>
                        <div class="tab-pane fade" id="image-type" role="tabpanel" aria-labelledby="border-contact-tab">
                            <p class="dropcap dc-outline-primary">
                                Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis
                                nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu
                                fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
                            </p>
                        </div>
                        <div class="tab-pane fade" id="materials" role="tabpanel" aria-labelledby="border-contact-tab">
                            <p class="dropcap dc-outline-primary">
                                Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis
                                nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu
                                fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
                            </p>
                        </div>
                        <div class="tab-pane fade" id="manufactury" role="tabpanel" aria-labelledby="border-contact-tab">
                            <p class="dropcap dc-outline-primary">
                                Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis
                                nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu
                                fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
                            </p>
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
    import dModel from "@/components/settings/d-model.vue";
    import dCarrier from "@/components/settings/d-carrier.vue";
    import dDevise from "@/components/settings/d-devise.vue";
    
    useMeta({ title: "Settings" });
</script>

<style lang="scss">
    .pill-justify-right .nav-pills .nav-link.active{
        background-color: #0a0d26;
    }
</style>
