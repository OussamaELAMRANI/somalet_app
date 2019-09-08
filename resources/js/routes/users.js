import UsersList from '../pages/admin/UsersList'
import Users from '../pages/admin/Users'

export default [
    {
        name: 'all_users',
        path: 'users',
        component: Users,
        redirect: 'users/users-list',
        meta: {
            title: 'Utilisateurs', name: 'all_users', icon: 'fa fa-users', roles: 'guest'
        },
        children: [
            {
                name: 'UserNew',
                path: 'new-user',
                component: () => import('@/pages/admin/UserNew'),
                meta: {
                    title: 'Nouveau Utilisateur', name: 'UserNew', icon: 'fa fa-user', roles: 'guest'
                },
            },
            {
                name: 'list_users',
                path: 'users-list',
                component: UsersList,
                meta: {
                    title: 'Liste des utilisateur', name: 'list_users', icon: 'fa fa-gor', roles: 'guest'
                },
            },
            {
                name: 'deleteUser',
                hidden: true,
                path: ':id/delete',
                meta: {
                    title: 'supprimer utilisateur', name: 'deleteUser', icon: 'fa fa-gor', roles: 'guest'
                },
            },
        ]
    },
]
