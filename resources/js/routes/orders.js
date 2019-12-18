import Orders from '@/pages/Orders/Order'
import OrderList from '@/pages/Orders/OrdersList'
import NewOrder from '@/pages/Orders/NewOrder'

export default [
    {
        name: 'allOrders',
        path: 'orders',
        component: Orders,
        redirect: 'orders/order-list',
        meta: {
            title: 'Commandes', name: 'allOrders', icon: 'fa fa-shopping-cart', roles: 'COMMERCIAL'
        },
        children: [
            {
                name: 'newOrder',
                path: 'new-order',
                component: NewOrder,
                meta: {
                    title: "Ajouter Commande", name: 'newOrder', icon: 'fa fa-gor', roles: 'COMMERCIAL'
                },
            },
           {
              name: 'molsatOrder',
              path: 'molsat',
              component: () => import('@/pages/Orders/MolsatOrder'),
              meta: {
                 title: "Commande de production", name: 'molsatOrder', icon: 'fa fa-gor', roles: 'COMMERCIAL'
              },
           },
            {
                name: 'detailOrder',
                path: ':id/detail',
                component: () => import('@/pages/Orders/DetailOrder'),
                hidden: true,
                meta: {
                    title: "Detail d'une Commande", name: 'detailOrder', icon: 'fa fa-gor', roles: 'COMMERCIAL',
                },
            },
            {
                name: 'listOrder',
                path: 'order-list',
                component: OrderList,
                meta: {
                    title: "Commandes", name: 'listOrder', icon: 'fa fa-gor', roles: 'COMMERCIAL'
                },
            },
            {
                name: 'canceledOrder',
                path: 'canceled',
                component: () => import('@/pages/Orders/CanceledOrders'),
                meta: {
                    title: "Retour de Marchandise", name: 'canceledOrder', icon: 'fa fa-gor', roles: 'COMMERCIAL'
                },
            },

        ]
    },
]
