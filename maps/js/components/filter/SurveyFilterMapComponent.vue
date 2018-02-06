<template lang="html">
  <!-- SurveyFilterMapComponent -->
</template>

<script>
export default {
  props: [ 'options' ],
  mounted() {
    
    // Add the control
    this.$store.commit('addControl', {
      group: 'Survey',
      type: 'SurveyFilter',
      options: this.options
    })
  }
}
</script>

<style lang="css">
</style>
