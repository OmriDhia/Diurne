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
            requiresAuth: true
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
                meta: {
                    permission: 'read user'
                },
            },
            {
                path: 'account-setting/:id?',
                name: 'account-setting',
                component: () => import('../views/users/account_setting.vue'),
                meta: {
                    permission: 'create user',
                },
            },
            {
                path: 'profiles',
                name: 'profiles',
                component: () => import('../views/users/profiles.vue'),
                meta: {
                    permission: 'read profile',
                },
            },
            {
                path: 'profile/:id',
                name: 'profile',
                component: () => import('../views/users/profile.vue'),
                meta: {
                    permission: 'update profile',
                },
            }
        ],
        meta: { 
            requiresAuth: true
        },
    },
    {
        path: '/contacts',
        name: 'contacts',
        children: [
            {
                path: '',
                name: 'contactsList',
                component: () => import('../views/contacts/contacts.vue'),
                meta: {
                    requiresAuth: true,
                    permission: "read contact",
                },
            },
            {
                path: 'manage/:id?',
                name: 'addContact',
                component: () => import('../views/contacts/addContact.vue'),
                meta: {
                    requiresAuth: true,
                    permission: "create contact",
                },
            },
        ],
        meta: {
            requiresAuth: true,
            class: 'contacts'
        },
    },
    {
        path: '/projet',
        name: 'projet',
        children: [
            {
                path: '',
                name: 'projectsList',
                component: () => import('../views/projects/projects.vue'),
                meta: {
                    requiresAuth: true,
                    class: 'projects'
                },
            },
        ],
        meta: {
            requiresAuth: true,
            class: 'projects'
        },
    },
];
