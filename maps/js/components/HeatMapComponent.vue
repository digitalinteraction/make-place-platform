<template lang="html">
  <!-- <pre> {{options}} </pre> -->
</template>

<script>
import L from 'leaflet'

import EventBus from '../buses/mapBus'

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
    
    let heatPoints = this.options.points.map(heat => {
      console.log(heat.weight)
      return [
        heat.pos.geom.x,
        heat.pos.geom.y,
        heat.weight || this.heatOpts.max / 2
      ]
    })
    
    
    
    this.heatLayer = L.heatLayer(heatPoints, this.heatOpts)
    
    this.$store.state.map.addLayer(this.heatLayer)
    
    EventBus.$on('responseCreated', (response) => {
      let posQ = this.options.positionQuestion
      let weightQ = this.options.weightQuestion
      
      let x = response.values[posQ].value.geom.x
      let y = response.values[posQ].value.geom.y
      
      let weight = this.heatOpts.max / 2
      if (weightQ) {
        weight = response.values[weightQ].value
      }
      
      this.heatLayer.addLatLng(new L.LatLng(x, y, weight))
      
    })
  }
}
</script>

<style lang="css">
</style>
