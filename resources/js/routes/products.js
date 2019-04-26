import Products from '../pages/Products/Products'
import NewProvider from '../pages/Products/NewProduct'
// import ProvidersList from '../pages/Products/ProvidersList'

export default [
    {
        path: '/product',
        redirect: '/product/new-product'
    },
    {
        name: 'allProducts',
        path: '/product',
        component: Products,
        meta: {
            forVisitor: true
        },
        children: [
            {
                name: 'newProduct',
                path: 'new-product',
                component: NewProvider,
            },
            // {
            //     name: 'listProvider',
            //     path: 'product-list',
            //     component: ProvidersList,
            // },
        ]
    },
]
