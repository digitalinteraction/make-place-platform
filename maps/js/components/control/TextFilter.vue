<template lang="html">
  <div class="control text-filter">
    <h5 v-text="options.label"></h5>
    <pre v-html="options"></pre>
  </div>
</template>

<script>
export default {
  props: [ 'options' ]
}
</script>

<style lang="scss">
</style>
