export const routes = [
    {
        path: '/',
        name: 'login',
        component: () => import('../views/auth/login.vue'),
        meta: { layout: 'auth' }
    },
    {
        path: '/lockscreen',
        name: 'lockscreen',
        component: () => import('../views/auth/lockscreen.vue'),
        meta: { layout: 'auth' }
    },
    {
        path: '/pass-recovery',
        name: 'pass-recovery',
        component: () => import('../views/auth/pass_recovery.vue'),
        meta: { layout: 'auth' }
    },
    {
        path: '/pass-reset/:token',
        name: 'pass-reset',
        component: () => import('../views/auth/pass_reset.vue'),
        meta: { layout: 'auth' }
    },
    {
        path: '/home',
        name: 'home',
        component: () => import('../views/home.vue'),
        meta: {
            layout: 'home',
            requiresAuth: true
        }
    },
    {
        path: '/error/:code',
        name: 'error',
        component: () => import('../views/pages/error.vue'),
        meta: {
            requiresAuth: true
        }
    },

    // {
    //     path: '/facture-client',
    //     name: 'facture_client',
    //     children: [
    //         {
    //             path: '',
    //             name: 'facture_client_list',
    //             component: () => import('../views/factureClient/factureClientList.vue'),
    //             meta: {
    //                 requiresAuth: true,
    //                 class: 'treasury',
    //                 permission: 'read invoice',
    //             }
    //         },
    //         {
    //             path: 'manage/:id?',
    //             name: 'facture_client_manage',
    //             component: () => import('../views/factureClient/factureClientForm.vue'),
    //             meta: {
    //                 requiresAuth: true,
    //                 class: 'treasury',
    //                 permission: 'create invoice',
    //             }
    //         }
    //     ],
    //     meta: {
    //         requiresAuth: true,
    //         class: 'treasury'
    //     },
    // },
    //users
    {
        path: '/users',
        name: 'users',
        children: [
            {
                path: '',
                name: 'users-manage',
                component: () => import('../views/users/users.vue'),
                meta: {
                    permission: 'read user'
                }
            },
            {
                path: 'account-setting/:id?',
                name: 'account-setting',
                component: () => import('../views/users/account_setting.vue'),
                meta: {
                    permission: 'create user'
                }
            },
            {
                path: 'profiles',
                name: 'profiles',
                component: () => import('../views/users/profiles.vue'),
                meta: {
                    permission: 'read profile'
                }
            },
            {
                path: 'profile/:id',
                name: 'profile',
                component: () => import('../views/users/profile.vue'),
                meta: {
                    permission: 'update profile'
                }
            }
        ],
        meta: {
            requiresAuth: true
        }
    },
    //users
    {
        path: '/settings',
        name: 'settings',
        children: [
            {
                path: '',
                name: 'settings_home',
                component: () => import('../views/settings/settings.vue')
            },
            {
                path: 'collections-produits',
                name: 'collections-produits',
                component: () => import('../views/settings/d-collections-produits.vue'),
                meta: { permission: 'create setting' }
            },
            {
                path: 'transport-livraison',
                name: 'transport-livraison',
                component: () => import('../views/settings/d-transport-livraison.vue'),
                meta: { permission: 'create setting' }
            },
            {
                path: 'couleurs-materiaux',
                name: 'couleurs-materiaux',
                component: () => import('../views/settings/d-couleurs-materiaux.vue'),
                meta: { permission: 'create setting' }
            },
            {
                path: 'fabricants-qualite',
                name: 'fabricants-qualite',
                component: () => import('../views/settings/d-fabricants-qualite.vue'),
                meta: { permission: 'create setting' }
            },
            {
                path: 'tarification-taxes',
                name: 'tarification-taxes',
                component: () => import('../views/settings/d-tarification-taxes.vue'),
                meta: { permission: 'create setting' }
            },
            {
                path: 'payment-types',
                name: 'payment-types',
                component: () => import('../views/settings/d-payment-types.vue'),
                meta: { permission: 'create setting' }
            },
            {
                path: 'formes-traitements',
                name: 'formes-traitements',
                component: () => import('../views/settings/d-formes-traitements.vue'),
                meta: { permission: 'create setting' }
            },
            {
                path: 'images-models',
                name: 'images-models',
                component: () => import('../views/settings/d-images-models.vue'),
                meta: { permission: 'create setting' }
            },
            {
                path: 'attachment-types',
                name: 'attachment-types',
                component: () => import('../views/settings/d-attachment-types.vue'),
                meta: { permission: 'create setting' }
            }
        ],
        meta: {
            requiresAuth: true
        }
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
                    permission: 'read contact'
                }
            },
            {
                path: 'manage/:id?',
                name: 'addContact',
                component: () => import('../views/contacts/addContact.vue'),
                meta: {
                    requiresAuth: true,
                    permission: 'create contact'
                }
            }
        ],
        meta: {
            requiresAuth: true,
            class: 'contacts'
        }
    },
    {
        path: '/intermediaries',
        name: 'get_intermediaries',
        component: () => import('../views/contacts/intermediares.vue'),
        meta: {
            requiresAuth: true,
            permission: 'read contact',
            class: 'contacts'
        }
    },
    {
        path: '/projet/contremarques',
        name: 'contremarques',
        children: [
            {
                path: '',
                name: 'projectsList',
                component: () => import('../views/projects/contremarques/contremarques.vue'),
                meta: {
                    requiresAuth: true,
                    class: 'projects',
                    permission: 'read contremarque'
                }
            },
            {
                path: 'manage/:id?',
                name: 'projectsListManage',
                component: () => import('../views/projects/contremarques/manage.vue'),
                meta: {
                    requiresAuth: true,
                    permission: 'create contremarque'
                }
            },
            {
                path: 'projectdis/:id',
                name: 'projectDIS',
                component: () => import('../views/projects/contremarques/projectdis.vue'),
                meta: {
                    requiresAuth: true,
                    permission: 'create contremarque'
                }
            }
        ],
        meta: {
            requiresAuth: true,
            class: 'projects'
        }
    },
    {
        path: '/projet/devis',
        name: 'devis',
        children: [
            {
                path: '',
                name: 'devisList',
                component: () => import('../views/projects/devis/devis.vue'),
                meta: {
                    requiresAuth: true,
                    class: 'projects',
                    permission: 'read quote'
                }
            },
            {
                path: 'manage/:id?',
                name: 'devisManage',
                component: () => import('../views/projects/devis/manage.vue'),
                meta: {
                    requiresAuth: true,
                    permission: 'create quote'
                }
            },
            {
                path: ':qouteId/details/:id?',
                name: 'devisDetails',
                component: () => import('../views/projects/devis/devisDetails.vue'),
                meta: {
                    requiresAuth: true,
                    permission: 'create quote'
                }
            }/*,
            {
                path: 'projectdis/:id',
                name: 'projectDIS',
                component: () => import('../views/projects/contremarques/projectdis.vue'),
                meta: {
                    requiresAuth: true,
                    permission: "create contremarque",
                },
            },*/
        ],
        meta: {
            requiresAuth: true,
            class: 'projects'
        }
    },
    //Carpet Order
    {
        path: '/tapis',
        name: 'tapis',
        children: [
            {
                path: '',
                name: 'carpetOrderList',
                component: () => import('../views/carpet/order/carpetOrders.vue'),
                meta: {
                    requiresAuth: true,
                    class: 'tapis',
                    permission: 'read carpet'
                }
            },
            {
                path: 'manage/:id?',
                name: 'carpetOrderManage',
                component: () => import('../views/carpet/order/manage.vue'),
                meta: {
                    requiresAuth: true,
                    class: 'tapis',
                    permission: 'read carpet'
                }
            },
            {
                path: ':carpetOrder/details/:id?',
                name: 'carpetOrderDetails',
                component: () => import('../views/carpet/order/devisDetails.vue'),
                meta: {
                    requiresAuth: true,
                    class: 'tapis',
                    permission: 'create quote'
                }
            }
        ],
        meta: {
            requiresAuth: true,
            class: 'projects'
        },
    }, //order-image
    {
        path: '/tapis/images',
        name: 'order-image',
        children: [
            {
                path: '',
                name: 'images',
                component: () => import('../views/carpet/image-commande/imageCommande.vue'),
                meta: {
                    requiresAuth: true,
                    class: 'tapis',
                    permission: "read image",
                },
            },
            {
                path: 'detail/:id',
                name: 'imagesCommadeDetails',
                component: () => import('../views/carpet/image-commande/orderImageDesigner.vue'),
                meta: {
                    requiresAuth: true,
                    class: 'tapis',
                    permission: "read image",
                },
            },
        ],
        meta: {
            requiresAuth: true,
            class: 'projects'
        }
    },
    {
        path: '/projet/dis',
        name: 'suiviDI',
        children: [
            {
                path: '',
                name: 'di_list',
                component: () => import('../views/projects/suiviDi/suiviDi.vue'),
                meta: {
                    requiresAuth: true,
                    class: 'projects',
                    permission: 'read contremarque'
                }
            },
            {
                path: 'model/:id_di/create',
                name: 'di_orderDesigner_create',
                component: () => import('../views/projects/suiviDi/orderCarpetDesigner.vue'),
                meta: {
                    requiresAuth: true,
                    class: 'projects',
                    permission: 'read contremarque'
                }
            },
            {
                path: 'model/:id_di/update/:carpetDesignOrderId',
                name: 'di_orderDesigner_update',
                component: () => import('../views/projects/suiviDi/orderCarpetDesigner.vue'),
                meta: {
                    requiresAuth: true,
                    class: 'projects',
                    permission: 'read contremarque'
                }
            }
        ],
        meta: {
            requiresAuth: true,
            class: 'projects'
        }
    },
    {
        path: '/projet/commande',
        name: 'commande client',
        children: [
            {
                path: '',
                name: 'orders',
                component: () => import('../views/carpet/order/carpetOrders.vue'),
                meta: {
                    requiresAuth: true,
                    class: 'projects',
                    permission: 'read order'
                }
            }
        ],
        meta: {
            requiresAuth: true,
            class: 'projects'
        }
    },
    {
        path: '/projet/invoices',
        name: 'invoices',
        children: [
            {
                path: '',
                name: 'client-invoice-list',
                component: () => import('../views/projects/factureClient/clientInvoiceList.vue'),
                meta: {
                    requiresAuth: true,
                   class: 'projects',
                    permission: 'read invoice',
                },
            },
            {
                path: 'create',
                name: 'client-invoice-create',
                component: () => import('../views/projects/factureClient/clientInvoiceCreate.vue'),
                meta: {
                    requiresAuth: true,
                   class: 'projects',
                    permission: 'create invoice',
                },
            },
        ],
        meta: {
            requiresAuth: true,
            class: 'projects'
        }
    },

    {
        path: '/checking-progress/list/:id',
        name: 'checkingList',
        component: () => import('../views/checkingProgress/checkingList.vue'),
        meta: {
            requiresAuth: true,
            class: 'projects'
        }
    },
    {
        path: '/checking-progress/provisional',
        name: 'provisionalCalendar',
        component: () => import('../views/checkingProgress/provisionalCalendar.vue'),
        meta: {
            requiresAuth: true,
            class: 'projects'
        }
    },
    {
        path: '/checking-progress/provisional/:id',
        name: 'provisionalCalendarView',
        component: () => import('../views/checkingProgress/provisionalCalendar.vue'),
        meta: {
            requiresAuth: true,
            class: 'projects'
        }
    },
    {
        path: '/checking-progress/progress',
        name: 'progressReport',
        component: () => import('../views/checkingProgress/progressReport.vue'),
        meta: {
            requiresAuth: true,
            class: 'projects'
        }
    },
    {
        path: '/treasury',
        name: 'treasury',
        component: () => import('../views/treasury/treasuryList.vue'),
         meta: {
                    requiresAuth: true,
                    class: 'treasury',
                    permission: "read treasury",
        },
    },

    {
        path: '/reglement',
        name: 'reglement',
        children: [{
                path: '',
                name: 'reglement_list',
                component: () => import('../views/treasury/treasuryList.vue'),
                meta: {
                    requiresAuth: true,
                    class: 'treasury',
                    permission: "read treasury",
                },
            },
            {
                path: 'attach/:quoteId',
                name: 'reglement_attach_list',
                component: () => import('../views/treasury/treasuryAttachList.vue'),
                meta: {
                    requiresAuth: true,
                    class: 'treasury',
                    permission: "read treasury",
                },
            },
            {
                path: 'create',
                name: 'reglement_create',
                component: () => import('../views/treasury/treasuryForm.vue'),
                meta: {
                    requiresAuth: true,
                    class: 'treasury',
                    permission: "create treasury",
                },
            },
            // {
            //     path: 'update/:id',
            //     name: 'reglement_update',
            //     component: () => import('../views/treasury/treasuryForm.vue'),
            //     meta: {
            //         requiresAuth: true,
            //         class: 'treasury',
            //         permission: "update treasury",
            //     },
            // },
            {
                path: 'manage/:id',
                name: 'reglement_view',
                component: () => import('../views/treasury/treasuryView.vue'),
                meta: {
                    requiresAuth: true,
                    class: 'treasury',
                    permission: "read treasury",
                },
            },
            {
                path: 'rattacher/:quoteId/:id',
                name: 'reglement_rattacher',
                component: () => import('../views/treasury/treasuryView.vue'),
                meta: {
                    requiresAuth: true,
                    class: 'treasury',
                    permission: "update treasury",
                },
            },
        ],
        meta: {
            requiresAuth: true,
            class: 'treasury'
        },
    },
    {
        path: '/tapis/invoices',
        name: 'supplier_invoices',
        children: [
            {
                path: '',
                name: 'fournisseur-invoice-list',
                component: () => import('../views/carpet/factureFournisseur/fournisseurInvoiceList.vue'),
                meta: {
                    requiresAuth: true,
                   class: 'tapis',
                    permission: 'read invoice',
                },
            },
            {
                path: 'create',
                name: 'fournisseur-invoice-create',
                component: () => import('../views/carpet/factureFournisseur/fournisseurInvoiceCreate.vue'),
                meta: {
                    requiresAuth: true,
                   class: 'tapis',
                    permission: 'create invoice',
                },
            },
        ],
        meta: {
            requiresAuth: true,
            class: 'tapis'
        }
    },
    {
        path: '/workshop',
        name: 'carpetWorkshop',
        children: [
            {
                path: '',
                name: 'work_shop',
                component: () => import('../views/workshop/workshops.vue'),
                meta: {
                    requiresAuth: true,
                    class: 'tapis',
                    permission: 'read carpet'
                }
            },
            {
                path: ':imagesCommadeId/create',
                name: 'createCarpetWorkshop',
                component: () => import('../views/workshop/workshop-info.vue'),
                meta: {
                    requiresAuth: true,
                    class: 'tapis',
                    permission: 'create workshop'
                }
            },
            {
                path: 'update/:workshopOrderId',
                name: 'updateCarpetWorkshop',
                component: () => import('../views/workshop/workshop-info.vue'),
                meta: {
                    requiresAuth: true,
                    class: 'tapis',
                    permission: 'update workshop'
                }
            }
        ],
        meta: {
            requiresAuth: true,
            class: 'tapis'
        },
    },

];
