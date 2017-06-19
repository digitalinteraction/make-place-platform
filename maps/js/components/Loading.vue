<template lang="html">
  <p class="loading" :class="type">
    <i class="fa fa-2x fa-spin fa-cog" aria-hidden="true"></i>
    <br>
    <slot></slot>
  </p>
</template>



<script>
export default {
  props: [ 'type' ]
}
</script>



<style lang="css">
</style>
