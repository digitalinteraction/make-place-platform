<template lang="html">
  <span class="emoji-summary">
    <img :src="emoji.icon" :class="{masked: emoji.masked}">
    <span class="count">{{count}}</span>
  </span>
</template>



<script>
export default {
  props: [ 'emoji', 'count' ]
}
</script>



<style lang="css">
</style>
