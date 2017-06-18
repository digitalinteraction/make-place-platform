<template lang="html">
  <span class="emoji"
    @click="chosen"
    :style="{'background-image': `url(/public/images/emoji/${emoji.icon}.svg)`}">
    <span class="tip">
      {{emoji.name}}
    </span>
  </span>
</template>



<script>
export default {
  props: [ 'emoji' ],
  methods: {
    chosen(event) {
      this.$emit('chosen', this.emoji.id, event)
    }
  }
}
</script>



<style lang="css">
</style>
