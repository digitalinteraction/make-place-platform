<template lang="html">
  <div class="survey-form" style="margin: 24px 0">
    <loading v-if="!renderedForm"> Fetching Survey </loading>
    <div v-else>
      <form @submit.prevent="submitSurvey" class="survey">
        <div v-html="renderedForm"></div>
      </form>
    </div>
  </div>
</template>



<script>
import axios from 'axios'
import Vue from 'vue'

export default {
  props: [ 'options' ],
  data() {
    return { renderedForm: null }
  },
  computed: {
    surveyApi() { return `${this.$config.api}/api/survey/${this.options.surveyID}` }
  },
  mounted() {
    this.fetchForm()
  },
  methods: {
    async fetchForm() {
      
      let res = await axios.get(`${this.surveyApi}/view`)
      
      let other = await axios.get(this.surveyApi)
      
      let content = res.data.content
      
      // Take the form bit out of the form
      content = content.replace(/(<form.*>|<\/form>)/g, '')
      
      console.log(content)
      
      this.$emit('change-title', res.data.title)
      this.renderedForm = content
    },
    submitSurvey(e) {
      
      
      // Maybe we'll just have to dynamically render the form w/ vue!?
      let data = new FormData(e.target)
      let body = {}
      
      for (let [key, value] of data.entries()) {
        body[key] = value
      }
      
      console.log(body)
      
      // let data = []
      //
      // do {
      //   let next =
      // }
      //
      // for (var key in formData.values()) {
      //   console.log(key, formData.get(key))
      // }
      
      // console.log(JSON.stringify(formData.getAll()))
      
      // console.log('submit', e)
    }
  }
}
</script>



<style lang="css">
</style>
