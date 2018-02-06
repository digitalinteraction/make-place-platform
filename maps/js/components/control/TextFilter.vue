<template lang="html">
  <div class="control text-filter">
    <h5 v-text="options.label"></h5>
    <input type="text" v-model="query" @change="updateFilter">
  </div>
</template>

<script>
import responseService from '../../services/responses'

export default {
  props: [ 'options' ],
  data() {
    return {
      query: ''
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
      if (this.query === '') return true
      let answer = response.values[this.options.question.handle]
      if (!answer || !answer.value) return false
      return answer.value.toLowerCase().includes(this.query.toLowerCase())
    }
  }
}
</script>

<style lang="scss">
</style>
