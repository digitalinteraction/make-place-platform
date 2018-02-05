<template lang="html">
  <div id="map-app" :class="{'is-mobile': isMobile}">
    
    <transition name="response-loader">
      <div v-if="!page || fetchingResponses" class="loading-data">
        <loading> Loading Map </loading>
      </div>
    </transition>
    
    <!-- <loading v-if="!page"> Loading Map </loading> -->
    
    <div v-if="page">
      
      <!-- Add map components -->
      <component v-for="(c,i) in components" :is="c.type" :key="i" :options="c"></component>
      
      
      <!-- The map's state component -->
      <component v-if="currentState"
        :is="currentState.type"
        :is-mobile="isMobile"
        :options="currentState.options">
      </component>
      
    </div>
    
    <div id="map-base"></div>
    
  </div>
</template>



<script>
import axios from 'axios'
import SurveyMapComponent from './components/SurveyMapComponent'
import ContentMapComponent from './components/ContentMapComponent'
import HeatMapComponent from './components/HeatMapComponent'
import TemporalFilterMapComponent from './components/TemporalFilterMapComponent'

import DefaultMapState from './state/DefaultMapState'
import DetailMapState from './state/DetailMapState'
import PickingMapState from './state/PickingMapState'

import responsesService from './services/responses'

import L from 'leaflet'
import 'leaflet.heat'
import 'leaflet.gridlayer.googlemutant'
import 'leaflet.markercluster'


export default {
  components: {
    SurveyMapComponent,
    ContentMapComponent,
    HeatMapComponent,
    TemporalFilterMapComponent,
    DefaultMapState,
    DetailMapState,
    PickingMapState
  },
  data() {
    return {
      isMobile: false,
      page: null,
      componentConfig: null,
      components: [],
      fetchingResponses: false
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
    
    responsesService.start()
    responsesService.listenForWork((eventName) => {
      this.fetchingResponses = eventName === 'start'
    })
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
      }
      catch (e) { console.log(e) }
    }
  }
}
</script>



<style lang="scss">

</style>
