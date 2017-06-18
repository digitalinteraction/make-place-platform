<template lang="html">
  <!-- <p> Survey Map Component: {{config.actionMessage}} </p> -->
</template>

<script>
import axios from 'axios'

export default {
  props: [ 'config' ],
  mounted() {
    
    this.$store.commit('addAction', {
      title: this.config.actionMessage,
      colour: this.config.actionColour,
      onClick: this.actionHandler
    })
    
    this.fetchResponses()
  },
  methods: {
    actionHandler() {
      console.log('Ouch!')
    },
    async fetchResponses() {
      
      let res = await axios.get(
        `${this.$config.base}/api/survey/${this.config.surveyID}/responses`
      )
      
      console.log(res.data)
    }
  }
}
</script>

<style lang="css">
</style>
