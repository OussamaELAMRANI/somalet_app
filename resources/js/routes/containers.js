export default [
    {
        name: 'all_containers',
        path: 'containers',
        component: () => import('@/pages/Containers/Containers'),
        redirect: 'containers/container-list',
        meta: {
            title: 'Contenaires', name: 'all_containers', icon: 'fa fa-tasks', roles: 'COMMERCIAL'
        },
        children: [
            {
                name: 'new_container',
                path: 'new-container',
                component: () => import('@/pages/Containers/NewContainer'),

                meta: {
                    title: 'Nouveau Contenaire', name: 'new_container', icon: 'fa fa-briefcase', roles: 'COMMERCIAL'
                },
            },
            {
               name: 'ContainerDetail',
               path: ':id/detail',
               component: ()=> import('@/pages/Containers/DetailContainer'),
               hidden: true,
               meta: {
                  title: 'Détail Contenaire', name: 'ContainerDetail', icon: 'fa fa-list', roles: 'COMMERCIAL'
               },
            },
            {
                name: 'list_container',
                path: 'container-list',
                component: () => import('@/pages/Containers/ListContainer'),
                meta: {
                    title: 'Lister les Contenaires',
                    name: 'list_container',
                    icon: 'fa fa-briefcase',
                    roles: 'COMMERCIAL'
                },
            },
        ]
    },
]
