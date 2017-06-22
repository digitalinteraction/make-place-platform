<template lang="html">
  <div class="survey-form" style="margin: 24px 0">
    <loading v-if="!renderedForm"> Fetching Survey </loading>
    <div v-else>
      <loading v-if="submitting" type="overlay"> Sending Response </loading>
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
    return {
      renderedForm: null,
      submitting: false
    }
  },
  computed: {
    surveyApi() {
      return `${this.$config.api}/api/survey/${this.options.component.surveyID}`
    }
  },
  mounted() {
    this.fetchForm()
    console.log(this.options)
  },
  methods: {
    async fetchForm() {
      
      let res = await axios.get(`${this.surveyApi}/view`)
      
      let other = await axios.get(this.surveyApi)
      
      let content = res.data.content
      
      // Take the form bit out of the form
      content = content.replace(/(<form.*>|<\/form>)/g, '')
      
      
      this.$emit('change-title', res.data.title)
      this.renderedForm = content
    },
    async submitSurvey(e) {
      
      
      // Maybe we'll just have to dynamically render the form w/ vue!?
      let data = new FormData(e.target)
      // let body = { fields: {} }
      //
      //
      // // Get the values our of the form
      // // Nests the 'field' values in an object rather than php array style
      // for (let [key, value] of data.entries()) {
      //   if (key.match(/^fields\[/)) {
      //     body.fields[key.replace(/fields|\[|\]/g, '')] = value
      //   }
      //   else {
      //     body[key] = value
      //   }
      // }
      
      // // Add the position to the fields
      // body.fields[this.options.component.positionQuestion] = {
      //   x: this.options.position.lat,
      //   y: this.options.position.lng
      // }
      
      let posField = this.options.component.positionQuestion
      data.append(`fields[${posField}][x]`, this.options.position.lat)
      data.append(`fields[${posField}][y]`, this.options.position.lng)
      
      
      
      try {
        
        // Add the overlay
        this.submitting = true
        
        // Submit the response
        let res = await axios.post(`${this.surveyApi}/submit`, data)
        
        // remove the overlay
        this.submitting = false
        
        // Emit the new response
        if (this.options.onCreate) {
          this.options.onCreate(res.data)
        }
      }
      catch (error) {
        console.log(error)
      }
      
    }
  }
}
</script>



<style lang="css">
</style>
