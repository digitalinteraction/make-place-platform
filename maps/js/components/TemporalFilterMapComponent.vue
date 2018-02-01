<template lang="html">
  <!--  -->
</template>

<script>
export default {
  props: [ 'options' ],
  mounted() {
    console.log(this.options)
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
