import SignUp from '../pages/admin/SignUp'
import LogIn from '../pages/admin/LogIn'
import dashboard from './../pages/admin/Dashbord'
import provider from './provider'
import client from './client'
import products from './products.js'
import Home from './../pages/home'
import arrivals from './arrivals'
import Users from "../pages/admin/Users";
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
        // meta: {
        //     requiresAuth: true
        // },
        meta: {
            title: 'Utilisateur', name: 'dashboard_main', icon: 'fa fa-briefcase', roles: 'guest'
        },
        children: [
            {
                path: 'home',
                component: Home,
            },
            {
                name: 'Allusers',
                path: 'users',
                component: Users,
                meta: {
                    title: 'Liste des Utilisateurs', name: 'Allusers', icon: 'fa fa-gor', roles: 'guest'
                },
            },
            ...provider,
            ...client,
            ...products,
            ...arrivals
        ],
    }
]
