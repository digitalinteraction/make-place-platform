<template lang="html">
  <!-- Dropdown Filter Map Component -->
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
      group: 'Dropdown',
      type: 'DropdownFilter',
      options: this.options
    })
  }
}
</script>

<style lang="css">
</style>
