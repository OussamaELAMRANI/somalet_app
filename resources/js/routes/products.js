import Products from '../pages/Products/Products'
import ProductList from '../pages/Products/ProductsList'

export default [
    {
        name: 'allProducts',
        path: 'product',
        component: Products,
        redirect: 'product/product-list',
        meta: {
            title: 'Produits', name: 'allProducts', icon: 'fa fa-cube', roles: 'guest'
        },
        children: [
            {
                name: 'newProduct',
                path: 'new-product',
                component: () => import('@/pages/Products/NewProduct'),
                meta: {
                    title: 'Nouveau Produit', name: 'newProduct', icon: 'fa fa-gor', roles: 'guest'
                },
            },
            {
                name: 'updateProduct',
                hidden: true,
                path: ':id/update',
                component: () => import('@/pages/Products/NewProduct'),
                meta: {
                    title: 'Modifier Produit', name: 'updateProduct', icon: 'fa fa-gor', roles: 'guest'
                },
            },
           {
                name: 'detailProduct',
                hidden: true,
                path: ':id/detail',
                component: () => import('@/pages/Products/DetailProduct'),
                meta: {
                    title: 'Détail Produit', name: 'detailProduct', icon: 'fa fa-gor', roles: 'guest'
                },
            },
            {
                name: 'deleteProduct',
                hidden: true,
                path: ':id/delete',
                meta: {
                    title: 'delete Produit', name: 'deleteProduct', icon: 'fa fa-gor', roles: 'guest'
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
