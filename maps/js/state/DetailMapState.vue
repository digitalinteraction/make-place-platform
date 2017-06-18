<template lang="html">
  <div class="default-map-state">
    <div class="map-detail">
      <div class="header-bar">
        <h1 class="title"> {{data.title}} </h1>
      </div>
      <div class="content">
        <p>Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Curabitur blandit tempus porttitor. Integer posuere erat a ante venenatis dapibus posuere velit aliquet. Donec ullamcorper nulla non metus auctor fringilla.</p>
        <p>Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Curabitur blandit tempus porttitor. Integer posuere erat a ante venenatis dapibus posuere velit aliquet. Donec ullamcorper nulla non metus auctor fringilla.</p>
        <p>Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Curabitur blandit tempus porttitor. Integer posuere erat a ante venenatis dapibus posuere velit aliquet. Donec ullamcorper nulla non metus auctor fringilla.</p>
        <p>Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Curabitur blandit tempus porttitor. Integer posuere erat a ante venenatis dapibus posuere velit aliquet. Donec ullamcorper nulla non metus auctor fringilla.</p>
        <p>Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Curabitur blandit tempus porttitor. Integer posuere erat a ante venenatis dapibus posuere velit aliquet. Donec ullamcorper nulla non metus auctor fringilla.</p>
      </div>
    </div>
    
    <div class="full-overlay"></div>
    
  </div>
</template>



<script>
export default {
  computed: {
    data() { return this.$store.state.mapDetail }
  }
}
</script>



<style lang="css">
</style>
