import Clients from '../pages/Clients/Clients'
import NewClient from '../pages/Clients/NewClient'
import ClientsList from '../pages/Clients/ClientsList'

export default [
   {
      name: 'allClients',
      path: 'client',
      redirect: 'client/client-list',
      component: Clients,
      meta: {
         title: 'Clients', name: 'allClients', icon: 'fa fa-user', roles: 'guest'
      },
      children: [
         {
            name: 'newClient',
            path: 'new-client',
            component: NewClient,
            meta: {
               title: 'Nouveau client', name: 'newClient', icon: 'fa fa-gor', roles: 'guest'
            },
         },
         {
            name: 'updateClient',
            hidden: true,
            path: ':id/update',
            component: () => import('@/pages/Clients/NewClient'),
            meta: {
               title: 'Modifier Client', name: 'updateClient', icon: 'fa fa-gor', roles: 'guest'
            },
         },
         {
            name: 'detailClient',
            hidden: true,
            path: ':id/detail',
            component: () => import('@/pages/Clients/ClientDetail'),
            meta: {
               title: 'Detail Client', name: 'updateClient', icon: 'fa fa-gor', roles: 'guest'
            },
         },
         {
            name: 'movementClient',
            hidden: true,
            path: ':id/movement',
            component: () => import('@/pages/Clients/ClientMouvement'),
            meta: {
               title: 'Detail Client', name: 'updateClient', icon: 'fa fa-gor', roles: 'guest'
            },
         },
         {
            name: 'deleteClient',
            hidden: true,
            path: ':id/delete',
            meta: {
               title: 'delete Client', name: 'deleteClient', icon: 'fa fa-gor', roles: 'guest'
            },
         },
         {
            name: 'listClient',
            path: 'client-list',
            component: ClientsList,
            meta: {
               title: 'Liste des clients', name: 'listClient', icon: 'fa fa-gor', roles: 'guest'
            },
         },
      ]
   },
]
