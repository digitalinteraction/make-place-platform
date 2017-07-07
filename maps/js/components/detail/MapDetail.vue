<template lang="html">
  <component :is="type">
  </component>
</template>

<script>
export default {
  props: [ 'detail' ]
}
</script>

<style lang="css">
</style>
