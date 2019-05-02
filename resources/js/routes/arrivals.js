import Arrivals from '../pages/Arrivals/Arrivals'
import NewArrival from '../pages/Arrivals/NewArrival'
import ArrivalsList from '../pages/Arrivals/ArrivalsList'

export default [
    {
        path: 'arrivals',
        redirect: 'arrivals/arrivals-list'
    },
    {
        name: 'all_arrivals',
        path: 'arrivals',
        component: Arrivals,
        meta: {
            // forVisitor: true
        },
        children: [
            {
                name: 'new_arrival',
                path: 'new-arrival',
                component: NewArrival,
            },
            {
                name: 'list_arrivals',
                path: 'arrivals-list',
                component: ArrivalsList,
            },
        ]
    },
]
