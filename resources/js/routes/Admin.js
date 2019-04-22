import SignUp from '../pages/admin/SignUp'
import LogIn from '../pages/admin/LogIn'
import dashboard from './../pages/admin/Dashbord'

// const dashboard = () => import('./../components/Admin/Dashboard');
// const Users = () => import('./../components/Admin/Users');

export default [
    {
        path: '/sign-up',
        component: SignUp,
        meta: {
            forVisitor: true
        }
    }, {
        path: '/login',
        component: LogIn,
        meta: {
            forVisitor: true
        }
    },
    {
        path: '/admin',
        name: 'admin_main',
        component: dashboard,
        meta: {
            requiresAuth: true
        },
        // children: [
        //     {
        //         path: 'users',
        //         component: Users,
        //         meta: {
        //             requiresAuth: true
        //         }
        //     },
        //
        // ],
    }
]
