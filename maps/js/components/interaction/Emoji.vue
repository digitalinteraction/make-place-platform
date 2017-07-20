<template lang="html">
  <span class="emoji"
    :class="{current, masked: emoji.masked, enabled}"
    @click="chosen"
    :style="{'background-image': `url(${emoji.icon})`}">
    
    <span class="tip">
      {{emoji.name}}
    </span>
    
    <span v-if="current" class="backdrop"></span>
    
  </span>
</template>



<script>
export default {
  props: [ 'emoji', 'current', 'masked', 'enabled' ],
  methods: {
    chosen(event) {
      if (!this.enabled) return
      this.$emit('chosen', this.emoji.id, event)
    }
  }
}
</script>



<style lang="css">
</style>
