export default [
   {
      name: 'allStocks',
      path: 'stock',
      component: () => import('@/pages/Stock/Stocks'),
      redirect: 'stock/stock-list',
      meta: {
         title: 'Stocks', name: 'allStocks', icon: 'fa fa-box', roles: ['MAGAZINIER', 'COMMERCIAL']
      },
      children: [
         {
            name: 'listStock',
            path: 'stock-list',
            component: () => import('@/pages/Stock/StockList'),
            meta: {
               title: "Entrée Stock", name: 'listStock', icon: 'fa fa-gor', roles: 'guest'
            },
         },
         {
            name: 'validStock',
            path: ':id/valid',
            component: () => import('@/pages/Stock/ValidContainer'),
            meta: {
               title: "Valide Contenaire", name: 'validStock', icon: 'fa fa-gor', roles: 'guest'
            },
            hidden: true
         },
         {
            name: 'inventories',
            path: 'inventories',
            component: () => import('@/pages/Stock/Inventories'),
            meta: {
               title: "Stock réel", name: 'inventories', icon: 'fa fa-gor', roles: ['MAGAZINIER', 'COMMERCIAL']
            },
         },
         {
            name: 'histories',
            path: 'histories',
            component: () => import('@/pages/Stock/ValidContainersHistory'),
            meta: {
               title: "Historiques", name: 'histories', icon: 'fa fa-gor', roles: 'guest'
            },
         },
      ]
   },
]
