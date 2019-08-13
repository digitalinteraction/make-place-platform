<template lang="html">
  <map-modal
    v-if="mapComponent"
    :title="mapComponent.popupTitle"
    @close="onClose"
  >
    <div v-html="mapComponent.popupContent"></div>
  </map-modal>
</template>

<script>
import MapModal from '../components/MapModal'

export default {
  components: { MapModal },
  computed: {
    mapComponent() {
      return this.$store.getters.mapComponent(parseInt(this.$route.params.id))
    }
  },
  mounted() {
    if (!this.mapComponent) this.$router.replace('/')
  },
  methods: {
    onClose() {
      this.$router.push('/')
    }
  }
}
</script>

<style lang="scss" scoped>
</style>
