<template lang="html">
  <div id="map-app" :class="{'is-mobile': isMobile}">
    
    <p v-if="!page"> Loading ... </p>
    
    <div v-else>
      
      <!-- Add components -->
      <component v-for="(c,i) in components" :is="c.type" :key="i" :config="c"></component>
      
      
      <!-- The map's state component -->
      <div v-if="$store.state.mapState" class="map-state">
        <component :is="$store.state.mapState" :is-mobile="isMobile"></component>
      </div>
      
    </div>
    
    <div id="map-base"></div>
    
  </div>
</template>



<script>
import axios from 'axios'
import SurveyMapComponent from './components/SurveyMapComponent.vue'
import DefaultState from './state/DefaultMapState.vue'

import L from 'leaflet'
import LG from './libs/leaflet-google'
import LC from './libs/leaflet-markercluster.min'

let componentMap = {
  SurveyMapComponent
}

export default {
  data() {
    return {
      isMobile: false,
      page: null,
      componentConfig: null
    }
  },
  components: { SurveyMapComponent },
  mounted() {
    this.loadConfig()
    this.$store.commit('setMapState', DefaultState)
    
    
    
    
    
  },
  methods: {
    async loadConfig() {
      try {
        
        // Fetch config
        let res = await axios.get(`${this.$config.url}/mapConfig`)
        let config = res.data
        this.page = config.page
        this.components = config.components
        
        
        // Create our map
        let map = L.map('map-base', {
          attributionControl: config.page.tileset !== 'Google'
        })
        
        
        // Configure the map
        map.zoomControl.setPosition('bottomright')
        map.setView([
          config.page.startLat, config.page.startLng
        ], config.page.startZoom)
        
        
        // Add tiles based on the config
        if (this.page.tileset === 'Google') {
          L.gridLayer.googleMutant({type: 'roadmap'}).addTo(map)
        }
        else {
          
          let url = 'http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png'
          map.addLayer(new L.TileLayer(url))
        }
      
      
        console.log(res.data)
      }
      catch (e) {
        console.log(e)
      }
    }
  }
}
</script>



<style lang="css">
</style>
