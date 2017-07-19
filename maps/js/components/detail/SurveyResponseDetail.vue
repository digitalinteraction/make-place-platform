<template lang="html">
  <div class="survey-response">
    
    <loading v-if="!rendered"> Fetching Response </loading>
    <div v-else>
      
      <!-- The response, rendered by the server -->
      <div class="response" v-html="rendered || '...'"></div>
      
      
      <!-- Voting -->
      <vote-section
        :data-id="response.id"
        data-type="SurveyResponse">
        {{config.voteTitle}}
      </vote-section>
      
      <!-- Comments -->
      <comment-section
        :data-id="response.id"
        :can-comment="config.canComment"
        data-type="SurveyResponse"
        :placeholder="config.commentPlaceholder"
        :action="config.commentAction">
        {{config.commentTitle}}
      </comment-section>
      
      
    </div>
    
    
  </div>
  
</template>



<script>
import axios from 'axios'

export default {
  props: [ 'options' ],
  data() {
    return { rendered: null }
  },
  computed: {
    response() { return this.options.response },
    config() { return this.options.config }
  },
  mounted() {
    this.fetchResponse()
    this.$emit('change-title', this.config.responseTitle)
  },
  methods: {
    async fetchResponse() {
      
      // A little delay to smooth the transition (api is too fast!?)
      // await new Promise((resolve) => { setTimeout(resolve, 300) })
      
      let res = await axios.get(
        `${this.$config.api}/api/survey/${this.response.surveyId}/response/${this.response.id}`
      )
      
      // this.$emit('change-title', res.data.title)
      this.rendered = res.data.body
      
    }
  }
}
</script>



<style lang="css">
</style>
