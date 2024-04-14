import Home from '../views/index.vue';

export const routes = [
    {
        path: '/',
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
        path: '/pass-reset/:token',
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
    {
        path: '/404',
        name: 'error404',
        component: () => import('../views/pages/error404.vue'),
        meta: { 
            layout: 'auth'
        },
    },

    //dashboard
    { path: '/dashboard', name: 'Home', component: Home },

    //users
    {
        path: '/users',
        name: 'users',
        children: [
            {
                path: '', 
                name: 'users',
                component: () => import('../views/users/users.vue'),
            },
            {
                path: 'account-setting/:id?',
                name: 'account-setting',
                component: () => import('../views/users/account_setting.vue'),
            },
            {
                path: 'profile',
                name: 'profile',
                component: () => import('../views/users/profile.vue'),
            }
        ],
        meta: { 
            requiresAuth: true
        },
    },
];
