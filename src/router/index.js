import store from '../store';
import {routes} from './routes';
import { createRouter, createWebHistory } from 'vue-router';
import NProgress from 'nprogress'


const router = new createRouter({
    // mode: 'history',
    history: createWebHistory(),
    linkExactActiveClass: 'active',
    routes,
});

router.beforeEach((to, from, next) => {

    NProgress.configure();
    NProgress.start();
    if (to.matched.length === 0) {
        // Redirect to the NotFound component
        next({ path: '/error/404' });
    }else{
        // affect layout 
        if (to.meta && to.meta.layout) {
            store.commit('setLayout', to.meta.layout);
        } else {
            if(store.getters.layout !== 'app')
                store.commit('setLayout', 'app');
        }

        // check for authentication
        const isAuthenticated = store.getters.isAuthenticated;
        if (to.matched.some(record => record.meta.requiresAuth) && !isAuthenticated) {
            next('/');
        } else {
            next();
        }
    }

    NProgress.done();
});

export default router;
