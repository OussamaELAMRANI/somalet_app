import Vue from 'vue';
import Router from 'vue-router';

Vue.use(Router);


// import adminRoutes from './Admin'
import Providers from './provider'
import client from './client'
import Products from './products'
import arrivals from './arrivals'
import Users from './users'
import Receptions from './receptions'
import Stock from './stock'
import order from './orders'
import billings from './billings'
import reglements from './reglements'
import settings from './settings'

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
      component: () => import('@/pages/NotFound'),
   },
];

export const asyncRoutes = [
   {
      path: '/dashboard',
      redirect: '/dashboard/home',
      name: 'dashboard',
      component: () => import("@/pages/admin/Dashbord"),
      meta: {
         title: 'Tableau de Bord', icon: 'fa fa-cogs', roles: 'guest'
      },

      children: [
         {
            path: 'home',
            name: 'dashboard_home',
            component: () => import('@/pages/admin/home'),
            hidden: true,
            meta: {
               title: 'Molsat Application', icon: 'fa fa-gor', roles: 'guest'
            },
         },
         ...Users,
         ...Providers,
         ...client,
         ...Products,
         ...arrivals,
         ...Receptions,
         ...Stock,
         ...order,
         ...billings,
         ...reglements,
         ...settings,

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
