import store from '../store';
import { routes } from './routes';
import { createRouter, createWebHistory } from 'vue-router';
import NProgress from 'nprogress';

const router = createRouter({  // ❌ Remove `new`
    history: createWebHistory(),
    linkExactActiveClass: 'active',
    routes,
});

router.beforeEach((to, from, next) => {
    NProgress.configure({ showSpinner: false });
    NProgress.start();

    if (to.matched.length === 0) {
        next({ path: '/error/404' }); // Redirect to NotFound
    } else {
        const permission = to.matched
            .filter(record => record.meta.permission)
            .map(record => record.meta.permission);

        if (permission.length > 0 && !window.$hasPermission(permission[0])) {
            next({ path: '/error/401' }); // Redirect to 401 (Unauthorized)
        } else {
            // Set layout
            if (to.meta && to.meta.layout) {
                store.commit('setLayout', to.meta.layout);
            } else {
                if (store.getters.layout !== 'app') store.commit('setLayout', 'app');
            }
            
            // Check authentication
            const isAuthenticated = store.getters.isAuthenticated;
            if(to.name === "login" && isAuthenticated)
                next('/home');
            
            // Set page class
            store.commit('setPageClass', '');
            if (to.meta && to.meta.class) {
                store.commit('setPageClass', to.meta.class);
            }

           
            if (to.matched.some(record => record.meta.requiresAuth) && !isAuthenticated) {
                next('/');
            } else {
                next();
            }
        }
    }
});

router.afterEach(() => {
    NProgress.done(); 
});

export default router;
