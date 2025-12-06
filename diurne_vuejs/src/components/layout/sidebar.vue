<template>
    <!--  BEGIN SIDEBAR  -->
    <div class="sidebar-wrapper sidebar-theme">
        <nav ref="menu" id="sidebar">
            <!--div class="shadow-bottom"></div-->
            <perfect-scrollbar class="list-unstyled menu-categories mt-5" tag="ul"
                :options="{ wheelSpeed: 0.5, swipeEasing: !0, minScrollbarLength: 40, maxScrollbarLength: 300, suppressScrollX: true }">
                <li class="menu" v-for="menu in menus" :key="menu.id">
                    <router-link :to="getPathByName(menu.route)" class="dropdown-toggle" v-if="menu.children.length === 0"
                                 @click.prevent="clickH()">
                        <d-menu-item :icon="menu.icon" :name="menu.name"></d-menu-item>
                    </router-link>
                    <template v-else>
                        <a class="dropdown-toggle" data-bs-toggle="collapse" :data-bs-target="'#'+menu.name"
                            :aria-controls="menu.name" aria-expanded="false">
                            <d-menu-item :icon="menu.icon" :name="menu.name"></d-menu-item>
                            <div>
                                <vue-feather type="chevron-right" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" stroke="currentColor"></vue-feather>
                            </div>
                        </a>

                        <ul :id="menu.name" class="collapse submenu list-unstyled" data-bs-parent="#sidebar">
                            <li v-if="menu.route && menu.route === 'contacts'">
                                <router-link :to="getPathByName(menu.route)"  @click.prevent="clickHExp($event)">
                                    {{ $t(menu.name) }}
                                </router-link>
                            </li>
                            <li v-for="child in menu.children" :key="child.id">
                                <router-link :to="getPathByName(child.route)"  @click.prevent="clickHExp($event)">
                                    <!-- {{ $t(child.name) }} -->
                                    {{ child.name === "Suivi des DI" ? "Suivi Des Maquettes" : $t(child.name) }}
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
import { onMounted, ref, watch } from 'vue';
import VueFeather from 'vue-feather';
import { useStore } from 'vuex';
import {useRouter, useRoute} from 'vue-router';
import userService from '../../Services/user-service';
import dMenuItem from './components/d-menu-item.vue';
const store = useStore();
const router = useRouter();

const menu_collapse = ref('dashboard');
const menus = userService.getUserMenu();
console.log("test",menus);

const getPathByName = (name) => {
    try{
        const routeInfo = router.resolve({ name: name });
        return routeInfo.href;
    }catch{
        return '/error/404';
    }
};
const route = useRoute();
watch(route, (to) => {
    subMenuActive(false)
});
onMounted(() => {
    subMenuActive()
});
const clickH = ()=>{
    const a = document.querySelectorAll('#sidebar ul.collapse');
    a.forEach(el => {
        applyMenuActive(el, true)
    });
}
const clickHExp = (e)=>{
    const sel = e.target;
    const u = sel.closest('ul.collapse');
    if (u) {
        let el = u.closest('li.menu').querySelectorAll('.dropdown-toggle');
        if (el) {
            el = el[0];
            el.dataset.active = true;
        }else{
            el.dataset.active = false;
        }
    }
};

const subMenuActive = (click = true) => {
    const el = document.querySelectorAll('#sidebar a');
    el.forEach( e => {
        if(e.href && window.location.href.includes(e.href)){
            setTimeout(() => {
                e.classList.add('active', 'router-link-active');
                applyMenuActive(e, click)
            },200);
        }
    })
};

const applyMenuActive = (selector, active) => {
    if (selector) {
        const ul = selector.closest('ul.collapse');
        if (ul) {
            //const e = document.querySelectorAll('li.menu .dropdown-toggle');
            let ele = ul.closest('li.menu').querySelectorAll('.dropdown-toggle');
            if (ele) {
                ele = ele[0];
                setTimeout(() => {
                    if(ele.ariaExpanded == "false"){
                        ele.click();
                        ele.dataset.active = true
                    }
                });
            }
            document.querySelectorAll('#sidebar .dropdown-toggle').forEach( e => {
                if(e !== ele){
                    e.dataset.active = false;
                }
            });
        } else {
            active ? selector.click() : '';
        }
    }
};
const toggleMobileMenu = () => {
    if (window.innerWidth < 991) {
        store.commit('toggleSideBar', !store.state.is_show_sidebar);
    }
};
</script>
