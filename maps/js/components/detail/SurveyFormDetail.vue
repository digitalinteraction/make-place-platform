<template lang="html">
  <div class="survey-form" style="margin: 24px 0">
    <loading v-if="!renderedForm"> Fetching Survey </loading>
    <div v-else>
      <loading v-if="state === 'working'" type="overlay"> Sending Response </loading>
      <p v-else-if="state === 'error'">
        Something went wrong, please <a href="#" @click.prevent="reset">try again</a>.
      </p>
      <form v-else @submit.prevent="submitSurvey" class="survey">
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
      state: 'input'
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
    this.state = 'input'
  },
  methods: {
    reset() {
      this.state = 'input'
    },
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
      if (this.state === 'working') return
      this.state = 'working'
      
      // Get the data from the form
      let data = new FormData(e.target)
      
      // Append the position onto the form data
      let posField = this.options.component.positionQuestion
      data.append(`fields[${posField}][x]`, this.options.position.lat)
      data.append(`fields[${posField}][y]`, this.options.position.lng)
      
      
      try {
        
        // Submit the response
        let res = await axios.post(`${this.surveyApi}/submit`, data)
        
        // Emit the new response
        if (this.options.onCreate) {
          this.options.onCreate(res.data)
        }
        
        this.state = 'input'
      }
      catch (error) {
        console.log(error)
        this.state = 'error'
      }
    }
  }
}
</script>



<style lang="css">
</style>
