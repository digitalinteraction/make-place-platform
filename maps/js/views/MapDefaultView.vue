<template lang="html">
  <div class="map-default-view">
    
    <!-- Show the map controls (if there are any to show) -->
    <div v-show="hasControls" class="controls-wrapper" :class="{ active: showControls }">
      <div class="inner">
        <!-- A nice header for the section, with a toggle button to show/hide -->
        <div class="header">
          <h3>
            Controls
            <button @click="toggleControls">
              <transition name="fade">
                <i v-if="showControls" class="fa fa-long-arrow-left"></i>
                <i v-else class="fa fa-cog"></i>
              </transition>
            </button>
          </h3>
        </div>
        
        <!-- Render each control, pulling from the Vue store -->
        <keep-alive>
          <div class="controls-list">
            <component v-for="(control, index) in $store.state.controls"
              :is="control.type"
              :options="control.options"
              :key="index">
            </component>
          </div>
        </keep-alive>
      </div>
    </div>
    
    <!-- The map's actions ~ if there are any and they're toggled on (on mobile) -->
    <div v-if="showActions" class="action-list">
      <span v-for="(action, index) in actions" :key="index" class="action-holder">
        <map-action :action="action" @chosen="actionChosen"></map-action>
        <br>
      </span>
    </div>
    
    <!-- Show the mobile actions toggler ~ if on mobile and there are actions to toggle-->
    <div v-if="isMobile && actions.length > 0 && !this.showControls">
      <span class="actions-toggle action"
        :class="[{toggled: showMobileActions}, toggleActionsClass]"
        @click="toggleMobileActions">
        
        <span v-if="showMobileActions">
          <i class="fa fa-times" aria-hidden="true"></i>
        </span>
        <span v-else>
          <i class="fa fa-map-marker" aria-hidden="true"></i>
        </span>
        
      </span>
    </div>
    
    <!-- An overlay for dynamic fade-ins -->
    <transition name="fade">
      <div v-if="shouldShowOverlay" class="full-overlay"></div>
    </transition>
    
  </div>
</template>

<script>
import MapAction from '../components/MapAction.vue'

import TemporalFilter from '../components/control/TemporalFilter'
import TextFilter from '../components/control/TextFilter'
import DropdownFilter from '../components/control/DropdownFilter'
import SurveyFilter from '../components/control/SurveyFilter'

export default {
  components: { MapAction, TemporalFilter, TextFilter, DropdownFilter, SurveyFilter },
  data() {
    return {
      showMobileActions: false,
      showControls: false
    }
  },
  computed: {
    actions() { return this.$store.state.actions },
    isMobile() { return this.$store.state.isMobile },
    showActions() {
      return this.isMobile ? this.showMobileActions : true
    },
    hasControls() {
      return Object.keys(this.$store.state.controls).length > 0
    },
    toggleActionsClass() { return this.showMobileActions ? 'red' : 'primary' },
    toggleActionsTitle() { return this.showMobileActions ? 'Cancel' : '...' },
    shouldShowOverlay() {
      return this.isMobile && (this.showMobileActions || this.showControls)
    }
  },
  methods: {
    toggleMobileActions() {
      this.showMobileActions = !this.showMobileActions
    },
    toggleControls() {
      this.showControls = !this.showControls
    },
    actionChosen() {
      this.showMobileActions = false
    }
  }
}
</script>

<style lang="scss" scoped>
@import 'maps/sass/maps-common.scss';

@keyframes position-action-mobile {
  from { top: 40px; opacity: 0; }
  to   { top: 0; opacity: 1.0; }
}

$controlsDuration: 0.4s;

.map-default-view {
  
  .controls-wrapper {
    position: absolute;
    top: $cardPadding;
    bottom: $cardPadding;
    left: $cardPadding;
    max-width: calc(100% - #{$cardPadding} - #{$cardPadding});
    width: 420px;
    
    .header {
      position: relative;
      
      h3 {
        margin: 0;
        margin-right: 2em;
        line-height: 54px;
        margin-left: 0.5em;
        
        button {
          position: absolute;
          top: 0;
          right: 0;
          padding: 0;
          border: none;
          background: #fff;
          width: 54px;
          background-color: white;
          font-size: 1.3em;
          
          &:focus { outline: none; }
          
          i {
            transition: $controlsDuration transform;
            transform: none;
          }
        }
      }
      
    }
    
    .inner {
      position: relative;
      min-height: 320px;
      background-color: #fff;
      overflow: auto;
      z-index: $zControls;
      max-height: 100%;
      transition: right $controlsDuration, background-color 0.3s 0.1s;
      box-shadow: 0 0 5px rgba(0,0,0,0.1);
      
      right: 0;
      
      
      .controls-list {
        opacity: 1;
        transition: $controlsDuration opacity;
        margin: 0 1em;
        
        h5 {
          margin: 1em 0 0.4em;
        }
      }
    }
    
    &:not(.active) .inner {
      right: calc(100% - 54px + #{$cardPadding});
      background-color: rgba(0,0,0,0);
      box-shadow: none;
      
      .header button {
        box-shadow: 0 0 5px rgba(0,0,0,0.1);
        
        i {
          transform: rotate(-180deg);
        }
      }
      
      .controls-list {
        opacity: 0;
      }
    }
    
    
    
  }
  
  .action-list {
    position: absolute;
    top: $cardPadding;
    right: $cardPadding;
    display: block;
    z-index: $zMainActions;
    text-align: right;
    
    .action-holder {
      position: relative;
      
      @include only-small {
        animation: position-action-mobile 0.3s both;
      }
      
      @for $i from 0 through 5 {
        &:nth-child(#{$i}) { animation-delay: 0.06s * $i; }
      }
    }
    
    .action {
      @include button-style(true);
      font-size: 16px;
    }
    
    @include only-small {
      top: inherit;
      bottom: 96px;
      left: 12px;
      right: 12px;
      text-align: center;
    }
  }
  
  .actions-toggle {
    @include button-style(false);
    font-size: 20px;
    
    i { transform: scale(1.5); }
    
    position: absolute;
    bottom: 32px;
    
    margin: 0px;
    
    left: 12px;
    transform: translateX(0);
    transition: left 0.3s, transform 0.3s;
    
    z-index: $zMobileActionToggle;
    
    &.toggled {
      left: 50%;
      transform: translateX(-50%);
    }
  }
  
}
</style>
