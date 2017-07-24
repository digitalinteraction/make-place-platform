<template lang="html">
  <!-- <pre> {{options}} </pre> -->
</template>

<script>
import axios from 'axios'
import L from 'leaflet'

import SurveyResponseDetail from './detail/SurveyResponseDetail.vue'

export default {
  props: [ 'options' ],
  mounted() {
    
    if (this.options.canSubmit) {
      this.$store.commit('addAction', {
        title: this.options.actionMessage,
        colour: this.options.actionColour,
        icon: 'plus',
        onClick: this.actionHandler
      })
    }
    
    if (this.options.canView) {
      this.fetchResponses()
    }
  },
  computed: {
    surveyApi() {
      return `${this.$config.api}/api/survey/${this.options.surveyID}`
    }
  },
  methods: {
    makeIcon(colour) {
      return L.icon({
        iconUrl: `/public/images/pins/pin-${colour}.svg`,
        iconSize: [30, 56],
        iconAnchor: [15, 40]
      })
    },
    actionHandler() {
      
      let state = {
        type: 'PickingMapState',
        options: {
          onPick: this.positionPicked
        }
      }
      
      this.$store.commit('setMapState', state)
    },
    positionPicked(position) {
      
      // Do nothing if no position was picked
      if (!position) return
      
      // The detail to render the survey
      let detail = {
        type: 'SurveyFormDetail',
        options: {
          position: position,
          component: this.options,
          onCreate: this.responseCreated
        }
      }
      
      // Push the map state to the store
      this.$store.commit('setMapState', {
        type: 'DetailMapState',
        options: {
          title: '',
          detail: detail
        }
      })
      
    },
    responseCreated(response) {
      
      if (this.options.positionQuestion) {
        this.addResponsePin(response, this.options.positionQuestion)
      }
      this.$store.commit('resetMapState')
    },
    addResponsePin(response, posKey) {
      
      // Get the responses's position
      let pos = response.values[posKey].value
      
      // Skip if the response doesn't have a geometry
      if (!pos || !pos.geom || !pos.geom.x || !pos.geom.y) { return }
      
      // Generate an icon
      let icon = this.makeIcon(this.options.pinColour || 'primary')
      
      // Create a marker
      let marker = L.marker([pos.geom.x, pos.geom.y], { icon })
      
      // Listen for clicks
      marker.on('click', (e) => {
        this.responseClicked(response, e)
      })
      
      // Add the marker
      this.$store.state.clusterer.addLayer(marker)
    },
    async fetchResponses() {
      
      let res = await axios.get(`${this.surveyApi}/responses`)
      
      let posKey = this.options.positionQuestion
      
      // If not set (from the CMS) do nothing
      if (!posKey) return
      
      // Loop responses and create pins
      res.data.forEach((response) => {
        
        // Add a pin for it
        this.addResponsePin(response, posKey)
      })
    },
    responseClicked(response, e) {
      
      let highlight = null
      
      if (this.options.highlightQuestion && response.values[this.options.highlightQuestion]) {
        let value = response.values[this.options.highlightQuestion].value
        
        if (value.type === 'LINESTRING' && value.geom) {
          
          let points = value.geom.map(({x, y}) => { return [x, y] })
          
          highlight = L.polyline(points, {
            color: '#3886c9',
            weight: 5
          })
        }
      }
      
      this.$store.commit('setHighlight', highlight)
      
      // The detail to render our form
      let detail = {
        type: 'SurveyResponseDetail',
        options: {
          response: response,
          config: this.options
        }
      }
      
      
      // Transition to the detail state
      this.$store.commit('setMapState', {
        type: 'DetailMapState',
        options: {
          title: '',
          detail: detail,
          onClose: () => { this.$store.commit('setHighlight', null) }
        }
      })
    }
  }
}
</script>

<style lang="css">
</style>
