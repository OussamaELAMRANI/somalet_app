export default [
   {
      name: 'reglements',
      path: 'reglements',
      component: () => import('@/pages/Reglement/Reglements'),
      redirect: 'reglements/caisse',
      meta: {
         title: 'Facturation', name: 'reglements', icon: 'fa fa-cash-register', roles: 'guest'
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
            component: () => import('@/pages/Billings/BillingsList'),
            meta: {
               title: "Portfeuille", name: 'portfeuille', icon: 'fa fa-gor', roles: 'guest'
            },
         },
         {
            name: 'monthBilling',
            path: ':year/:month/billings',
            component: () => import('@/pages/Billings/Monthly'),
            meta: {
               title: "PortFeuille Monsuelle", name: 'monthBilling', icon: 'fa fa-gor', roles: 'guest'
            },
            hidden: true
         },
         {
            name: 'banks',
            path: 'banks',
            component: () => import('@/pages/Reglement/Banks'),
            redirect: 'banks/molsat',
            meta: {
               title: "Banques", name: 'banks', icon: 'fa fa-gor', roles: 'guest'
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
               {
                  name: 'bank_bb',
                  path: 'bb',
                  component: () => import('@/pages/Reglement/banks/BankBB'),
                  meta: {
                     title: "IF", name: 'bank_bb', icon: 'fa fa-gor', roles: 'guest'
                  },
               },

            ]
         },
         {
            name: 'validatePayments',
            path: 'validate',
            component: () => import('@/pages/Reglement/ValidatePayments'),
            meta: {
               title: "A encaissé", name: 'validatePayments', icon: 'fa fa-gor', roles: 'guest'
            },
         },
         {
            name: 'transferMoney',
            path: 'transfer',
            component: () => import('@/pages/Reglement/TransferMoney'),
            meta: {
               title: "Transfére d'argent", name: 'transferMoney', icon: 'fa fa-gor', roles: 'guest'
            },
            hidden: true
         },

      ]
   },
]
