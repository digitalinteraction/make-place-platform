<template lang="html">
  <p class="loading" :class="type" v-if="show">
    <i class="fa fa-2x fa-spin fa-cog" aria-hidden="true"></i>
    <br>
    <slot></slot>
  </p>
</template>



<script>
export default {
  props: [ 'type' ],
  data() {
    return { show: false }
  },
  mounted() {
    setTimeout(() => {
      this.show = true
    }, 300)
  }
}
</script>



<style lang="css">
</style>
