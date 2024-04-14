<template>
    <div class="layout-px-spacing dash_1">
        <div class="row layout-top-spacing">
            <template v-for="menu in menus">
                <div class="col-4 layout-spacing">
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
                },
                {
                    title: 'Recherche Contremarque',
                    image: 'contremarque.png',
                    color: menuHomeColor.contremarque,
                    href: '',
                },
                {
                    title: 'Recherche Devis',
                    image: 'devis.png',
                    color: menuHomeColor.devis,
                    href: '',
                },
                {
                    title: 'Recherche Image',
                    image: 'image.png',
                    color: menuHomeColor.image,
                    href: '',
                },
                {
                    title: 'Recherche Commande',
                    image: 'commande.png',
                    color: menuHomeColor.commande,
                    href: '',
                },
                {
                    title: 'Recherche Facture',
                    image: 'facture.png',
                    color: menuHomeColor.facture,
                    href: '',
                },
                {
                    title: 'Recherche Tapis',
                    image: 'tapis.png',
                    color: menuHomeColor.tapis,
                    href: '',
                },
                {
                    title: 'Suivi des DI',
                    image: 'di.png',
                    color: menuHomeColor.di,
                    href: '',
                },
                {
                    title: 'Suivi trésorerie',
                    image: 'treasure.png',
                    color: menuHomeColor.treasure,
                    href: '',
                },
            ]
        }
    }
};
</script>
