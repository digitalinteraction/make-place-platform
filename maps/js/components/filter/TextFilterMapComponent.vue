<template lang="html">
  <!-- Text Filter Map Component -->
</template>

<script>
export default {
  props: [ 'options' ],
  mounted() {
    this.$store.commit('addControl', {
      group: 'Text',
      type: 'TextFilter',
      options: this.options
    })
  }
}
</script>

<style lang="css">
</style>
