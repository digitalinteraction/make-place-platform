<template lang="html">
  <p class="loading" :class="type" v-if="show">
    <i class="fa fa-2x fa-spin fa-cog" aria-hidden="true"></i>
    <br>
    <span class="text">
      <slot></slot>
    </span>
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



<style lang="scss">

@keyframes overlay-fade {
  from { opacity: 0.0; }
  to   { opacity: 1.0; }
}

.loading {
  text-align: center;
  font-size: 32px;
  margin: 3em;
  
  &.short {
    font-size: 24px;
    margin: 2em;
  }
  
  &.overlay {
    position: absolute;
    left: 0;
    right: 0;
    top: 0;
    bottom: 0;
    background-color: #c8c8c8;
    border-radius: 5px;
    margin: 0;
    padding: 3em;
    z-index: 1700;
    color: white;
    
    animation: overlay-fade 0.3s both;
  }
}

</style>
