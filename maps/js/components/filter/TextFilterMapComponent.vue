<template lang="html">
  <!-- Text Filter Map Component -->
</template>

<script>
import responseService from '../../services/responses'

export default {
  props: [ 'options' ],
  mounted() {
    
    // Request this question be fetched
    responseService.request(this.options.surveyID, [this.options.question.handle], {})
    
    // Add the control
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
