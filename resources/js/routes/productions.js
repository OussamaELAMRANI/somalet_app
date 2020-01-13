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
               title: "Commande d'usine",
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
            path: ':id/order-reception',
            component: () => import('@/pages/Productions/SolesReceptions'),
            meta: {
               title: "Reception des commandes", name: 'orderReception', icon: 'fa fa-gor', roles: 'COMMERCIAL'
            },
            hidden: true
         },
         {
            name: 'listOrderProduction',
            path: 'productions-list',
            component: () => import('@/pages/Productions/ProductionOrderList'),
            meta: {
               title: "Lister en details", name: 'listOrderProduction', icon: 'fa fa-gor', roles: 'COMMERCIAL'
            },
         },
         {
            name: 'inventoryProduction',
            path: 'inventory',
            component: () => import('@/pages/Productions/ProductionStock'),
            meta: {
               title: "Stock Production", name: 'inventoryProduction', icon: 'fa fa-gor', roles: 'COMMERCIAL'
            },
         },
         {
            name: 'prodctionReceptions',
            path: 'receptions',
            component: () => import('@/pages/Productions/ProductionReceptionList'),
            redirect: 'receptions/list',
            meta: {
               title: "Reception", name: 'prodctionReceptions', icon: 'fa fa-gor', roles: 'COMMERCIAL'
            },
            children: [
               {
                  name: 'listOrderReception',
                  path: 'list',
                  component: () => import('@/pages/Productions/ReceptionList'),
                  meta: {
                     title: "Lister en details", name: 'listOrderReception', icon: 'fa fa-gor', roles: 'COMMERCIAL'
                  },
               },
            ]
         },
         {
            name: 'commandProduction',
            path: 'new-command',
            component: () => import('@/pages/Productions/NewProductionCommand'),
            meta: {
               title: "Nouvelle Commande", name: 'commandProduction', icon: 'fa fa-gor', roles: 'COMMERCIAL'
            },
         },
      ]
   },
]
