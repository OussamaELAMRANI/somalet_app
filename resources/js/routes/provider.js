import Providers from '../pages/Providers/Providers'
import NewProvider from '../pages/Providers/NewProvider'
import ProvidersList from '../pages/Providers/ProvidersList'

export default [
    {
        path: 'provider',
        redirect: '/provider/provider-list'
    },
    {
        name: 'allProviders',
        path: 'provider',
        component: Providers,
        meta: {
            // forVisitor: true
        },
        children: [
            {
                name: 'newProvider',
                path: 'new-provider',
                component: NewProvider,
            },
            {
                name: 'listProvider',
                path: 'provider-list',
                component: ProvidersList,
            },
        ]
    },
]
