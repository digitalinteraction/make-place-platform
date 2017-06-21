<template lang="html">
  <!--  -->
</template>

<script>
import axios from 'axios'
import L from 'leaflet'

export default {
  props: [ 'options' ],
  mounted() {
    
    this.$store.commit('addAction', {
      title: this.options.actionMessage,
      colour: this.options.actionColour,
      icon: 'plus',
      onClick: this.actionHandler
    })
    
    this.fetchResponses()
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
      
      // The detail to render the survey
      let detail = {
        type: 'SurveyFormDetail',
        options: { position: position, surveyID: this.options.surveyID }
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
    async fetchResponses() {
      
      let res = await axios.get(`${this.surveyApi}/responses`)
      
      let posKey = this.options.positionQuestion
      
      // Loop responses and create pins
      res.data.forEach((response) => {
        
        // Get the responses's position
        let pos = response.values[posKey].value.geom
        
        // Skip if the response doesn't have a geometry
        if (!pos || !pos.x || !pos.y) { return }
        
        // Generate an icon
        let icon = this.makeIcon(this.options.pinColour || 'blue')
        
        // Create a marker
        let marker = L.marker([pos.x, pos.y], { icon })
        
        // Listen for clicks
        marker.on('click', (e) => {
          this.responseClicked(response, e)
        })
        
        // Add the marker
        this.$store.state.clusterer.addLayer(marker)
      })
    },
    async responseClicked(response, e) {
      
      let detail = {
        type: 'SurveyResponse',
        options: {
          response: response,
          config: this.options
        }
      }
      
      let state = {
        type: 'SurveyResponse',
        options: {
          title: '',
          detail: detail
        }
      }
      
      // Transition to Detail state + render our response
      this.$store.commit('setMapDetail', {
        type: 'SurveyResponse',
        title: '',
        options: { response: response, config: this.options }
      })
      
      // Transition to the detail state
      this.$store.commit('setMapState', 'DetailMapState')
    }
  }
}
</script>

<style lang="css">
</style>
