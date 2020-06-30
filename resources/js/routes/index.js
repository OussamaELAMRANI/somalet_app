import Vue from 'vue';
import Router from 'vue-router';

Vue.use(Router);


// import adminRoutes from './Admin'
import Providers from './provider'
import client from './client'
import Products from './products'
import alerts from './alerts'
import Users from './users'
import Receptions from './receptions'
import Stock from './stock'
import order from './orders'
import billings from './billings'
import reglements from './reglements'
import settings from './settings'
import containers from './containers'
import productions from './productions'
import payments from './payments'

// import NotFound from './../pages/NotFound'

export const constantRoutes = [
   {
      path: '/',
      hidden: true,
      redirect: '/login'
   },
   {
      path: '/login',
      component: () => import("@/pages/admin/Login"),
      hidden: true,
      meta: {
         title: 'Societé MOLSAT'
      }
   },
   {
      path: '/404',
      hidden: true,
      name: 'notFound',
      meta: {
         title: 'Erreur | Cette page n\'éxiste pas !'
      },
      component: () => import('@/pages/NotFound'),
   },
   {
      path: '/printer',
      name: 'printer',
      component: () => import("@/pages/Printer/Printer"),
      hidden: true,
      meta: {
         title: 'Imprimer maintenant', name: 'printer', icon: 'fa fa-print'
      }
   },
   {
      path: '/:id/printer-production',
      name: 'productionPrinter',
      component: () => import("@/pages/Printer/SolePrinter"),
      hidden: true,
      meta: {
         title: 'Imprimer maintenant', name: 'productionPrinter', icon: 'fa fa-print'
      }
   },
];

export const asyncRoutes = [
   {
      path: '/dashboard',
      redirect: '/dashboard/home',
      name: 'dashboard',
      component: () => import("@/pages/admin/Dashbord"),
      meta: {
         title: 'Tableau de Bord', icon: 'fa fa-cogs',
      },

      children: [
         {
            path: 'home',
            name: 'dashboard_home',
            component: () => import('@/pages/admin/home'),
            hidden: true,
            meta: {
               title: 'Molsat Application', icon: 'fa fa-gor', roles: ['GUEST', 'MAGAZINIER', 'COMMERCIAL']
            },
         },
         ...client,
         ...order,
         ...payments,
         ...Stock,
         ...containers,
         ...productions,
         ...Receptions,
         ...Products,
         ...billings,
         ...reglements,
         ...Providers,
         ...Users,
         ...settings,
         ...alerts,

      ],
   },

   {path: '*', redirect: '/404', hidden: true},

];

const createRouter = () => new Router({
   mode: 'history', // require service support
   scrollBehavior: () => ({y: 0}),
   routes: constantRoutes,
});

const router = createRouter();

// Detail see: https://github.com/vuejs/vue-router/issues/1234#issuecomment-357941465
export function resetRouter() {
   const newRouter = createRouter();
   router.matcher = newRouter.matcher; // reset router
}

export default router;
