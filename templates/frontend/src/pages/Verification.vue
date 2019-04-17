<template>
  <q-page
    v-cloak
    padding
  >
    <p>
      {{ message }}
    </p>
  </q-page>
</template>

<script>
export default {
  name: 'Verification',
  data () {
    return {
      token: '',
      message: ''
    }
  },
  mounted () {
    this.verifyUser()
  },
  methods: {
    verifyUser () {
      this.token = this.$route.query.token

      this.$auth.verify(this.token).then((response) => {
        this.message = this.$i18n.t('messages.verification_success')
      })
        .catch((error) => {
          this.message = this.$i18n.t('messages.verification_failed')
          console.log(error)
        })
    }
  }
}
</script>

<style>
[v-cloak] {
  display: none;
}
</style>
