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
        path: '/error/:code',
        name: 'error',
        component: () => import('../views/pages/error.vue'),
        meta: { 
            layout: 'auth'
        },
    },
    
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
                path: 'profiles',
                name: 'profiles',
                component: () => import('../views/users/profiles.vue'),
            },
            {
                path: 'profile/:id',
                name: 'profile',
                component: () => import('../views/users/profile.vue'),
            }
        ],
        meta: { 
            requiresAuth: true
        },
    },
];
