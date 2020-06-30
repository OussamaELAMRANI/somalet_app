export default [
   {
      name: 'payments',
      path: 'payments',
      component: () => import('@/pages/payments/Payments'),
      redirect: 'payments/list',
      meta: {
         title: 'Paiments', name: 'all_alerts', icon: 'fa fa-credit-card', roles: ['COMMERCIAL']
      },
      children: [
         {
            name: 'paymentNew',
            path: 'new',
            component: () => import('@/pages/payments/NewPayment'),
            meta: {
               title: 'Nouveau paiement', name: 'paymentNew', icon: 'fa fa-credit-card', roles: ['COMMERCIAL']
            },
         },
         {
            name: 'paymentList',
            path: 'list',
            component: () => import('@/pages/payments/PaymentList'),
            meta: {
               title: 'Liste de paiements', name: 'paymentList', icon: 'fa fa-credit-card', roles: ['COMMERCIAL']
            },
         },
         {
            name: 'paymentUpdate',
            path: ':id/update',
            hidden: true,
            component: () => import('@/pages/payments/NewPayment'),
            meta: {
               title: 'Modification de paiement', name: 'paymentUpdate',
               icon: 'fa fa-credit-card', roles: ['ADMINE']
            },
         },

      ]
   },
]
