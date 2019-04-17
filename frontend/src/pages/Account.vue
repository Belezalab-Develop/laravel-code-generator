<template>
  <q-page class="flex flex-center">
    <p>
      Welcome {{ this.$auth.user().email }}
    </p>
  </q-page>
</template>

<style>
</style>

<script>
export default {
  name: 'Account'
}
</script>
