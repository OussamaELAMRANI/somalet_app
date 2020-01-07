export default [
   {
      name: 'allProductions',
      path: 'productions',
      component: () => import('@/pages/Productions/Productions'),
      redirect: 'productions/productions-list',
      meta: {
         title: 'Productions', name: 'allProductions', icon: 'fa fa-industry', roles: 'GUEST'
      },
      children: [
         {
            name: 'newProductionOrder',
            path: 'new-production-order',
            component: () => import('@/pages/Productions/MolsatOrder'),
            meta: {
               title: "Commande au production",
               name: 'newProductionOrder',
               icon: 'fa fa-gor',
               roles: 'COMMERCIAL'
            },
         },
         {
            name: 'detailProductionOrder',
            path: ':id/detail',
            component: () => import('@/pages/Productions/productionDetail'),
            hidden: true,
            meta: {
               title: "Detail d'une Commande", name: 'detailProductionOrder', icon: 'fa fa-gor', roles: 'COMMERCIAL',
            },
         },
         {
            name: 'orderReception',
            path: 'order-reception',
            component: () => import('@/pages/Productions/SolesReceptions'),
            meta: {
               title: "Reception des commandes", name: 'orderReception', icon: 'fa fa-gor', roles: 'COMMERCIAL'
            },
         },
         {
            name: 'listOrderProduction',
            path: 'productions-list',
            component: () => import('@/pages/Productions/ProductionOrderList'),
            meta: {
               title: "Lister en details", name: 'listOrderProduction', icon: 'fa fa-gor', roles: 'COMMERCIAL'
            },
         },

      ]
   },
]
