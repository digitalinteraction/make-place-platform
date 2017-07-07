<template lang="html">
  <div class="default-map-state">
    
    <div v-if="showActions" class="action-list">
      <span v-for="(a,i) in actions" class="action-holder">
        <map-action :key="i" :action="a" @chosen="actionChosen"></map-action> <br>
      </span>
    </div>
    
    
    <div v-if="isMobile && actions.length > 0">
      <span class="actions-toggle action"
        :class="[{'toggled': actionsToggled}, toggleClass]"
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
      <div v-if="isMobile && actionsToggled" class="full-overlay"></div>
    </transition>
    
    
  </div>
</template>



<script>
import MapAction from '../components/MapAction.vue'

export default {
  props: [ 'isMobile' ],
  components: { MapAction },
  data() {
    return { actionsToggled: false }
  },
  computed: {
    actions() { return this.$store.state.actions },
    showActions() {
      if (this.isMobile) return this.actionsToggled
      return true
    },
    toggleClass() { return this.actionsToggled ? 'red' : 'primary' },
    toggleTitle() { return this.actionsToggled ? 'Cancel' : '...' }
  },
  methods: {
    toggleActions() {
      this.actionsToggled = !this.actionsToggled
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
