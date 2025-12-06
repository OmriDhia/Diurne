<template>
    <div :class="[$store.state.layout_style, $store.state.menu_style, $store.getters.pageClass]">
        <component v-bind:is="layout"></component>
    </div>
</template>
<script setup>

import "./assets/sass/app.scss";

import { useMeta } from "./composables/use-meta";

useMeta({ title: "Diurne" });
</script>
<script>
// layouts
import appLayout from "./layouts/app-layout.vue";
import authLayout from "./layouts/auth-layout.vue";
import homeLayout from "./layouts/home-layout.vue";
import userService from "./Services/user-service";
import store from "./store";

export default {
    components: {
        app: appLayout,
        auth: authLayout,
        home: homeLayout,
    },
    computed: {
        layout: {
            get() {
                return store.getters.layout;
            },
            set(layout) {
                store.commit('setLayout', layout)
            }
        }
    },
    mounted(){
        const userData = userService.getUserInfo();
        userService.affectUserRoles(userData);
    }
}
</script>
