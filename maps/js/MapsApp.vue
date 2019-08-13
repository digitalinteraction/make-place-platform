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
      <component
        v-for="(component, index) in mapComponents"
        :is="component.type"
        :key="index"
        :options="component">
      </component>
      
      <!-- Render the router -->
      <router-view></router-view>
    </div>
    
    <!-- An element to render the map -->
    <div id="map-base"></div>
  </div>
</template>



<script>
import axios from 'axios'
import SurveyMapComponent from './components/map/SurveyMapComponent'
import ContentMapComponent from './components/map/ContentMapComponent'
import HeatMapComponent from './components/map/HeatMapComponent'

import TemporalFilterMapComponent from './components/filter/TemporalFilterMapComponent'
import TextFilterMapComponent from './components/filter/TextFilterMapComponent'
import DropdownFilterMapComponent from './components/filter/DropdownFilterMapComponent'
import SurveyFilterMapComponent from './components/filter/SurveyFilterMapComponent'

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
    TextFilterMapComponent,
    DropdownFilterMapComponent,
    SurveyFilterMapComponent
  },
  data() {
    return {
      componentConfig: null,
      fetchingResponses: false
    }
  },
  computed: {
    currentState() { return this.$store.state.mapState },
    isMobile() { return this.$store.state.isMobile },
    page() { return this.$store.state.page },
    mapComponents() { return this.$store.state.mapComponents }
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
      this.$store.commit('setIsMobile', window.outerWidth <= 767)
    },
    async loadConfig() {
      try {
        
        // Fetch config
        const res = await axios.get(`${this.$config.url}/mapConfig`)
        // let config = res.data
        const { page, components } = res.data
        
        this.$store.commit('setPage', page)
        this.$store.commit('setMapComponents', components)
        
        // Create our map
        const map = L.map('map-base', {
          attributionControl: page.tileset !== 'Google'
        })
        
        
        // Configure the map
        map.zoomControl.setPosition('bottomright')
        map.setView([
          page.startLat, page.startLng
        ], page.startZoom)
        
        
        // Add tiles based on the config
        if (page.tileset === 'Google') {
          L.gridLayer.googleMutant({type: 'roadmap'}).addTo(map)
        }
        else {
          
          const url = 'http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png'
          map.addLayer(new L.TileLayer(url))
        }
        
        
        // Setup the clusterer
        const clusterer = L.markerClusterGroup()
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
