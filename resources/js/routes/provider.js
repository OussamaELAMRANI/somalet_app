import Providers from '../pages/Providers/Providers'
import NewProvider from '../pages/Providers/NewProvider'
import ProvidersList from '../pages/Providers/ProvidersList'

export default [
    {
        name: 'allProviders',
        path: 'provider',
        redirect: '/provider/provider-list',
        component: Providers,
        meta: {
            title: 'Fournisseurs', name: 'allProviders', icon: 'fa fa-briefcase', roles: 'guest'
        },
        children: [
            {
                name: 'newProvider',
                path: 'new-provider',
                component: NewProvider,
                meta: {
                    title: 'Nouveau fournisseur', name: 'newProvider', icon: 'fa fa-gor', roles: 'guest'
                },
            },
            {
                name: 'listProvider',
                path: 'provider-list',
                component: ProvidersList,
                meta: {
                    title: 'Liste des fournisseurs', name: 'listProvider', icon: 'fa fa-gor', roles: 'guest'
                },
            },
        ]
    },
]
