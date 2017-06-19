<template lang="html">
  <div class="detail-map-state">
    
    <!-- The detail popover -->
    <transition name="fade-up" appear>
      <div class="map-detail" :class="{minified}">
        
        <!-- The title bar -->
        <div class="header-bar">
          
          <!-- Title text -->
          <h2 class="title"> {{title}} </h2>
          
          <!-- The detail buttons -->
          <div class="buttons">
            <span class="close-button" @click="close" title="Close"></span>
            <span class="minify-button" @click="minify" title="Minify"></span>
          </div>
          
        </div>
        
        <!-- If set, render the detail component -->
        <transition name="fade">
          <div v-if="detail" class="content">
            <component :is="detail.type" :data="detail.data" @change-title="changeTitle"></component>
          </div>
        </transition>
        
      </div>
    </transition>
    
    <!-- Show an overlay when not minified -->
    <transition name="fade" appear>
      <div v-if="!minified" class="full-overlay"></div>
    </transition>
    
  </div>
</template>



<script>
import DefaultMapState from './DefaultMapState.vue'

export default {
  data() {
    return { minified: false, customTitle: null }
  },
  computed: {
    detail() { return this.$store.state.mapDetail },
    title() { return this.customTitle || this.detail.title || 'Loading' }
  },
  methods: {
    close() { this.$store.commit('setMapState', DefaultMapState) },
    minify() { this.minified = !this.minified },
    changeTitle(newTitle) { this.customTitle = newTitle }
  }
}
</script>



<style lang="css">
</style>
