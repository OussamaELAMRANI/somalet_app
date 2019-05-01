import Products from '../pages/Products/Products'
import NewProvider from '../pages/Products/NewProduct'
import ProductList from '../pages/Products/ProductsList'

export default [
    {
        path: 'product',
        redirect: '/product/product-list'
    },
    {
        name: 'allProducts',
        path: 'product',
        component: Products,
        meta: {
            // forVisitor: true
        },
        children: [
            {
                name: 'newProduct',
                path: 'new-product',
                component: NewProvider,
            },
            {
                name: 'listProducts',
                path: 'product-list',
                component: ProductList,
            },
        ]
    },
]
