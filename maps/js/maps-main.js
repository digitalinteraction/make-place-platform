import axios from 'axios'
import Vue from 'vue'
import VueX from 'vuex'

import MapsApp from './MapsApp.vue'
import storeConfig from './store'

import Loading from './components/LoadingComponent.vue'
import Voting from './components/interaction/VotingComponent.vue'
import Emoji from './components/interaction/EmojiComponent.vue'
import EmojiSummary from './components/interaction/EmojiSummaryComponent.vue'


// Setup Vue plugins
Vue.use(VueX)


// Add config to all components
let url = window.location.href
let api = window.location.origin
if (url.substr(-1) !== '/') url += '/'
let config = { url, api }

Vue.use({
  install(Vue, options) {
    Vue.prototype.$config = config
  }
})


// Global components
Vue.component('loading', Loading)
Vue.component('voting', Voting)
Vue.component('emoji', Emoji)
Vue.component('emoji-summary', EmojiSummary)


let store = new VueX.Store(storeConfig)

let app = new Vue({
  el: '#map-app',
  store: store,
  template: '<MapsApp/>',
  components: { MapsApp }
})
