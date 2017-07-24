<template lang="html">
  <div class="detail-map-state">
    
    <!-- The detail popover -->
    <transition name="fade-up" appear>
      <div class="map-detail" :class="{minified}">
        <div class="inner">
          
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
          
          <!-- Render the detail component -->
          <div class="content" :class="options.detail.type">
            <component :is="options.detail.type"
              :options="options.detail.options"
              @change-title="changeTitle">
            </component>
          </div>
          
        </div>
        
      </div>
    </transition>
    
    <!-- Show an overlay when not minified -->
    <transition name="fade" appear>
      <div v-if="!minified" class="full-overlay"></div>
    </transition>
    
  </div>
</template>



<script>
import _ from 'lodash'
import ContentDetail from '../components/detail/ContentDetail.vue'
import SurveyFormDetail from '../components/detail/SurveyFormDetail.vue'
import SurveyResponseDetail from '../components/detail/SurveyResponseDetail.vue'

export default {
  props: [ 'options' ],
  components: { ContentDetail, SurveyFormDetail, SurveyResponseDetail },
  data() {
    return { minified: false, customTitle: null }
  },
  computed: {
    detail() {
      console.log(this.options.detail)
      return this.options.detail
    },
    title() { return this.customTitle || this.options.title || 'Loading' }
  },
  watch: {
    options() {
      this.minified = false
    }
  },
  methods: {
    close() {
      this.$store.commit('setMapState', 'DefaultMapState')
      if (this.options.onClose) this.options.onClose()
    },
    minify() { this.minified = !this.minified },
    changeTitle(newTitle) { this.customTitle = newTitle }
  }
}
</script>



<style lang="css">
</style>
