<template lang="html">
  <div class="control dropdown-filter">
    <h5 v-text="options.label"></h5>
    <label v-for="option in options.question.options">
      <input type="checkbox" v-model="chosenOptions" :value="option.value" @change="updateFilter">
      {{option.key}}
    </label>
  </div>
</template>

<script>
import responseService from '../../services/responses'

export default {
  props: [ 'options' ],
  data() {
    return {
      chosenOptions: []
    }
  },
  mounted() {
    responseService.registerFilter(this.options.id, response => {
      return this.performFilter(response)
    })
  },
  methods: {
    updateFilter() {
      responseService.invalidate()
    },
    performFilter(response) {
      if (response.surveyId !== this.options.surveyID) return true
      if (this.chosenOptions.length === 0) return true
      let value = response.values[this.options.question.handle]
      return this.chosenOptions.includes(value)
    }
  }
}
</script>

<style lang="scss">
.dropdown-filter {
  label {
    margin-right: 0.5em;
    user-select: none;
  }
}
</style>
