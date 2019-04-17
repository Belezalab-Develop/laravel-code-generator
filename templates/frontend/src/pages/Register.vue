<template>
  <q-page
    padding
    class="flex flex-center"
  >
    <q-card
      square
      style="width: 400px; padding:50px"
    >
      <q-card-section>
        <div class="text-h6">
          {{ $t('headers.register') }}
        </div>
      </q-card-section>

      <q-card-section>
        <q-input
          id="email"
          v-model.trim="data.email"
          type="text"
          :label="$t('labels.email')"
          bottom-slots
          required
          autofocus
          :error="$v.data.email.$error"
          :error-message="$t('error_labels.email')"
        />
        <q-input
          v-model.trim="data.name"
          type="text"
          :label="$t('labels.name')"
          required
          :error="$v.data.name.$error"
        />
        <q-input
          id="password"
          v-model="data.password"
          type="password"
          :label="$t('labels.password')"
          bottom-slots
          required
          :error="$v.data.password.$error"
          :error-message="$t('error_labels.password_length', { length: minPasswordLength })"
        />
        <q-input
          id="repeatPassword"
          v-model="data.repeatPassword"
          type="password"
          :label="$t('labels.repeat_password')"
          bottom-slots
          required
          :error="$v.data.repeatPassword.$error"
          :error-message="$t('error_labels.password_match')"
        />
      </q-card-section>
      <q-card-actions>
        <q-btn
          color="primary"
          :loading="loading"
          :label="$t('buttons.register')"
          @click="register"
        />
      </q-card-actions>
    </q-card>
  </q-page>
</template>

<style>
</style>

<script>
import { email, required, sameAs, minLength } from 'vuelidate/lib/validators'

const minPasswordLength = 8

export default {
  name: 'Register',
  data () {
    return {
      data: {
        name: '',
        email: '',
        password: '',
        repeatPassword: ''
      },
      loading: false,
      minPasswordLength: minPasswordLength
    }
  },
  methods: {
    register () {
      this.$v.data.$touch()
      if (!this.$v.data.$error) {
        this.loading = true
        this.$auth.register(this.data).then(() => {
          this.$q.dialog({
            message: this.$t('messages.account_created')
          })
        }).catch((error) => {
          if (error.response) {
            this.$q.dialog({
              message: this.$t('errors.general_error')
            })
            console.log(error.response)
          }
        }).finally(() => {
          this.loading = false
        })
      }
    }
  },
  validations: {
    data: {
      name: { required },
      email: {
        required,
        email
      },
      password: {
        required,
        minLength: minLength(minPasswordLength)
      },
      repeatPassword: {
        sameAsPassword: sameAs('password')
      }
    }
  }
}
</script>
