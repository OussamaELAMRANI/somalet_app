import Providers from '../pages/Providers/Providers'
import NewProvider from '../pages/Providers/NewProvider'
import ProvidersList from '../pages/Providers/ProvidersList'

export default [
   {
      name: 'allProviders',
      path: 'provider',
      redirect: 'provider/provider-list',
      component: Providers,
      meta: {
         title: 'Fournisseurs', name: 'allProviders', icon: 'fa fa-briefcase', roles: ['COMMERCIAL','COMTABLE']
      },
      children: [
         {
            name: 'newProvider',
            path: 'new-provider',
            component: NewProvider,
            meta: {
               title: 'Nouveau fournisseur', name: 'newProvider', icon: 'fa fa-gor', roles: ['COMMERCIAL','COMTABLE']
            },
         },
         {
            name: 'updateProvider',
            hidden: true,
            path: ':id/update',
            component: () => import('@/pages/Providers/NewProvider'),
            meta: {
               title: 'Modifier Fournisseur', name: 'updateProvider', icon: 'fa fa-gor', roles: ['COMMERCIAL','COMTABLE']
            },
         },
         // {
         //    name: 'deleteProvider',
         //    hidden: true,
         //    path: ':id/delete',
         //    meta: {
         //       title: 'delete Fournisseur', name: 'deleteProvider', icon: 'fa fa-gor', roles: 'guest'
         //    },
         // },
         {
            name: 'detailProvider',
            hidden: true,
            path: ':id/detail',
            component: () => import('@/pages/Providers/ProviderDetail'),
            meta: {
               title: 'Détail Fournisseur', name: 'detailProvider', icon: 'fa fa-gor', roles: ['COMMERCIAL','COMTABLE']
            },
         },
         {
            name: 'listProvider',
            path: 'provider-list',
            component: ProvidersList,
            meta: {
               title: 'Liste des fournisseurs', name: 'listProvider', icon: 'fa fa-gor', roles: ['COMMERCIAL','COMTABLE']
            },
         },
      ]
   },
]
