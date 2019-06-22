import Arrivals from '../pages/Arrivals/Arrivals'
import NewArrival from '../pages/Arrivals/NewArrival'
import ArrivalsList from '../pages/Arrivals/ArrivalsList'

export default [
    {
        name: 'all_arrivals',
        path: 'arrivals',
        component: Arrivals,
        redirect: 'arrivals/arrivals-list',
        meta: {
            title: 'Arrivage', name: 'all_arrivals', icon: 'fa fa-tasks', roles: 'guest'
        },
        children: [
            {
                name: 'new_arrival',
                path: 'new-arrival',
                component: NewArrival,
                meta: {
                    title: 'Nouveau arrivage', name: 'new_arrival', icon: 'fa fa-briefcase', roles: 'guest'
                },
            },
            {
                name: 'update_arrival',
                path: ':id/update',
                component: NewArrival,
                hidden:true,
                meta: {
                    title: 'Modifier arrivage', name: 'update_arrival', icon: 'fa fa-briefcase', roles: 'guest'
                },
            },
            {
                name: 'list_arrivals',
                path: 'arrivals-list',
                component: ArrivalsList,
                meta: {
                    title: 'Liste des arrivages', name: 'list_arrivals', icon: 'fa fa-briefcase', roles: 'guest'
                },
            },
        ]
    },
]
