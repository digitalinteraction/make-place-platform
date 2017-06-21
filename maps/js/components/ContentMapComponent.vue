<template lang="html">
  <!-- ... -->
</template>

<script>
import Content from './details/Content.vue'

export default {
  props: [ 'options' ],
  mounted() {
    this.$store.commit('addAction', {
      title: this.options.popupTitle,
      icon: 'info',
      onClick: this.actionHandler
    })
  },
  methods: {
    actionHandler() {
      
      // Set the detail
      this.$store.commit('setMapDetail', {
        type: Content,
        title: this.options.popupTitle,
        options: { content: this.options.popupContent }
      })
      
      // Set the map state
      this.$store.commit('setMapState', 'DetailMapState')
    }
  }
}
</script>

<style lang="css">
</style>
