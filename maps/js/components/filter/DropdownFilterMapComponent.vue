<template lang="html">
  <!-- Dropdown Filter Map Component -->
</template>

<script>
export default {
  props: [ 'options' ],
  mounted() {
    console.log('...')
    this.$store.commit('addControl', {
      group: 'Dropdown',
      type: 'DropdownFilter',
      options: this.options
    })
  }
}
</script>

<style lang="css">
</style>
