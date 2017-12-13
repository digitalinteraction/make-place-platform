<template lang="html">
  <!--  -->
</template>

<script>
export default {
  props: [ 'options' ],
  mounted() {
    this.$store.commit('addControl', {
      group: 'Temporal',
      type: 'TemporalFilter',
      options: {
        mode: this.options.mode
      }
    })
  }
}
</script>

<style lang="css">
</style>
