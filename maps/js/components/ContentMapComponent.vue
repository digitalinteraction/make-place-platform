<template lang="html">
  <!-- ... -->
</template>

<script>
import Content from './details/Content.vue'

export default {
  props: [ 'config' ],
  mounted() {
    this.$store.commit('addAction', {
      title: this.config.popupTitle,
      icon: 'info',
      onClick: this.actionHandler
    })
  },
  methods: {
    actionHandler() {
      
      // Set the detail
      this.$store.commit('setMapDetail', {
        type: Content,
        title: this.config.popupTitle,
        options: { content: this.config.popupContent }
      })
      
      // Set the map state
      this.$store.commit('setMapState', 'DetailMapState')
    }
  }
}
</script>

<style lang="css">
</style>
