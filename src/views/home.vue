<template>
    <div class="layout-px-spacing dash_1">
        <div class="row layout-top-spacing">
            <template v-for="menu in menus">
                <div class="col-xl-4 col-lg-6 col-sm-12 p-2" v-if="hasMenuPermission(menu.menuId)" :key="menu.menuId">
                    <box-home :image="menu.image" :title="menu.title" :color="menu.color"></box-home>
                </div>
            </template>
        </div>
    </div>
</template>

<script setup>
import { useMeta } from "/src/composables/use-meta";
useMeta({ title: "Acceuil" });
</script>

<script>
import boxHome from "./../components/home/box-home.vue";
import { menuHomeColor } from "../composables/constants";
import userService from "../composables/user-service"

export default {
    components: {
        boxHome
    },
    data() {
        return {
            menus: [
                {
                    title: 'Recherche contacts',
                    image: 'contact.png',
                    color: menuHomeColor.contact,
                    href: '',
                    menuId: 1,
                },
                {
                    title: 'Recherche Contremarque',
                    image: 'contremarque.png',
                    color: menuHomeColor.contremarque,
                    href: '',
                    menuId: 5,
                },
                {
                    title: 'Recherche Devis',
                    image: 'devis.png',
                    color: menuHomeColor.devis,
                    href: '',
                    menuId: 6,
                },
                {
                    title: 'Recherche Image',
                    image: 'image.png',
                    color: menuHomeColor.image,
                    href: '',
                    menuId: 10,
                },
                {
                    title: 'Recherche Commande',
                    image: 'commande.png',
                    color: menuHomeColor.commande,
                    href: '',
                    menuId: 8,
                },
                {
                    title: 'Recherche Facture',
                    image: 'facture.png',
                    color: menuHomeColor.facture,
                    href: '',
                    menuId: 9,
                },
                {
                    title: 'Recherche Tapis',
                    image: 'tapis.png',
                    color: menuHomeColor.tapis,
                    href: '',
                    menuId: 11,
                },
                {
                    title: 'Suivi des DI',
                    image: 'di.png',
                    color: menuHomeColor.di,
                    href: '',
                    menuId: 7,
                },
                {
                    title: 'Suivi trésorerie',
                    image: 'treasure.png',
                    color: menuHomeColor.treasure,
                    href: '',
                    menuId: 4,
                },
            ]
        }
    },
    methods: {
        getMenus(){
            const menus = userService.getUserMenu();
            console.log(menus);
            let tmpsMenus = [];
            menus.forEach( m => {
                tmpsMenus.push(m.id)
                if(m.children.length > 0){
                    tmpsMenus = tmpsMenus.concat(m.children.map(ch => {return ch.id}))
                }
            })
            return tmpsMenus;
        },
        hasMenuPermission(id){
            const menus = this.getMenus();
            return menus.indexOf(id) > -1;
        }
    },
    mounted() {
        this.getMenus();
    },
};
</script>
