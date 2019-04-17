<template>
  <div id="q-app">
    <router-view />
  </div>
</template>

<script>
import { LocalStorage, Dialog } from 'quasar'
import { axiosInstance } from 'boot/axios'
import { i18nInstance, setLocale } from 'boot/i18n'

export default {
  name: 'App',
  preFetch ({ store, redirect }) {
    redirect(false)
    axiosInstance.get('/statics/config.json?' + new Date().getTime()).then((response) => {
      axiosInstance.defaults.baseURL = response.data.API_URL
      setLocale(response.data.LOCALE)

      store.dispatch('auth/fetch').then(() => {
        redirect(true)
      }).catch(() => {
        store.dispatch('auth/logout').then(() => {
          redirect(true)
        })
      })
    }).catch(() => {
      console.error('No config file found. Abort')
    })

    axiosInstance.get('/statics/version.json?' + new Date().getTime()).then((response) => {
      let version = LocalStorage.getItem('version')
      if (version) {
        console.log('Current version: ' + version)
        if (response.data.VERSION !== version) {
          console.log('New version available: ' + response.data.VERSION)
          Dialog.create({
            message: i18nInstance.t('messages.update_available'),
            ok: true
          }).onOk(() => {
            LocalStorage.set('version', response.data.VERSION)
            window.location.reload(true)
          })
        }
      } else {
        LocalStorage.set('version', response.data.VERSION)
      }
    })
  }
}
</script>

<style>
</style>
