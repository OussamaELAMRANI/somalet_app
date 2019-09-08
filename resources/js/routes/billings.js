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
         title: 'Payéments', name: 'allBillings', icon: 'fa fa-money-bill', roles: 'guest'
      },
      children: [
         {
            name: 'newPayment',
            path: ':cmd/new-payment',
            component: () => import('@/pages/Billings/NewPayment'),
            meta: {
               title: "Ajouter un Payément", name: 'newPayment', icon: 'fa fa-gor', roles: 'guest'
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

         {
            name: 'listBilling',
            path: 'billings-list',
            component: () => import('@/pages/Billings/BillingsList'),
            meta: {
               title: "Payéments", name: 'listOrder', icon: 'fa fa-gor', roles: 'guest'
            },
         },
      ]
   },
]
