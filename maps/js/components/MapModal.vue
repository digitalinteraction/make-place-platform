<template lang="html">
  <div class="map-modal">
    
    <!-- fade-upwards on first appear -->
    <transition name="fade-up" appear>
      <div class="map-modal-box" :class="{minified}">
        <div class="inner">
          
          <div class="header-bar">
            <h2 class="title"> {{title}} </h2>
            <div class="buttons">
              <button
                class="close-button"
                @click="onClose"
                title="Close">
              </button>
              <button
                class="minify-button"
                @click="onMinify"
                title="Minify">
              </button>
            </div>
          </div>
          
          <div class="content">
            <slot></slot>
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

// 
// A component to render a modal overlay, putting the <slot> inside the modal
// 
export default {
  props: {
    title: { type: String, required: true }
  },
  data() {
    return {
      minified: false
    }
  },
  methods: {
    onClose() {
      this.$emit('close')
    },
    onMinify() {
      this.minified = !this.minified
    }
  }
}
</script>

<style lang="scss">
@import 'maps/sass/maps-common.scss';

.map-modal-box {
  position: absolute;
  top: 0; left: 0; right: 0;
  margin: 48px auto 0px;
  width: 620px;
  max-width: calc(100%);
  max-height: calc(100% - 64px);
  z-index: $zDetail;
  background: $cardBackground;
  border-radius: 2px;
  padding: 12px;
  border: none;
  overflow-y: auto;
  -webkit-overflow-scrolling: touch;
  background: white;
  transition: all 0.3s;
  @include only-small {
    left: 0;
    margin: 12px 0 0;
    width: inherit;
    max-height: calc(100% - 12px);
    border-top-left-radius: 15px;
    border-top-right-radius: 15px;
  }
  .inner {
    position: relative;
  }
  .content {
    transform: scale(1);
    transform-origin: top;
    opacity: 1;
    transition: transform 0.3s, opacity 0.3s;
    overflow: hidden;
  }
  &.minified {
    margin-top: 0;
    max-height: 64px;
    overflow: hidden;
    border-radius: 2px 2px 10px 10px;
    box-shadow: 0 0 2px rgba(0,0,0,0.4);
    .content {
      opacity: 0;
      transform: translateY(-50px);
    }
    .header-bar {
      margin-bottom: 0px;
    }
  }
  .header-bar {
    @include primary-bottom;
    position: relative;
    z-index: 1;
    background: white;
    .title {
      margin: 0;
      margin-right: 80px;
    }
    .buttons {
      position: absolute;
      top: 0;
      right: 0;
      .close-button, .minify-button {
        width: 26px;
        height: 26px;
        background-size: contain;
        background-repeat: no-repeat;
        float: right;
        transform: scale(1);
        transition: transform 0.3s;
        border: none;
        &:hover { transform: scale(1.07); cursor: pointer; }
      }
      .close-button {
        background-image: url(/static/img/buttons/close.svg);
      }
      .minify-button {
        background-image: url(/static/img/buttons/minify.svg);
        margin-right: 14px;
      }
    }
  }
  
}
</style>
