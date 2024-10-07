<template>
    <div class="layout-px-spacing dash_1">
        <div class="row layout-top-spacing">
            <template v-for="menu in menus">
                <div class="col-xl-4 col-lg-6 col-sm-12 p-2" v-if="hasMenuPermission(menu.permission)" :key="menu.menuId">
                    <box-home :image="menu.image" :title="menu.title" :color="menu.color" :href="menu.href"></box-home>
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
import userService from "../Services/user-service"

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
                    href: '/contacts',
                    permission: "read contact",
                },
                {
                    title: 'Recherche Contremarque',
                    image: 'contremarque.png',
                    color: menuHomeColor.contremarque,
                    href: '/projet/contremarques',
                    permission: "read contremarque",
                },
                {
                    title: 'Recherche Devis',
                    image: 'devis.png',
                    color: menuHomeColor.devis,
                    href: '/devis',
                    permission: "read quote",
                },
                {
                    title: 'Recherche Image',
                    image: 'image.png',
                    color: menuHomeColor.image,
                    href: '/image',
                    permission: "read image",
                },
                {
                    title: 'Recherche Commande',
                    image: 'commande.png',
                    color: menuHomeColor.commande,
                    href: '/commande',
                    permission: "read order",
                },
                {
                    title: 'Recherche Facture',
                    image: 'facture.png',
                    color: menuHomeColor.facture,
                    href: '/facture',
                    permission: "read invoice",
                },
                {
                    title: 'Recherche Tapis',
                    image: 'tapis.png',
                    color: menuHomeColor.tapis,
                    href: '/tapis',
                    permission: "read carpet",
                },
                {
                    title: 'Suivi des DI',
                    image: 'di.png',
                    color: menuHomeColor.di,
                    href: '/projet/dis',
                    permission: "read DI",
                },
                {
                    title: 'Suivi trésorerie',
                    image: 'treasure.png',
                    color: menuHomeColor.treasure,
                    href: '/treasury',
                    permission: "read treasury",
                },
            ]
        }
    },
    methods: {
        hasMenuPermission(permession){
            return window.$hasPermission(permession)
        }
    },
    mounted() {
        
    },
};
</script>
