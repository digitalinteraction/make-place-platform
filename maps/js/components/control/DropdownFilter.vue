<template lang="html">
  <option-set class="control dropdown-filter"
    :label="options.label"
    v-model="chosenOptions"
    :options="options.question.options"
    @input="updateFilter">
  </option-set>
</template>

<script>
import OptionSet from '../OptionSet'
import responseService from '../../services/responses'

export default {
  components: { OptionSet },
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
      let value = response.values[this.options.question.handle].value
      return this.chosenOptions.includes(value)
    }
  }
}
</script>

<style lang="scss">
</style>
