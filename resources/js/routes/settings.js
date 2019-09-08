export default [
   {
      name: 'settings',
      path: 'settings',
      component: () => import('@/pages/Setting/AppSettings'),
      meta: {
         title: 'Paramétrage', name: 'settings', icon: 'fa fa-cog', roles: 'guest'
      },
      children: [

         {
            name: 'settingColors',
            path: 'colors',
            component: () => import('@/pages/Setting/Colors/ColorSetting'),
            meta: {
               title: "Couleurs", name: 'colors', icon: 'fa fa-gor', roles: 'guest'
            },
         },
         {
            name: 'settingUnity',
            path: 'unities',
            component: () => import('@/pages/Setting/Unities/UnitySetting'),
            meta: {
               title: "Paramétrage des unités", name: 'settingUnity', icon: 'fa fa-gor', roles: 'guest'
            },
         },
         {
            name: 'settingCategory',
            path: 'categories',
            component: () => import('@/pages/Setting/Categories/CategorySetting'),
            meta: {
               title: "Paramétrage des unités", name: 'settingUnity', icon: 'fa fa-gor', roles: 'guest'
            },
         },
      ]
   },
]
