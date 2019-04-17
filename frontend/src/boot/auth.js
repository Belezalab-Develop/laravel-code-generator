function isArrayOrString (variable) {
  if (typeof variable === typeof [] || typeof variable === typeof '') {
    return true
  }
  return false
}

export default ({ router, store, Vue }) => {
  router.beforeEach((to, from, next) => {
    const record = to.matched.find(record => record.meta.auth)
    if (record) {
      if (!store.getters['auth/loggedIn']) {
        router.push('/')
      } else if (isArrayOrString(record.meta.auth) && !store.getters['auth/check'](record.meta.auth)) {
        router.push('/account')
      }
    }
    next()
  })

  var helper = {}
  helper.register = (data) => { return store.dispatch('auth/register', data) }
  helper.loggedIn = () => { return store.getters['auth/loggedIn'] }
  helper.check = (roles) => { return store.getters['auth/check'](roles) }
  helper.login = (data) => { return store.dispatch('auth/login', data) }
  helper.logout = () => { return store.dispatch('auth/logout') }
  helper.verify = (token) => { return store.dispatch('auth/verify', token) }
  helper.passwordForgot = (data) => { return store.dispatch('auth/passwordForgot', data) }
  helper.passwordRecover = (data) => { return store.dispatch('auth/passwordRecover', data) }
  helper.fetch = () => { return store.dispatch('auth/fetch') }
  helper.user = () => { return store.getters['auth/user'] }
  Vue.prototype.$auth = helper
}
