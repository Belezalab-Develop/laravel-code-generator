
const routes = [
  {
    path: '/',
    component: () => import('layouts/Master.vue'),
    children: [
      { path: '', component: () => import('pages/Index.vue') },
      { path: '/login', name: 'login', component: () => import('pages/Login') },
      { path: '/logout', name: 'logout', component: () => import('pages/Logout') },
      { path: '/register', name: 'register', component: () => import('pages/Register') },
      { path: '/verification', name: 'verification', component: () => import('pages/Verification') },
      { path: '/password',
        name: 'password',
        component: { render: h => h('router-view') },
        children: [
          { path: 'forgot', name: 'forgot', component: () => import('pages/password/Forgot') },
          { path: 'recover', name: 'recover', component: () => import('pages/password/Recover') }
        ]
      },
      { path: '/account', meta: { auth: true }, name: 'account', component: () => import('pages/Account') },
      { path: '/admin',
        meta: { auth: ['administrator'] },
        name: 'admin',
        component: { render: h => h('router-view') },
        children: [
          { path: 'home', name: 'home', component: () => import('pages/admin/Home') }
        ]
      }
    ]
  }
]

// Always leave this as last one
if (process.env.MODE !== 'ssr') {
  routes.push({
    path: '*',
    component: () => import('pages/Error404.vue')
  })
}

export default routes
