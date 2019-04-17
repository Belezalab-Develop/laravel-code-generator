<template>
  <div />
</template>

<script>
export default {
  name: 'Logout',
  mounted () {
    this.$q.dialog({
      title: this.$i18n.t('messages.confirm'),
      message: this.$i18n.t('messages.logout_confirmation'),
      ok: this.$i18n.t('buttons.logout'),
      cancel: this.$i18n.t('buttons.cancel')
    }).onOk(() => {
      this.$auth.logout().then(() => {
        this.$router.push('/')
      })
    }).onCancel(() => {
      this.$router.go(-1)
    })
  }
}
</script>
