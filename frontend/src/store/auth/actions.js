import { axiosInstance } from 'boot/axios'
import { LocalStorage, SessionStorage } from 'quasar'

const REGISTER_ROUTE = '/auth/register'
const LOGIN_ROUTE = '/auth/login'
const USER_ROUTE = '/auth/user'
const PASSWORD_FORGOT_ROUTE = '/auth/password/forgot'
const PASSWORD_RECOVER_ROUTE = '/auth/password/recover'

export function register (state, data) {
  return axiosInstance.post(REGISTER_ROUTE, data)
}

export function login (state, data) {
  return axiosInstance.post(LOGIN_ROUTE, data.body).then((response) => {
    const token = response.data.data.token
    state.commit('setUser', response.data.data.user)
    axiosInstance.defaults.headers.common['Authorization'] = 'Bearer ' + token
    if (data.rememberMe) {
      LocalStorage.set('token', token)
    } else {
      SessionStorage.set('token', token)
    }
  })
}

export function fetch (state) {
  let token
  if (LocalStorage.has('token')) {
    token = LocalStorage.getItem('token')
  } else if (SessionStorage.has('token')) {
    token = SessionStorage.getItem('token')
  }
  if (token) {
    axiosInstance.defaults.headers.common['Authorization'] = 'Bearer ' + token
    return axiosInstance.get(USER_ROUTE).then((response) => {
      state.commit('setUser', response.data.data)
    })
  }
}

export function logout (state) {
  if (LocalStorage.has('token')) {
    LocalStorage.remove('token')
  } else if (SessionStorage.has('token')) {
    SessionStorage.remove('token')
  }
  state.commit('setUser', null)
}

export function verify (state, token) {
  return axiosInstance.get('/auth/verify/' + token)
}
export function passwordForgot (state, data) {
  return axiosInstance.post(PASSWORD_FORGOT_ROUTE, data)
}

export function passwordRecover (state, { token, data }) {
  return axiosInstance.post(PASSWORD_RECOVER_ROUTE + '/' + token, data)
}
