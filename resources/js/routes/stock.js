import Stocks from '@/pages/Stock/Stocks'
import StockList from '@/pages/Stock/StockList'
import Inventories from '@/pages/Stock/Inventories'

export default [
   {
      name: 'allStocks',
      path: 'stock',
      component: Stocks,
      redirect: 'stock/stock-list',
      meta: {
         title: 'Stocks', name: 'allStocks', icon: 'fa fa-box', roles: 'guest'
      },
      children: [
         {
            name: 'listStock',
            path: 'stock-list',
            component: StockList,
            meta: {
               title: "Stock d'entre", name: 'listStock', icon: 'fa fa-gor', roles: 'guest'
            },
         },
         {
            name: 'inventories',
            path: 'inventories',
            component: Inventories,
            meta: {
               title: "Stock réel", name: 'inventories', icon: 'fa fa-gor', roles: 'guest'
            },
         },
      ]
   },
]
