<template lang="html">
  <div class="default-map-state">
    
    <!-- <p>{{isMobile}}</p> -->
    
    <transition name="grow-fade">
      <div v-if="showActions" class="action-list">
        <span v-for="(a,i) in actions">
          <map-action :key="i" :action="a"></map-action> <br>
        </span>
      </div>
    </transition>
    
    
    <div v-if="isMobile">
      <span class="actions-toggle action"
        :class="[{'toggled': actionsToggled}, toggleClass]"
        @click="toggleActions">
        
        <span v-if="actionsToggled">
          <i class="fa fa-times" aria-hidden="true"></i>
        </span>
        <span v-else>
          <!-- <i class="fa fa-ellipsis-h" aria-hidden="true"></i> -->
          <!-- <i class="fa fa-caret-up" aria-hidden="true"></i> -->
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
    }
  }
}
</script>



<style lang="css">
</style>
