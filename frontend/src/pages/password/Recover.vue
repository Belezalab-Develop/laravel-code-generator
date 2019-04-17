<template>
  <q-page class="flex flex-center">
    <q-card
      square
      style="width: 400px; padding:50px"
    >
      <q-card-section>
        <div class="text-h6">
          {{ $t('headers.password_forgot') }}
        </div>
      </q-card-section>

      <q-card-section>
        <q-input
          id="password"
          v-model="form.password"
          type="password"
          :label="$t('labels.password')"
          bottom-slots
          required
          :error="$v.form.password.$error"
          :error-message="$t('error_labels.password_length', {length: minPasswordLength})"
        />
        <q-input
          id="repeatPassword"
          v-model="form.repeatPassword"
          type="password"
          :label="$t('labels.repeat_password')"
          bottom-slots
          required
          :error="$v.form.repeatPassword.$error"
          :error-message="$t('error_labels.password_match')"
        />
      </q-card-section>
      <q-card-actions>
        <q-btn
          class="fit"
          color="primary"
          :loading="loading"
          @click="recoverPassword"
        >
          {{ $t('buttons.submit') }}
        </q-btn>
      </q-card-actions>
    </q-card>
  </q-page>
</template>

<script>
import { required, sameAs, minLength } from 'vuelidate/lib/validators'

var minPasswordLength = 8

export default {
  name: 'PasswordRecover',
  data () {
    return {
      form: {
        password: '',
        repeatPassword: ''
      },
      loading: false,
      minPasswordLength: minPasswordLength
    }
  },

  mounted () {
    //
  },
  methods: {
    recoverPassword () {
      this.$v.form.$touch()
      if (!this.$v.form.$error) {
        this.loading = true
        this.token = this.$route.query.token
        this.$auth.passwordRecover({ token: this.token, data: { 'password': this.form.password } }).then((response) => {
          this.$q.dialog({
            message: this.$t('messages.password_recover_success')
          }).onOk(() => {
            this.$router.push('/login')
          })
        }).catch((error) => {
          this.$q.dialog({
            message: this.$t('errors.general_error')
          })
          console.log(error)
        }).finally(() => {
          this.loading = false
        })
      }
    }
  },
  validations: {
    form: {
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

<style>
[v-cloak] {
  display: none;
}
</style>
