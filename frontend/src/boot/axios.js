import axios from 'axios'
import { Notify } from 'quasar'
import { i18nInstance } from 'boot/i18n'

const axiosInstance = axios.create({})

axiosInstance.interceptors.response.use((response) => {
  return response
}, (error) => {
  if (!error.response) {
    Notify.create(i18nInstance.t('errors.network'))
  }
  return Promise.reject(error)
})

export default ({ Vue }) => {
  Vue.prototype.$axios = axiosInstance
  Vue.axios = Vue.prototype.$axios
}

export { axiosInstance }
