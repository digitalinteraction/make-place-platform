<template lang="html">
  <div class="survey-response">
    
    <loading v-if="!rendered"> Fetching Response </loading>
    <div v-else>
      
      <!-- The response, rendered by the server -->
      <div class="response" v-html="rendered || '...'"></div>
      
      <!-- Voting -->
      <vote-section
        v-if="config.votingEnabled"
        :type="config.voteType"
        :data-id="response.id"
        :perms="config.permissions"
        :can-view="config.canViewVotes"
        :can-make="config.canMakeVotes"
        data-type="SurveyResponse">
        {{config.voteTitle}}
      </vote-section>
      
      <!-- Comments -->
      <comment-section
        v-if="config.commentingEnabled"
        :data-id="response.id"
        :perms="config.permissions"
        :can-view="config.canViewComments"
        :can-make="config.canMakeComments"
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
import L from 'leaflet'

export default {
  props: [ 'options' ],
  data() {
    return { rendered: null }
  },
  computed: {
    response() { return this.options.response },
    config() { return this.options.config }
  },
  watch: {
    options() {
      this.setup()
    }
  },
  mounted() {
    this.setup()
  },
  methods: {
    setup() {
      this.rendered = null
      this.fetchResponse()
      this.$emit('change-title', this.config.responseTitle)
    },
    async fetchResponse() {
      
      let base = `${this.$config.api}/api/survey/${this.response.surveyId}/response/${this.response.id}`
      
      let [ viewRes, dataRes ] = await Promise.all([
        axios.get(base + '/view'),
        axios.get(base)
      ])
      
      let fullResponse = dataRes.data
      this.rendered = viewRes.data.body
      
      
      // If we have a highlight question, render the highlight
      if (this.options.highlight && fullResponse.values[this.options.highlight]) {
        let value = fullResponse.values[this.options.highlight].value
        
        
        // Render the highlight depending on the type
        let highlight = null
        if (value && value.type === 'LINESTRING' && value.geom) {
          
          // If a linestring, create a path
          let points = value.geom.map(({x, y}) => { return [x, y] })
          highlight = L.polyline(points, {
            color: '#3886c9',
            weight: 5
          })
        }
        
        // Commit the highlight to be rendered
        this.$store.commit('setHighlight', highlight)
      }
      
      
    }
  }
}
</script>



<style lang="css">
</style>
