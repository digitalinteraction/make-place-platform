<template lang="html">
  <option-set class="control survey-filter"
    :label="options.label"
    v-model="chosenSurveys"
    :options="surveys"
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
    return { chosenSurveys: [] }
  },
  computed: {
    surveys() {
      return Object.keys(this.options.surveys)
        .map(name => ({
          key: name,
          value: this.options.surveys[name] }))
        .filter(option =>
          responseService.isRequested(option.value)
        )
    }
  },
  mounted() {
    responseService.registerFilter(this.options.id, response => {
      if (this.chosenSurveys.length === 0) return true
      return this.chosenSurveys.includes(response.surveyId)
    })
  },
  methods: {
    updateFilter() {
      responseService.invalidate()
    }
  }
}
</script>

<style lang="scss">
</style>
