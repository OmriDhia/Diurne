import Home from '../views/index.vue';

export const routes = [
    {
        path: '/login',
        name: 'login',
        component: () => import('../views/auth/login.vue'),
        meta: { layout: 'auth' },
    },
    {
        path: '/lockscreen',
        name: 'lockscreen',
        component: () => import( '../views/auth/lockscreen.vue'),
        meta: { layout: 'auth' },
    },
    {
        path: '/pass-recovery',
        name: 'pass-recovery',
        component: () => import('../views/auth/pass_recovery.vue'),
        meta: { layout: 'auth' },
    },
    {
        path: '/pass-reset',
        name: 'pass-reset',
        component: () => import('../views/auth/pass_reset.vue'),
        meta: { layout: 'auth' },
    },
    {
        path: '/home',
        name: 'home',
        component: () => import('../views/home.vue'),
        meta: { 
            layout: 'home',
            requiresAuth: true
        },
    },

    //dashboard
    { path: '/dashboard', name: 'Home', component: Home },

    //users
    {
        path: '/users/profile',
        name: 'profile',
        component: () => import('../views/users/profile.vue'),
    },
    {
        path: '/users/account-setting',
        name: 'account-setting',
        component: () => import('../views/users/account_setting.vue'),
    },
];
