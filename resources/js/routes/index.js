import adminRoutes from './Admin'
import Providers from './provider'
import Products from './products'
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
    ...adminRoutes,
    ...Providers,
    ...Products,
    // ...FR,
    // ...AR,
    // ...EN,
    {
        path: '*',
        redirect: '/404'
    }
]
