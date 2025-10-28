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
                            <a class="nav-link"
                               :class="{ active: activeTab === 'tarif-groups' }"
                               href="#tarif-groups"
                               role="tab"
                               aria-controls="border-home"
                               :aria-selected="activeTab === 'tarif-groups'"
                               @click.prevent="setTab('tarif-groups')">
                                Groupes tarifaires
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link"
                               :class="{ active: activeTab === 'currency' }"
                               href="#currency"
                               role="tab"
                               aria-controls="border-profile"
                               :aria-selected="activeTab === 'currency'"
                               @click.prevent="setTab('currency')">
                                Devises
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link"
                               :class="{ active: activeTab === 'conversions' }"
                               href="#conversions"
                               role="tab"
                               aria-controls="border-profile"
                               :aria-selected="activeTab === 'conversions'"
                               @click.prevent="setTab('conversions')">
                                Conversions
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link"
                               :class="{ active: activeTab === 'manufacturer-price-grid' }"
                               href="#manufacturer-price-grid"
                               role="tab"
                               aria-controls="border-profile"
                               :aria-selected="activeTab === 'manufacturer-price-grid'"
                               @click.prevent="setTab('manufacturer-price-grid')">
                                Grille tarifaire fournisseur
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content mb-4" id="border-tabsContent">
                        <div class="tab-pane fade"
                             :class="{ show: activeTab === 'tarif-groups', active: activeTab === 'tarif-groups' }"
                             id="tarif-groups"
                             role="tabpanel"
                             aria-labelledby="border-home-tab">
                            <d-tarif-group></d-tarif-group>
                        </div>
                        <div class="tab-pane fade"
                             :class="{ show: activeTab === 'currency', active: activeTab === 'currency' }"
                             id="currency"
                             role="tabpanel"
                             aria-labelledby="border-home-tab">
                            <d-devise></d-devise>
                        </div>
                        <div class="tab-pane fade"
                             :class="{ show: activeTab === 'conversions', active: activeTab === 'conversions' }"
                             id="conversions"
                             role="tabpanel"
                             aria-labelledby="border-home-tab">
                            <d-conversion></d-conversion>
                        </div>
                        <div class="tab-pane fade"
                             :class="{ show: activeTab === 'manufacturer-price-grid', active: activeTab === 'manufacturer-price-grid' }"
                             id="manufacturer-price-grid"
                             role="tabpanel"
                             aria-labelledby="border-home-tab">
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
    import { ref, watch } from 'vue';
    import { useRoute, useRouter } from 'vue-router';
    import { useMeta } from "/src/composables/use-meta";
    import '@/assets/sass/components/tabs-accordian/custom-tabs.scss';
    import dBasePage from "@/components/base/d-base-page.vue";
    import dPageTitle from "@/components/common/d-page-title.vue";
    import dConversion from "@/components/settings/d-conversion.vue";
    import DTarifGroup from "@/components/settings/d-tarif-group.vue";
    import DDevise from "@/components/settings/d-devise.vue";
    import DManufacturerPriceGrid from "@/components/settings/d-manufacturer-price-grid.vue";

    useMeta({ title: "Settings" });

    const DEFAULT_TAB = 'tarif-groups';
    const VALID_TABS = ['tarif-groups', 'currency', 'conversions', 'manufacturer-price-grid'];

    const route = useRoute();
    const router = useRouter();

    const normalizeTab = (tab) => VALID_TABS.includes(tab) ? tab : DEFAULT_TAB;

    const extractRouteTab = () => {
        const rawTab = route.query.tab;
        if (Array.isArray(rawTab)) {
            return normalizeTab(rawTab[0]);
        }
        if (typeof rawTab === 'string') {
            return normalizeTab(rawTab);
        }
        return DEFAULT_TAB;
    };

    const activeTab = ref(extractRouteTab());

    watch(() => route.query.tab, () => {
        const nextTab = extractRouteTab();
        if (nextTab !== activeTab.value) {
            activeTab.value = nextTab;
        }

        const rawTab = route.query.tab;
        const rawValue = Array.isArray(rawTab) ? rawTab[0] : rawTab;
        if (typeof rawValue === 'string' && rawValue !== nextTab) {
            const targetRouteName = typeof route.name === 'string' ? route.name : 'tarification-taxes';
            const newQuery = { ...route.query, tab: nextTab };
            router.replace({ name: targetRouteName, query: newQuery });
        }
    });

    const setTab = (tab) => {
        const resolvedTab = normalizeTab(tab);
        if (resolvedTab === activeTab.value && extractRouteTab() === resolvedTab) {
            return;
        }

        activeTab.value = resolvedTab;
        const newQuery = { ...route.query, tab: resolvedTab };
        const targetRouteName = typeof route.name === 'string' ? route.name : 'tarification-taxes';
        router.replace({ name: targetRouteName, query: newQuery });
    };
</script>

<style lang="scss">
    .pill-justify-right .nav-pills .nav-link.active{
        background-color: #0a0d26;
    }
</style>
