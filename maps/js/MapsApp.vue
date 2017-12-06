<template lang="html">
  <div id="map-app" :class="{'is-mobile': isMobile}">
    
    <loading v-if="!page"> Loading Map </loading>
    
    <div v-else>
      
      <!-- Add map components -->
      <component v-for="(c,i) in components" :is="c.type" :key="i" :options="c"></component>
      
      
      <!-- The map's state component -->
      <!-- <keep-alive> -->
      <component v-if="currentState"
        :is="currentState.type"
        :is-mobile="isMobile"
        :options="currentState.options">
      </component>
      <!-- </keep-alive> -->
      
    </div>
    
    <div id="map-base"></div>
    
  </div>
</template>



<script>
import axios from 'axios'
import SurveyMapComponent from './components/SurveyMapComponent'
import ContentMapComponent from './components/ContentMapComponent'
import HeatMapComponent from './components/HeatMapComponent'

import DefaultMapState from './state/DefaultMapState'
import DetailMapState from './state/DetailMapState'
import PickingMapState from './state/PickingMapState'

import L from 'leaflet'
import 'leaflet.heat'
import './libs/leaflet-google'
import './libs/leaflet-markercluster.min'


export default {
  components: {
    SurveyMapComponent,
    ContentMapComponent,
    HeatMapComponent,
    DefaultMapState,
    DetailMapState,
    PickingMapState
  },
  data() {
    return {
      isMobile: false,
      page: null,
      componentConfig: null,
      components: []
    }
  },
  computed: {
    currentState() { return this.$store.state.mapState }
  },
  mounted() {
    this.onResize()
    window.addEventListener('resize', this.onResize)
    
    this.loadConfig()
    this.$store.commit('setMapState', 'DefaultMapState')
  },
  methods: {
    onResize() {
      this.isMobile = window.outerWidth <= 767
    },
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
        
        
        // Setup the clusterer
        let clusterer = L.markerClusterGroup()
        map.addLayer(clusterer)
        
        
        // Store on the state
        this.$store.commit('setMap', map)
        this.$store.commit('setClusterer', clusterer)
      
      
        console.log('config', res.data)
      }
      catch (e) { console.log(e) }
    }
  }
}
</script>



<style lang="css">
</style>
