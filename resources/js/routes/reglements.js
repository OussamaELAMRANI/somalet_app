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
            name: 'outstandingPayments',
            path: 'impaye',
            component: () => import('@/pages/Billings/OutstandingPayments'),
            meta: {
               title: "Impayés", name: 'outstandingPayments', icon: 'fa fa-gor', roles: 'guest'
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
         {
            name: 'operationMoney',
            path: 'operation',
            component: () => import('@/pages/Reglement/OperationFromBank'),
            meta: {
               title: "Operation", name: 'operationMoney', icon: 'fa fa-gor', roles: 'ADMINE'
            },
            hidden: true
         },
         {
            name: 'adjust',
            path: ':id/adjust',
            component: () => import('@/pages/Billings/AdjustOustanding'),
            meta: {
               title: "Régler les impayés", name: 'adjust', icon: 'fa fa-gor', roles: 'ADMINE'
            },
            hidden: true
         },
         {
            name: 'UpDeadline',
            path: ':id/deadline',
            component: () => import('@/pages/Billings/UpdateDeadline'),
            meta: {
               title: "Modifier la date d'écheancé", name: 'UpDeadline', icon: 'fa fa-gor', roles: 'ADMINE'
            },
            hidden: true
         },

      ]
   },
]
