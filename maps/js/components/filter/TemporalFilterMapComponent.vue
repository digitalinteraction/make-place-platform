<template lang="html">
  <!-- Temporal Filter Map Component -->
</template>

<script>
export default {
  props: [ 'options' ],
  mounted() {
    this.$store.commit('addControl', {
      group: 'Temporal',
      type: 'TemporalFilter',
      options: this.options
    })
  }
}
</script>

<style lang="css">
</style>
