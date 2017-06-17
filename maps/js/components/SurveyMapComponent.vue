<template lang="html">
  <!-- <p> Survey Map Component: {{config.actionMessage}} </p> -->
</template>

<script>
export default {
  props: [ 'config' ],
  mounted() {
    
    this.$store.commit('addAction', {
      title: this.config.actionMessage,
      colour: this.config.actionColour,
      onClick: this.actionHandler
    })
    
  },
  methods: {
    actionHandler() {
      console.log('Ouch!')
    }
  }
}
</script>

<style lang="css">
</style>
