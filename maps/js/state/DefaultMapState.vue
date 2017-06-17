<template lang="html">
  <div class="default-map-state">
    
    <!-- <p>{{isMobile}}</p> -->
    
    <div class="action-list">
      <span v-for="(a,i) in actions">
        <map-action :key="i" :action="a"></map-action> <br>
      </span>
    </div>
    
    
    
  </div>
</template>



<script>
import MapAction from '../components/MapAction.vue'

export default {
  props: [ 'isMobile' ],
  components: { MapAction },
  computed: {
    actions() { return this.$store.state.actions }
  }
}
</script>



<style lang="css">
</style>
