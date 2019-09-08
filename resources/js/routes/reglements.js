export default [
   {
      name: 'reglements',
      path: 'reglements',
      component: () => import('@/pages/Reglement/Reglements'),
      redirect:'reglements/caisse',
      meta: {
         title: 'Réglements', name: 'reglements', icon: 'fa fa-cash-register', roles: 'guest'
      },
      children: [

         {
            name: 'caisse',
            path: 'caisse',
            component: () => import('@/pages/Reglement/Caisse'),
            meta: {
               title: "Caisse", name: 'caisse', icon: 'fa fa-gor', roles: 'guest'
            },
         },
         {
            name: 'portfeuille',
            path: 'portfeuille',
            component: () => import('@/pages/Reglement/PortFeuille'),
            meta: {
               title: "Portfeuille", name: 'portfeuille', icon: 'fa fa-gor', roles: 'guest'
            },
         },
         {
            name: 'banks',
            path: 'banks',
            component: () => import('@/pages/Reglement/Banks'),
            redirect:'banks/molsat',
            meta: {
               title: "Banque", name: 'banks', icon: 'fa fa-gor', roles: 'guest'
            },
            children: [
               {
                  name: 'bank_molsat',
                  path: 'molsat',
                  component: () => import('@/pages/Reglement/banks/BankMolsat'),
                  meta: {
                     title: "MOLSAT", name: 'bank_molsat', icon: 'fa fa-gor', roles: 'guest'
                  },
               },
               {
                  name: 'bank_if',
                  path: 'if',
                  component: () => import('@/pages/Reglement/banks/BankIf'),
                  meta: {
                     title: "IF", name: 'bank_if', icon: 'fa fa-gor', roles: 'guest'
                  },
               },
            ]
         },


      ]
   },
]
