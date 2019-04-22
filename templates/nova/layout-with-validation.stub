<template>
  <q-layout view="lHh Lpr lFf">
    <q-header
      elevated
      class="glossy"
    >
      <q-toolbar>
        <q-btn
          flat
          dense
          round
          aria-label="Menu"
          @click="leftDrawerOpen = !leftDrawerOpen"
        >
          <q-icon name="menu" />
        </q-btn>

        <q-toolbar-title>
          Quasar App
        </q-toolbar-title>

        <div>Quasar v{{ $q.version }}</div>
      </q-toolbar>
    </q-header>

    <q-drawer
      v-model="leftDrawerOpen"
      bordered
      content-class="bg-grey-2"
    >
      <q-scroll-area class="fit">
        <div class="column flex-center">
          <q-icon
            name="person"
            size="3rem"
            class="q-mt-lg"
          />
        </div>
        <q-list
          no-border
          link
          inset-delimiter
        >
          <q-item
            v-if="this.$auth.check()"
            clickable
            @click="$router.push('/account')"
          >
            <q-item-section avatar>
              <q-icon name="person" />
            </q-item-section>
            <q-item-section>
              <q-item-label>{{ $t('labels.my_account') }}</q-item-label>
            </q-item-section>
          </q-item>
          <q-item
            v-if="!this.$auth.check()"
            clickable
            @click="$router.push('/login')"
          >
            <q-item-section avatar>
              <q-icon name="exit_to_app" />
            </q-item-section>
            <q-item-section>
              <q-item-label>{{ $t('labels.login') }}</q-item-label>
            </q-item-section>
          </q-item>
          <q-item
            v-if="!this.$auth.check()"
            clickable
            @click="$router.push('/register')"
          >
            <q-item-section avatar>
              <q-icon name="person_add" />
            </q-item-section>
            <q-item-section>
              <q-item-label>{{ $t('labels.register') }}</q-item-label>
            </q-item-section>
          </q-item>
          <q-item
            v-if="this.$auth.check()"
            clickable
            @click="$router.push('/logout')"
          >
            <q-item-section avatar>
              <q-icon name="power_settings_new" />
            </q-item-section>
            <q-item-section>
              <q-item-label>{{ $t('labels.logout') }}</q-item-label>
            </q-item-section>
          </q-item>
        </q-list>
        <hr>
        <q-list
          no-border
          link
          inset-delimiter
        >
          <q-item-label header>
            {{ $t('headers.navigation') }}
          </q-item-label>
          <q-item
            clickable
            @click="$router.push('/')"
          >
            <q-item-section avatar>
              <q-icon name="home" />
            </q-item-section>
            <q-item-section>
              <q-item-label>{{ $t('labels.home') }}</q-item-label>
            </q-item-section>
          </q-item>
        </q-list>
        <q-list
          v-if="this.$auth.check('administrator')"
          no-border
          link
          inset-delimiter
        >
          <q-item-label header>
            {{ $t('headers.administrator') }}
          </q-item-label>
          <q-item
            clickable
            @click="$router.push('/admin/home')"
          >
            <q-item-section avatar>
              <q-icon name="home" />
            </q-item-section>
            <q-item-section>
              <q-item-label>{{ $t('labels.home') }}</q-item-label>
            </q-item-section>
          </q-item>
        </q-list>
      </q-scroll-area>
    </q-drawer>

    <q-page-container>
      <router-view />
    </q-page-container>
  </q-layout>
</template>

<script>
import { openURL } from 'quasar'

export default {
  name: 'MyLayout',
  data () {
    return {
      leftDrawerOpen: this.$q.platform.is.desktop
    }
  },
  methods: {
    openURL
  }
}
</script>

<style>
</style>
