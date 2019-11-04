export default [
    {
        name: 'all_receptions',
        path: 'receptions',
        component: () => import('@/pages/receptions/Receptions'),
        redirect: 'receptions/receptions-list',
        meta: {
            title: 'Receptions', name: 'all_receptions', icon: 'fa fa-inbox', roles: 'MAGAZINIER'
        },
        children: [
            {
                name: 'list_receptions',
                path: 'receptions-list',
                component: () => import('@/pages/receptions/ReceptionsList'),
                meta: {
                    title: 'Liste des arrivages', name: 'list_receptions', icon: 'fa fa-briefcase', roles: 'MAGAZINIER'
                },
            },
        ]
    },
]
