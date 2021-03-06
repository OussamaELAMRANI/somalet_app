// import Orders from '@/pages/Orders/Order'
// import OrderList from '@/pages/Orders/OrdersList'
// import NewOrder from '@/pages/Orders/NewOrder'

export default [
   {
      name: 'allBillings',
      path: 'billings',
      component: () => import('@/pages/Billings/Billings'),
      redirect: 'billings/billings-list',
      meta: {
         title: 'Payéments', name: 'allBillings', icon: 'fa fa-money-bill',
      },
      hidden: true,

      children: [
         {
            name: 'newPayment',
            path: ':cmd/new-payment',
            component: () => import('@/pages/Billings/NewPayment'),
            meta: {
               title: "Ajouter un Payément", name: 'newPayment', icon: 'fa fa-gor', roles: 'COMMERCIAL'
            },
            hidden: true,
         },
         // {
         //    name: 'detailPayment',
         //    path: ':cmd/detail',
         //    component: () => import('@/pages/Orders/DetailOrder'),
         //    hidden: true,
         //    meta: {
         //       title: "Detail d'une Commande", name: 'detailPayment', icon: 'fa fa-gor', roles: 'guest',
         //    },
         // },

         // {
         //    name: 'listBilling',
         //    path: 'billings-list',
         //    component: () => import('@/pages/Billings/BillingsList'),
         //    meta: {
         //       title: "PortFeuille", name: 'listBilling', icon: 'fa fa-gor', roles: 'guest'
         //    },
         // },

         // {
         //    name: 'monthBilling',
         //    path: ':year/:month/billings',
         //    component: () => import('@/pages/Billings/Monthly'),
         //    meta: {
         //       title: "PortFeuille Monsuelle", name: 'monthBilling', icon: 'fa fa-gor', roles: 'guest'
         //    },
         //    hidden: true
         // },
      ]
   },
]
