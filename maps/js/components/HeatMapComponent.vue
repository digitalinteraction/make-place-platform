<template lang="html">
  <!-- <pre> {{options}} </pre> -->
</template>

<script>
import L from 'leaflet'

import responsesService from '../services/responses'

export default {
  props: [ 'options' ],
  data() {
    return {
      heatLayer: null,
      heatOpts: null
    }
  },
  mounted() {
    
    this.heatOpts = {
      radius: this.options.radius || 25,
      minOpacity: this.options.minOpacity || 25,
      blur: this.options.blur || 15,
      max: this.options.maxIntensity || 1.0
    }
    
    let posQ = this.options.positionQuestion
    let weightQ = this.options.weightQuestion
    let defaultWeight = this.heatOpts.max / 2
    
    
    let questions = []
    if (posQ) questions.push(posQ)
    if (weightQ) questions.push(weightQ)
    
    if (questions.length === 0) return
    
    responsesService.request(this.options.surveyID, questions, {
      fetched: (responses) => {
        let heatPoints = this.pointsFromResponses(responses, posQ, weightQ, defaultWeight)
        this.heatLayer = L.heatLayer(heatPoints, this.heatOpts)
        this.$store.state.map.addLayer(this.heatLayer)
      },
      redraw: (responses) => {
        let heatPoints = this.pointsFromResponses(responses, posQ, weightQ, defaultWeight)
        this.heatLayer.setLatLngs(heatPoints)
      },
      created: (response) => {
        let point = this.latLngFromResponse(response, posQ, weightQ, defaultWeight)
        if (point) {
          this.heatLayer.addLatLng(point)
        }
      }
    })
  },
  methods: {
    latLngFromResponse(response, posQ, weightQ, defaultWeight) {
      let pos = response.values[posQ].value
      if (pos === null) return null
      return new L.LatLng(
        response.values[posQ].value.geom.x,
        response.values[posQ].value.geom.y,
        weightQ ? response.values[weightQ].value || defaultWeight : defaultWeight
      )
    },
    pointsFromResponses(responses, posQ, weightQ, defaultWeight) {
      let points = []
      responses.forEach(r => {
        let point = this.latLngFromResponse(r, posQ, weightQ, defaultWeight)
        if (point) points.push(point)
      })
      return points
    }
  }
}
</script>

<style lang="css">
</style>
