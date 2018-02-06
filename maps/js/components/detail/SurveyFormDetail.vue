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
  activated() {
    this.fetchForm()
  },
  deactivated() {
    this.renderedForm = null
    this.submitting = false
  },
  methods: {
    async fetchForm() {
      
      // Fetch the survey form
      let res = await axios.get(`${this.surveyApi}/view`)
      
      
      // Take the form bit out of the form
      let content = res.data.content.replace(/(<form.*>|<\/form>)/g, '')
      
      // Change our title
      this.$emit('change-title', res.data.title)
      
      // Render the form
      this.renderedForm = content
    },
    async submitSurvey(e) {
      
      // Get the data from the form
      let data = new FormData(e.target)
      
      // Append the position onto the form data
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
