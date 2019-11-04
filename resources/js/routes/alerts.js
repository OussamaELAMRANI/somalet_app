export default [
    {
        name: 'all_alerts',
        path: 'alerts',
        component: () => import('@/pages/Alerts/Alerts'),
        redirect: 'receptions/receptions-list',
        meta: {
            title: 'Alerts', name: 'all_alerts', icon: 'fa fa-exclamation-triangle', roles: ['MAGAZINIER','COMMERCIAL']
        },
        children: [
            {
                name: 'stockAlert',
                path: 'stock',
                component: () => import('@/pages/Alerts/StockAlert'),
                meta: {
                    title: 'Alert de Stock', name: 'stockAlert', icon: 'fa fa-briefcase', roles: ['MAGAZINIER','COMMERCIAL']
                },
            },
        ]
    },
]
