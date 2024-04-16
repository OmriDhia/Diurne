<template>
    <!--  BEGIN SIDEBAR  -->
    <div class="sidebar-wrapper sidebar-theme">
        <nav ref="menu" id="sidebar">
            <div class="shadow-bottom"></div>

            <perfect-scrollbar class="list-unstyled menu-categories" tag="ul"
                :options="{ wheelSpeed: 0.5, swipeEasing: !0, minScrollbarLength: 40, maxScrollbarLength: 300, suppressScrollX: true }">
                <li class="menu" v-for="menu in menus" :key="menu.id">
                    <router-link to="/widgets" class="dropdown-toggle" v-if="menu.children.length === 0"
                        @click="toggleMobileMenu">
                        <div class="">
                            <span>{{ $t(menu.name)}}</span>
                        </div>
                    </router-link>
                    <template v-else>
                        <a class="dropdown-toggle" data-bs-toggle="collapse" :data-bs-target="'#'+menu.name"
                            :aria-controls="menu.name" aria-expanded="false">
                            <div class="">
                                <span>{{ $t(menu.name) }}</span>
                            </div>
                            <div>
                                <vue-feather type="chevron-right" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" stroke="currentColor"></vue-feather>
                            </div>
                        </a>

                        <ul :id="menu.name" class="collapse submenu list-unstyled" data-bs-parent="#sidebar">
                            <li v-for="child in menu.children" :key="child.id">
                                <router-link to="/" @click="toggleMobileMenu">
                                    {{ $t(child.name) }}
                                </router-link>
                            </li>
                            <li>
                                <router-link to="/index2" @click="toggleMobileMenu">
                                    {{ $t('analytics') }}
                                </router-link>
                            </li>
                        </ul>
                    </template>
                </li>
            </perfect-scrollbar>
        </nav>
    </div>
    <!--  END SIDEBAR  -->
</template>

<script setup>
import { onMounted, ref } from 'vue';
import VueFeather from 'vue-feather';
import { useStore } from 'vuex';
import userService from '../../composables/user-service';
const store = useStore();

const menu_collapse = ref('dashboard');
const menus = userService.getUserMenu();

onMounted(() => {
    const selector = document.querySelector('#sidebar a[href="' + window.location.pathname + '"]');
    if (selector) {
        const ul = selector.closest('ul.collapse');
        if (ul) {
            let ele = ul.closest('li.menu').querySelectorAll('.dropdown-toggle');
            if (ele) {
                ele = ele[0];
                setTimeout(() => {
                    ele.click();
                });
            }
        } else {
            selector.click();
        }
    }
});

const toggleMobileMenu = () => {
    if (window.innerWidth < 991) {
        store.commit('toggleSideBar', !store.state.is_show_sidebar);
    }
};
</script>
