<template lang="html">
  <!-- <pre> {{options}} </pre> -->
</template>

<script>

import L from 'leaflet'
import responsesService from '../services/responses'

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
    
    let posKey = this.options.positionQuestion
    
    // If not set (from the CMS) do nothing
    if (!this.options.canView || !posKey || !this.options.renderResponses) return
    
    // Register to get responses
    responsesService.request(this.options.surveyID, [posKey], {
      fetched: (responses) => {
        responses.forEach(r => this.addResponsePin(r, posKey))
      },
      redraw: (responses) => {
        console.log('redraw', responses)
      },
      created: (response) => {
        this.addResponsePin(response, posKey)
      }
    })
    
  },
  computed: {
    surveyApi() {
      return `${this.$config.api}/api/survey/${this.options.surveyID}`
    }
  },
  methods: {
    makeIcon(colour) {
      return L.icon({
        iconUrl: `/static/img/pins/pin-${colour}.svg`,
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
      
      responsesService.responseCreated(response)
       
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
    responseClicked(response, e) {
      
      // The detail to render our form
      let detail = {
        type: 'SurveyResponseDetail',
        options: {
          response: response,
          config: this.options,
          highlight: this.options.highlightQuestion
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
