<template>
    <div>
        <!--  BEGIN NAVBAR  -->
        <Header></Header>
        <!--  END NAVBAR  -->

        <!--  BEGIN MAIN CONTAINER  -->
        <div class="main-container" id="container" :class="[!$store.state.is_show_sidebar ? 'sidebar-closed sbar-open' : '', $store.state.menu_style === 'collapsible-vertical' ? 'collapsible-vertical-mobile' : '']">
            <!--  BEGIN OVERLAY  -->
            <div class="overlay" :class="{ show: !$store.state.is_show_sidebar }" @click="$store.commit('toggleSideBar', !$store.state.is_show_sidebar)"></div>
            <div class="search-overlay" :class="{ show: $store.state.is_show_search }" @click="$store.commit('toggleSearch', !$store.state.is_show_search)"></div>
            <!-- END OVERLAY -->

            <!--  BEGIN SIDEBAR  -->
            <Sidebar></Sidebar>
            <!--  END SIDEBAR  -->
            <div id="content-loader" class="main-content mt-5" v-if="loading">
                <div class="loader multi-loader mx-auto"></div>
            </div>
            <!--  BEGIN CONTENT AREA  -->
            <div id="content" class="main-content  mt-5" v-else>
                <router-view></router-view>

                <!-- BEGIN FOOTER -->
                <Footer></Footer>
                <!-- END FOOTER -->
            </div>
            <!--  END CONTENT AREA  -->
            
            
        </div>
    </div>
</template>

<script setup>
    import Header from "../components/layout/header.vue";
    import Sidebar from "../components/layout/sidebar.vue";
    import Footer from "../components/layout/footer.vue";
    import { ref } from "vue";
    import Store from "../store/index";

    const loading = ref(Store.getters.loading);
</script>
