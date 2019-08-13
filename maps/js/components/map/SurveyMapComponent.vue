<script>

import L from 'leaflet'
import responsesService from '../../services/responses'

// type SurveyMapComponent = {
//   actionColour: Color
//   actionMessage: string
//   canMakeComments: boolean
//   canMakeVotes: boolean
//   canSubmit: boolean
//   canView: boolean
//   canViewComments: boolean
//   canViewVotes: boolean
//   className: "SurveyMapComponent"
//   commentAction: string
//   commentPlaceholder: string
//   commentTitle: string
//   commentingEnabled: 0
//   created: "2019-02-13 14:41:26"
//   highlightQuestion: number | null
//   id: number
//   name: string
//   order: number
//   pageID: number
//   permissions: {
//     comments: PermissionObject,
//     response: PermissionObject,
//     voting: PermissionObject,
//   },
//   pinColour: Color
//   positionQuestion: string | null
//   renderResponses: number (boolean)
//   responseMinimizable: number (boolean)
//   responseShareable: number (boolean)
//   responseTitle: string
//   surveyID: number
//   type: "SurveyMapComponent"
//   voteTitle: string
//   voteType: "BASIC" | "ARROW" | "EMOJI"
//   votingEnabled: number (boolean)
// }
// 
// type Color = "primary" | "secondary" | "blue" | "red" | ...
// 
// type PermissionObject = {
//   make: "NoOne" | "Anyone" | "Member",
//   view: "NoOne" | "Anyone" | "Member"
// }

export default {
  props: ['options'],
  data() {
    return {
      markers: []
    }
  },
  mounted() {
    // Add an action to respond to the survey
    if (this.options.canSubmit) {
      this.$store.commit('addAction', {
        title: this.options.actionMessage,
        colour: this.options.actionColour,
        icon: 'plus',
        onClick: this.actionHandler
      })
    }
    
    // Get the key name for the position-ing question
    const positionKey = this.options.positionQuestion
    
    // If not set (from the CMS) do nothing
    if (!this.options.canView || !positionKey || !this.options.renderResponses) return
    
    // Register to get responses
    responsesService.request(this.options.surveyID, [positionKey], {
      resolve: (responses) => {
        this.markers.forEach(m => this.$store.state.clusterer.removeLayer(m))
        this.markers = []
        responses.forEach(r => this.addResponsePin(r, positionKey))
      },
      created: (response) => {
        this.addResponsePin(response, positionKey)
      }
    })
    
  },
  computed: {
    surveyApi() {
      return `${this.$config.api}/api/survey/${this.options.surveyID}`
    }
  },
  render: h => '',
  methods: {
    makeIcon(colour) {
      return L.icon({
        iconUrl: `/static/img/pins/pin-${colour}.svg`,
        iconSize: [30, 56],
        iconAnchor: [15, 40]
      })
    },
    actionHandler() {
      this.$router.push({
        name: 'pick',
        query: {
          mode: 'survey',
          component: this.options.id
        }
      })
    },
    addResponsePin(response, posKey) {
      
      // Get the responses's position
      const pos = response.values[posKey].value
      
      // Skip if the response didn't answer that question
      if (!pos || !pos.geom || !pos.geom.x || !pos.geom.y) { return }
      
      // Create a marker for the pin
      const marker = L.marker([pos.geom.x, pos.geom.y], {
        icon: this.makeIcon(this.options.pinColour || 'primary')
      })
      
      // Listen for clicks on the pin
      marker.on('click', e => this.responseClicked(response, e))
      
      // Add the marker to the map
      this.markers.push(marker)
      this.$store.state.clusterer.addLayer(marker)
    },
    responseClicked(response, e) {
      // Push the response view
      this.$router.push({
        name: 'survey-response',
        params: {
          componentID: this.options.id,
          responseID: response.id
        }
      })
    }
  }
}
</script>

<style lang="css">
</style>
