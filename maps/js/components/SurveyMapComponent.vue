<template lang="html">
  <!-- <p> Survey Map Component: {{config.actionMessage}} </p> -->
</template>

<script>
import axios from 'axios'
import L from 'leaflet'
import DetailMapState from '../state/DetailMapState.vue'
import SurveyMapComponent from './details/SurveyResponseComponent.vue'

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
    makeIcon(colour) {
      return L.icon({
        iconUrl: `/public/images/pins/pin-${colour}.svg`,
        iconSize: [30, 56],
        iconAnchor: [15, 40]
      })
    },
    async fetchResponses() {
      
      let res = await axios.get(
        `${this.$config.api}/api/survey/${this.config.surveyID}/responses`
      )
      
      let posKey = this.config.positionQuestion
      
      // Loop responses and create pins
      res.data.forEach((response) => {
        
        // Get the responses's position
        let pos = response.values[posKey].value.geom
        
        // Generate an icon
        let icon = this.makeIcon(this.config.pinColour || 'blue')
        
        // Create a marker
        let marker = L.marker([pos.x, pos.y], { icon })
        
        // Listen for clicks
        marker.on('click', (e) => {
          this.responseClicked(response, e)
        })
        
        // Add the marker
        this.$store.state.clusterer.addLayer(marker)
      })
      
      // console.log(res.data)
    },
    async responseClicked(response, e) {
      console.log(response)
      
      // Transition to Detail state + render our response
      this.$store.commit('setMapDetail', {
        type: SurveyMapComponent,
        title: 'A Response',
        data: response
      })
      
      this.$store.commit('setMapState', DetailMapState)
    }
  }
}
</script>

<style lang="css">
</style>
