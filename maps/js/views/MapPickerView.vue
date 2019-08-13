<template lang="html">
  <div class="map-picker-view">
    <!-- A message to prompt the user to pick a position -->
    <div class="message">
      <p>Choose a position on the map</p>
    </div>
    
    <!-- A button to cancel the picking and go back -->
    <span
      class="cancel-button red"
      @click.stop.capture="onCancel"
      title="Cancel"
    >
      <i class="fa fa-times" />
    </span>
    
    <!-- An overlay to darken the background to focus the user -->
    <transition name="fade" appear>
      <div class="full-overlay" @click.stop.capture="onPick"></div>
    </transition>
  </div>
</template>

<script>
export const allowedModes = ['survey']

export default {
  computed: {
    mode() {
      return this.$route.query.mode
    }
  },
  mounted() {
    if (!allowedModes.includes(this.mode)) return this.$router.replace('/')
  },
  methods: {
    onCancel() {
      this.$router.push({ name: 'default' })
    },
    onPick(e) {
      // Work out the lat & long from the click event
      const { lat, lng } = this.$store.state.map.mouseEventToLatLng(e)
      
      // If in 'survey' mode, navigate to the form
      // > with the lat and lng as query string parameters
      if (this.mode === 'survey') {
        this.$router.push({
          name: 'survey-form',
          params: {
            id: this.$route.query.component
          },
          query: { lat, lng }
        })
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

.map-picker-view {
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
