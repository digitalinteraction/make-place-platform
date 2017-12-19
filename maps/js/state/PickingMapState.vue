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



<style lang="scss">

@import 'maps/sass/maps-common.scss';

@keyframes position-cancel {
  from { bottom: -40px; }
  to   { bottom: 0; }
}

@keyframes position-message {
  from { top: -40px; opacity: 0; }
  to   { top: 0; opacity: 1.0; }
}

.picking-state {
  .message {
    z-index: $zPickerMessage;
    animation: position-message 0.3s both;
    position: absolute;
    left: 0;
    right: 0;
    background: white;
    max-width: calc(100% - 12px);
    width: 540px;
    margin: 12px auto;
    border-radius: 4px;
    
    @include only-small {
      margin: 6px;
      width: 100%;
    }
    
    p {
      text-align: center;
      font-size: 18px;
      margin: 0.5em;
    }
  }
  
  .cancel-button {
    @include button-style(false);
    
    animation: position-cancel 0.3s both;
    @include only-small { animation-duration: 0.0s; }
    
    position: absolute;
    left: 50%;
    transform: translateX(-50%);
    margin-bottom: 32px;
    z-index: $xPickerCancel;
    bottom: 0;
    
    font-size: 20px;
    
    i { transform: scale(1.5); }
  }
  
  .full-overlay {
    cursor: crosshair;
  }
  
}

</style>
