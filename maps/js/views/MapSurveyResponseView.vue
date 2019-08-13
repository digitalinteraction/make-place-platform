<template lang="html">
  <map-modal
    v-if="mapComponent"
    :title="modalTitle"
    @close="onClose"
  >
    <div class="survey-response-view">
      <loading v-if="!renderedResponse"> Fetching response </loading>
      <template v-else>
        <div class="response" v-html="renderedResponse.body" />
        
        <!-- Add the voting section, if its been configured in the CMS -->
        <vote-section
          v-if="mapComponent.votingEnabled"
          :type="mapComponent.voteType"
          :data-id="response.id"
          :perms="mapComponent.permissions"
          :can-view="mapComponent.canViewVotes"
          :can-make="mapComponent.canMakeVotes"
          data-type="SurveyResponse"
        >
          {{mapComponent.voteTitle}}
        </vote-section>
        
        <!-- Add the comments section, if its been configured in the CMS -->
        <comment-section
          v-if="mapComponent.commentingEnabled"
          :data-id="response.id"
          :perms="mapComponent.permissions"
          :can-view="mapComponent.canViewComments"
          :can-make="mapComponent.canMakeComments"
          data-type="SurveyResponse"
          :placeholder="mapComponent.commentPlaceholder"
          :action="mapComponent.commentAction"
        >
          {{mapComponent.commentTitle}}
        </comment-section>
        
      </template>
    </div>
  </map-modal>
</template>

<script>
import axios from 'axios'
import L from 'leaflet'

import MapModal from '../components/MapModal'

export default {
  components: { MapModal },
  data() {
    return {
      response: null,
      renderedResponse: null
    }
  },
  computed: {
    modalTitle() {
      return this.renderedResponse ? this.renderedResponse.title : this.mapComponent.responseTitle
    },
    mapComponent() {
      return this.$store.getters.mapComponent(parseInt(this.$route.params.componentID))
    }
  },
  mounted() {
    if (!this.mapComponent) return this.$router.replace('/')
    
    this.fetchResponse()
  },
  methods: {
    onClose() {
      this.$router.replace('/')
    },
    async fetchResponse() {
      this.renderedResponse = null
      this.response = null
      
      // Craft the API url to view the response
      const surveyID = this.mapComponent.surveyID
      const responseID = this.$route.params.responseID
      const url = `${this.$config.api}/api/survey/${surveyID}/response/${responseID}`
      
      // Request the response as JSON and HTML
      // > TODO: This ought to be refactored to 1 endpoint
      const [viewRes, dataRes] = await Promise.all([
        axios.get(url + '/view'),
        axios.get(url)
      ])
      
      // Store both responses
      this.renderedResponse = viewRes.data
      this.response = dataRes.data
      
      this.addMapHighlight(this.response)
      this.centerMap(this.response)
    },
    addMapHighlight(response) {
      // Get the highlight question's value (if there is one)
      // The CMS lets you pick a question to highlight a geometry when viewed
      const highlightValue = this.mapComponent.highlightQuestion &&
        this.response.values[this.mapComponent.highlightQuestion] &&
        this.response.values[this.mapComponent.highlightQuestion].value
      
      // Only proceed if there is a value and it was answered
      if (!highlightValue || !highlightValue.geom) return
      
      let highlight = null
      
      // Create a line geometry for LINESTRING questions
      if (highlightValue.type === 'LINESTRING') {
        const points = highlightValue.geom.map(({ x, y }) => [x, y])
        highlight = L.polyline(points, {
          color: '#3886c9',
          weight: 5
        })
      }
    
      // if type === 'POINT' ...
    
      // If a highlight was created, render it
      if (highlight) {
        this.$store.commit('setHighlight', highlight)
      }
    },
    centerMap(response) {
      const positionValue = this.mapComponent.positionQuestion &&
        this.response.values[this.mapComponent.positionQuestion] &&
        this.response.values[this.mapComponent.positionQuestion].value
      
      if (!positionValue || !positionValue.geom) return
      
      this.$store.state.map.panTo([positionValue.geom.x, positionValue.geom.y], {
        animate: true
      })
    }
  }
}
</script>

<style lang="scss" scoped>
</style>
