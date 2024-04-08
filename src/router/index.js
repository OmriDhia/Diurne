import store from '../store';
import {routes} from './routes';
import { createRouter, createWebHistory } from 'vue-router';
import NProgress from 'nprogress'

const router = new createRouter({
    // mode: 'history',
    history: createWebHistory(),
    linkExactActiveClass: 'active',
    routes,
    scrollBehavior(to, from, savedPosition) {
        if (savedPosition) {
            return savedPosition;
        } else {
            return { left: 0, top: 0 };
        }
    },
});

router.beforeEach((to, from, next) => {

    NProgress.configure({showSpinner: false});
    NProgress.start();

    // affect layout 
    if (to.meta && to.meta.layout) {
        store.commit('setLayout', to.meta.layout);
    } else {
        store.commit('setLayout', 'app');
    }

    // check for authentication
    const isAuthenticated = store.getters.isAuthenticated;
    if (to.matched.some(record => record.meta.requiresAuth) && !isAuthenticated) {
        next('/login');
    } else {
        next(true);
    }

    NProgress.done();
});

export default router;
