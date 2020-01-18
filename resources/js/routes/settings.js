export default [
   {
      name: 'settings',
      path: 'settings',
      component: () => import('@/pages/Setting/AppSettings'),
      meta: {
         title: 'Paramétrage', name: 'settings', icon: 'fa fa-cog',
      },
      children: [
         {
            name: 'settingColors',
            path: 'colors',
            component: () => import('@/pages/Setting/Colors/ColorSetting'),
            meta: {
               title: "Couleurs", name: 'settingColors', icon: 'fa fa-gor'
            },
            redirect: 'colors/colors-list',

            children: [
               {
                  name: 'colorsList',
                  path: 'colors-list',
                  component: () => import('@/pages/Setting/Colors/ColorsList'),
                  meta: {
                     title: "Couleurs", name: 'colorsList',
                  },
               },
               {
                  name: 'updateColor',
                  path: ':id/color',
                  component: () => import('@/pages/Setting/Colors/UpdateColor'),
                  meta: {
                     title: "Modifier Couleur", name: 'updateColor', icon: 'fa fa-gor'
                  },
               }
            ]
         },
         {
            name: 'settingUnity',
            path: 'unities',
            component: () => import('@/pages/Setting/Unities/UnitySetting'),
            meta: {
               title: "Unités", name: 'settingUnity', icon: 'fa fa-gor', roles: 'guest'
            },
         },
         {
            name: 'settingCategory',
            path: 'categories',
            component: () => import('@/pages/Setting/Categories/CategorySetting'),
            meta: {
               title: "Catégories", name: 'settingCategory', icon: 'fa fa-gor', roles: 'guest'
            },
         },
         {
            name: 'settingPayments',
            path: 'payments',
            component: () => import('@/pages/Setting/Payments/PaymentsType'),
            meta: {
               title: "Paiéments", name: 'settingPayments', icon: 'fa fa-gor', roles: 'guest'
            },
         },
      ]
   },
]
