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
    
    this.heatLayer = L.heatLayer([], this.heatOpts)
    this.$store.state.map.addLayer(this.heatLayer)
    
    responsesService.request(this.options.surveyID, questions, {
      resolve: (responses) => {
        let heatPoints = this.pointsFromResponses(responses, posQ, weightQ, defaultWeight)
        this.heatLayer.setLatLngs(heatPoints)
      },
      created: (response) => {
        this.latLngsFromResponse(response, posQ, weightQ, defaultWeight)
          .forEach(p => this.heatLayer.addLatLng(p))
      }
    })
  },
  methods: {
    latLngsFromResponse(response, posQ, weightQ, defaultWeight) {
      let answer = response.values[posQ].value
      if (answer === null) return []
      
      let weight = weightQ ? response.values[weightQ].value || defaultWeight : defaultWeight
      
      if (answer.type === 'POINT') {
        return [ new L.LatLng(answer.geom.x, answer.geom.y, weight) ]
      }
      else if (answer.type === 'LINESTRING') {
        return answer.geom.map(geom => new L.LatLng(geom.x, geom.y, weight))
      }
      return []
    },
    pointsFromResponses(responses, posQ, weightQ, defaultWeight) {
      let points = []
      responses.forEach(r => {
        points = points.concat(this.latLngsFromResponse(r, posQ, weightQ, defaultWeight))
      })
      return points
    }
  }
}
</script>

<style lang="css">
</style>
