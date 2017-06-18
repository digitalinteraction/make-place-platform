<template lang="html">
  <div class="survey-response">
    
    <loading v-if="!rendered"> Fetching Response </loading>
    <div v-else>
      
      <!-- The response, rendered by the server -->
      <div class="response" v-html="rendered || '...'"></div>
      
      
      <!-- Voting ... -->
      <voting :data-id="response.id"> What do you think? </voting>
      
      <!-- Comments ... -->
      
      
    </div>
    
    
  </div>
  
</template>



<script>
import axios from 'axios'

export default {
  props: [ 'data' ],
  data() {
    return {
      rendered: null
    }
  },
  computed: {
    response() { return this.data }
  },
  mounted() {
    this.fetchResponse()
  },
  methods: {
    async fetchResponse() {
      
      // A little delay to smooth the transition (api is too fast!?)
      // await new Promise((resolve) => { setTimeout(resolve, 300) })
      
      let res = await axios.get(
        `${this.$config.api}/api/survey/${this.data.surveyId}/response/${this.data.id}`
      )
      
      this.$emit('change-title', res.data.title)
      this.rendered = res.data.body
      
      // console.log(res.data)
    }
  }
}
</script>



<style lang="css">
</style>
