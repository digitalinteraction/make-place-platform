<template lang="html">
  <span @click="onClick" class="map-action with-icon animated" :class="colour">
    
    <!-- Our icon -->
    <span class="icon" :style="{'background-image': `url(${icon})`}"></span>
    
    <!-- Our title -->
    {{title}}
    
  </span>
</template>



<script>
export default {
  props: [ 'action' ],
  computed: {
    title() { return this.action.title || '??' },
    icon() { return `/static/img/icons/${this.action.icon || 'info'}.svg` },
    colour() { return this.action.colour || 'primary' }
  },
  methods: {
    onClick(e) {
      this.$emit('chosen')
      if (this.action.onClick) this.action.onClick(e)
    }
  }
}
</script>



<style lang="scss">
.map-action {
  @include button-style;
}
</style>
