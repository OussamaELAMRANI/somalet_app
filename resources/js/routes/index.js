import adminRoutes from './Admin'
import Providers from './provider'
import Products from './products'
import Home from './../pages/home'
// import AR from './Arabe'
// import FR from './Francais'
// import EN from './English'

import NotFound from './../pages/NotFound'

export default [
    {
        path: '/',
        redirect: '/login'
    },

    {
        path: '/404',
        name: 'error_404',
        component: NotFound,

    },
    // {
    //     path: '/home',
    //     name: 'home_page',
    //     component: Home,
    //
    // },
    ...adminRoutes,
    // ...Providers,
    // ...Products,
    // ...FR,
    // ...AR,
    // ...EN,
    {
        path: '*',
        redirect: '/404'
    }
]
