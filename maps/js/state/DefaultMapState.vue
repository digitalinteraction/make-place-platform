<template lang="html">
  <div class="default-map-state">
    
    <div v-if="hasControls" class="controls-wrapper" :class="{ active: showControls }">
      <div class="inner">
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
        <div class="group-list">
          <div v-for="(group, name) in $store.state.controls" class="group">
            <h5 v-text="name"></h5>
            <component v-for="(control, i) in group"
              :is="control.type"
              :options="control.options"
              :key="i">
            </component>
          </div>
        </div>
      </div>
    </div>
    
    <div v-if="showActions" class="action-list">
      <span v-for="(a,i) in actions" class="action-holder">
        <map-action :key="i" :action="a" @chosen="actionChosen"></map-action> <br>
      </span>
    </div>
    
    
    <div v-if="isMobile && actions.length > 0 && !this.showControls">
      <span class="actions-toggle action"
        :class="[{'toggled': actionsToggled}, toggleActionsClass]"
        @click="toggleActions">
        
        <span v-if="actionsToggled">
          <i class="fa fa-times" aria-hidden="true"></i>
        </span>
        <span v-else>
          <i class="fa fa-map-marker" aria-hidden="true"></i>
        </span>
        
      </span>
    </div>
    
    <transition name="fade">
      <div v-if="isMobile && (actionsToggled || showControls)" class="full-overlay"></div>
    </transition>
    
    
  </div>
</template>



<script>
import MapAction from '../components/MapAction.vue'

import TemporalFilter from '../components/control/TemporalFilter.vue'

export default {
  props: [ 'isMobile' ],
  components: { MapAction, TemporalFilter },
  data() {
    return {
      actionsToggled: false,
      showControls: false
    }
  },
  computed: {
    actions() { return this.$store.state.actions },
    showActions() {
      if (this.isMobile) return this.actionsToggled
      return true
    },
    hasControls() {
      return Object.keys(this.$store.state.controls).length > 0
    },
    toggleActionsClass() { return this.actionsToggled ? 'red' : 'primary' },
    toggleActionsTitle() { return this.actionsToggled ? 'Cancel' : '...' }
  },
  methods: {
    toggleActions() {
      this.actionsToggled = !this.actionsToggled
    },
    toggleControls() {
      this.showControls = !this.showControls
    },
    actionChosen() {
      this.actionsToggled = false
      // console.log('picked')
    }
  }
}
</script>



<style lang="css">
</style>
