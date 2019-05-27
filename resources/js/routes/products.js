import Products from '../pages/Products/Products'
import NewProvider from '../pages/Products/NewProduct'
import ProductList from '../pages/Products/ProductsList'

export default [
    {
        name: 'allProducts',
        path: 'product',
        component: Products,
        redirect: '/product/product-list',
        meta: {
            title: 'Produits', name: 'allProducts', icon: 'fa fa-briefcase', roles: 'guest'
        },
        children: [
            {
                name: 'newProduct',
                path: 'new-product',
                component: NewProvider,
                meta: {
                    title: 'Nouveau Produit', name: 'newProduct', icon: 'fa fa-gor', roles: 'guest'
                },
            },
            {
                name: 'listProducts',
                path: 'product-list',
                component: ProductList,
                meta: {
                    title: 'Liste des produits', name: 'listProducts', icon: 'fa fa-gor', roles: 'guest'
                },
            },
        ]
    },
]
