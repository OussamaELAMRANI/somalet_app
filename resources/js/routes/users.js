import Users from '../pages/admin/Users'

export default [
    {
        name: 'allUsers',
        path: 'user',
        component: Users,
        redirect: '/users',
        meta: {
            title: 'Utilisateur', name: 'allUsers', icon: 'fa fa-briefcase', roles: 'guest'
        },
        children: [
            // {
            //     name: 'newUser',
            //     path: 'new-user',
            //     // component: () => import('@/pages/Users/NewUser'),
            //     meta: {
            //         title: 'Nouveau Utilisateur', name: 'newUser', icon: 'fa fa-gor', roles: 'guest'
            //     },
            // },
            // {
            //     name: 'updateUser',
            //     hidden: true,
            //     path: ':id/update',
            //     // component: () => import('@/pages/Users/NewUser'),
            //     meta: {
            //         title: 'Modifier Produit', name: 'updateUser', icon: 'fa fa-gor', roles: 'guest'
            //     },
            // },
            // {
            //     name: 'deleteUser',
            //     hidden: true,
            //     path: ':id/delete',
            //     meta: {
            //         title: 'delete Produit', name: 'deleteUser', icon: 'fa fa-gor', roles: 'guest'
            //     },
            // },
            {
                name: 'listUsers',
                path: 'users',
                component: Users,
                meta: {
                    title: 'Liste des utilisateur', name: 'listUsers', icon: 'fa fa-gor', roles: 'guest'
                },
            },
        ]
    },
]
