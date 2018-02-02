<template lang="html">
  <span class="emoji"
    :class="classes"
    @click="chosen"
    :style="{'background-image': `url(${emoji.icon})`}">
    
    <span class="tip">
      {{emoji.name}}
    </span>
    
    <span v-if="current" class="backdrop secondary-border"></span>
    
  </span>
</template>



<script>
export default {
  props: [ 'emoji', 'current', 'masked', 'enabled' ],
  computed: {
    classes() {
      return {
        current: this.current,
        masked: this.emoji.masked,
        'primary-background': this.emoji.masked,
        enabled: this.enabled
      }
    }
  },
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
