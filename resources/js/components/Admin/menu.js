const menu = (user_type) => {
    return [
        {
            header: true,
            title: 'Dashboard',
        },
        { // item
            href: '/dashboard/home',
            title: 'Dashboard',
            icon: 'fa fa-bullseye',
            // badge: {
            //     text: 'new',
            //     class:'badge sm-badge badge-danger'
            //     // attributes: {}
            // }
        },
        { // item Users
            title: 'Utilisateurs',
            icon: 'fa fa-users',
            child: [
                {
                    href: '/users/new-user',
                    title: 'Nouveau',
                },
                {
                    href: '/users/provider-list',
                    title: 'Utilisateurs',
                },
                {
                    href: '/users/permission',
                    title: 'Permissions',
                }
            ]
        },
        { // item with child
            title: 'Fournisseur',
            icon: 'fa fa-briefcase',
            child: [
                {
                    href: '/dashboard/provider/new-provider',
                    title: 'Nouveau',
                },
                {
                    href: '/dashboard/provider/provider-list',
                    title: 'Fournisseurs',
                }
            ]
        },
        { // item with child
            title: 'Produits',
            icon: 'fa fa-tasks',
            child: [
                {
                    href: '/dashboard/product/new-product',
                    title: 'Nouveau',
                },
                {
                    href: '/dashboard/product/product-list',
                    title: 'Produits',
                }
            ]
        },
        { // item with child
            title: 'Arrivage',
            icon: 'fa fa-dropbox',
            child: [
                {
                    href: '/dashboard/arrivals/new-arrival',
                    title: 'Nouveau',
                },
                {
                    href: '/dashboard/arrivals/arrival-list',
                    title: 'Arrivages',
                }
            ]
        },
        { // item with child
            title: 'Commande',
            icon: 'fa fa-dropbox',
            child: [
                {
                    href: '/dashboard/arrivals/new-arrival',
                    title: 'Nouveau',
                },
                {
                    href: '/dashboard/arrivals/arrival-list',
                    title: 'Commandes',
                }
            ]
        },
    ]
}
export default menu()
