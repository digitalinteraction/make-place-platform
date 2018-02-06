<template lang="html">
  <div class="option-set">
    <h5 v-text="label"></h5>
    <label v-for="option in options">
      <input type="checkbox"
        :value="option.value"
        v-model="chosen"
        @change="$emit('input', chosen)">
      {{option.key}}
    </label>
  </div>
</template>

<script>
export default {
  props: [ 'label', 'value', 'options' ],
  data() {
    return { chosen: [] }
  }
}
</script>

<style lang="scss">
.option-set {
  label {
    margin-right: 0.7em;
    user-select: none;
  }
}
</style>
