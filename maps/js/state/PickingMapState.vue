<template lang="html">
  <div class="picking-state">
    
    <div class="message">
      <p>Choose a position on the map</p>
    </div>
    
    <span class="cancel-button red" @click.stop.capture="cancel" title="Cancel">
      <i class="fa fa-times" aria-hidden="true"></i>
    </span>
    
    <transition name="fade" appear>
      <div class="full-overlay" @click.stop.capture="picked"></div>
    </transition>
    
  </div>
</template>



<script>
export default {
  props: [ 'options' ],
  methods: {
    cancel() {
      
      // Reset the map state
      this.$store.commit('resetMapState')
      
      // Use our callback with null
      if (this.options.onPick) {
        this.options.onPick(null)
      }
    },
    picked(e) {
      
      // Localise the position from screen to map coords
      let map = this.$store.state.map
      let local = map.mouseEventToLatLng(e)
      
      // Reset back to the base state
      this.$store.commit('resetMapState')
      
      // Use our callback
      if (this.options.onPick) {
        this.options.onPick(local)
      }
    }
  }
}
</script>



<style lang="css">
</style>
