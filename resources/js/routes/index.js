import Vue from 'vue';
import Router from 'vue-router';

Vue.use(Router);


// import adminRoutes from './Admin'
import Providers from './provider'
import Products from './products'
import arrivals from './arrivals'

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
    },
    {
        path: '/404',
        hidden: true,
        component: () => import('@/pages/NotFound'),
    },
];

export const asyncRoutes = [
    {
        path: '/dashboard',
        redirect: '/dashboard/home',
        component: () => import("@/pages/admin/Dashbord"),
        meta: {
            title: 'dashboard', icon: 'fa fa-cogs', roles: 'guest'
        },

        children: [
            {
                path: 'home',
                name: 'dashboard_home',
                component: () => import('@/pages/admin/home'),
                hidden: true,
                meta: {
                    title: 'dashboard_home', icon: 'fa fa-gor', roles: 'guest'
                },
            },
            ...Providers,
            ...Products,
            ...arrivals,
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
