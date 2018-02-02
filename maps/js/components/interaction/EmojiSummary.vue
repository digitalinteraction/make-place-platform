<template lang="html">
  <span class="emoji-summary">
    <img :src="emoji.icon" :class="classes">
    <span class="count">{{count}}</span>
  </span>
</template>



<script>
export default {
  props: [ 'emoji', 'count' ],
  computed: {
    classes() {
      return {
        masked: this.emoji.masked,
        'primary-background': this.emoji.masked
      }
    }
  }
}
</script>



<style lang="css">
</style>
