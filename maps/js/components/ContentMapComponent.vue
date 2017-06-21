<template lang="html">
  <!-- ... -->
</template>

<script>
export default {
  props: [ 'options' ],
  mounted() {
    this.$store.commit('addAction', {
      title: this.options.popupTitle,
      icon: 'info',
      colour: this.options.actionColour,
      onClick: this.actionHandler
    })
  },
  methods: {
    actionHandler() {
      
      // The detail component to render our content
      let detail = {
        type: 'ContentDetail',
        options: {
          content: this.options.popupContent
        }
      }
      
      // Set the map state, with our detail embedded
      this.$store.commit('setMapState', {
        type: 'DetailMapState',
        options: {
          title: this.options.popupTitle,
          detail: detail
        }
      })
    }
  }
}
</script>

<style lang="css">
</style>
