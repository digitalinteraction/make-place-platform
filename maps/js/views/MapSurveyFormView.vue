<template lang="html">
  <map-modal
    v-if="mapComponent"
    :title="mapComponent.responseTitle"
    @close="onClose"
  >
    <div class="map-survey-form-view">
      <!--
        Show a loading screen if the form hasn't been rendered yet
      -->
      <loading v-if="!renderedForm"> Fetching survey </loading>
      <template v-else>
        <!--
          Show a loading screen if the form is submitting
        -->
        <loading v-if="state === 'working'" type="overlay">
          Sending response
        </loading>
        <!--
          Otherwise, show an error if there was one
        -->
        <p v-else-if="state === 'error'" class="error">
          Something went wrong, please <a @click.prevent="onReset">try again</a>.
        </p>
        <!--
          If loaded and there are no errors, render the form for the user to submit
        -->
        <form v-else @submit.prevent="onSubmit" class="survey">
          <div v-html="renderedForm"></div>
        </form>
      </template>
    </div>
  </map-modal>
</template>

<script>
import axios from 'axios'
import MapModal from '../components/MapModal'
import responsesService from '../services/responses'

export default {
  components: { MapModal },
  data() {
    return {
      title: '',
      renderedForm: null,
      state: 'input'
    }
  },
  computed: {
    mapComponent() {
      return this.$store.getters.mapComponent(parseInt(this.$route.params.id))
    },
    surveyID() {
      return this.mapComponent.surveyID
    },
    surveyBaseEndpoint() {
      return `${this.$config.api}/api/survey/${this.surveyID}`
    },
    lat() {
      return this.$route.query.lat
    },
    lng() {
      return this.$route.query.lng
    }
  },
  mounted() {
    // Go home if the url parameters are bad
    if (!this.mapComponent || !this.lat || !this.lng) {
      return this.$router.replace('/')
    }
    // 
    // Fetch the form using the API
    // 
    this.fetchForm()
  },
  methods: {
    onClose() {
      this.$router.replace('/')
    },
    onReset() {
      this.state = 'input'
    },
    async onSubmit(e) {
      // Make sure there wasn't a double click and move to the 'working' state
      if (this.state === 'working') return
      this.state = 'working'
      
      // Create form data to post up
      const data = new FormData(e.target)
      
      // Inject the lat & lng fields
      const posField = this.mapComponent.positionQuestion
      data.append(`fields[${posField}][x]`, this.lat)
      data.append(`fields[${posField}][y]`, this.lng)
      
      try {
        // Perform the http request
        const res = await axios.post(`${this.surveyBaseEndpoint}/submit`, data)
        
        // Emit the response for any listeners
        responsesService.responseCreated(res.data)
        
        // Reset our state
        this.state = 'input'
        
        // Go back home
        this.$router.push('/')
      }
      catch (error) {
        console.log(error)
        this.state = 'error'
      }
    },
    async fetchForm() {
      // Get the form (as html) from the API
      const res = await axios.get(`${this.surveyBaseEndpoint}/view`)
      
      // Store the title
      this.title = res.data.title
      
      // Store the form
      // > Strips the form tag so we can inject our own
      this.renderedForm = res.data.content.replace(/(<form.*>|<\/form>)/g, '')
    }
  }
}
</script>

<style lang="scss" scoped>
</style>
