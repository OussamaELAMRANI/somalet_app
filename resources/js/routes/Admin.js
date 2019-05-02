import SignUp from '../pages/admin/SignUp'
import LogIn from '../pages/admin/LogIn'
import dashboard from './../pages/admin/Dashbord'
import provider from './provider'
import products from './products.js'
import Home from './../pages/home'
import arrivals from './arrivals'
// const dashboard = () => import('./../components/Admin/Dashboard');
// const Users = () => import('./../components/Admin/Users');

export default [
    {
        path: '/sign-up',
        component: SignUp,
        meta: {
            forVisitor: true
        }
    },
    {
        path: '/login',
        component: LogIn,
        meta: {
            forVisitor: true
        }
    },
    {
        path: '/dashboard',
        redirect: '/dashboard/home'
    },
    {
        path: '/dashboard',
        name: 'dashboard_main',
        component: dashboard,
        meta: {
            requiresAuth: true
        },
        children: [
            {
                path: 'home',
                component: Home,
            },
            ...provider,
            ...products,
            ...arrivals
        ],
    }
]
