import Vue from 'vue'
import VueX from 'vuex'

import MapsApp from './MapsApp'
import Store from './store'

import Loading from './components/Loading'
import VoteSection from './components/interaction/VoteSection'
import Emoji from './components/interaction/Emoji'
import EmojiSummary from './components/interaction/EmojiSummary'
import CommentSection from './components/interaction/CommentSection'
import CommentComposer from './components/interaction/CommentComposer'
import Comment from './components/interaction/Comment'
import ProfileImage from './components/interaction/ProfileImage'

import 'leaflet/dist/leaflet.css'
import 'leaflet.markercluster/dist/MarkerCluster.Default.css'

import '../sass/maps.scss'


// Setup Vue plugins
Vue.use(VueX)


// Add config to all components + make sure url has no trailing slash
let url = window.location.origin + window.location.pathname.replace(/\/$/, '')
const api = window.location.origin


// If at the root, use /home as that is the true root
if (window.location.pathname === '/') { url += '/home' }


Vue.use({
  install(Vue, options) { Vue.prototype.$config = { url, api } }
})


// Global components
Vue.component('loading', Loading)
Vue.component('vote-section', VoteSection)
Vue.component('emoji', Emoji)
Vue.component('emoji-summary', EmojiSummary)
Vue.component('comment-section', CommentSection)
Vue.component('comment-composer', CommentComposer)
Vue.component('comment', Comment)
Vue.component('profile-image', ProfileImage)


/* eslint-disable no-new */
new Vue({
  el: '#map-app',
  store: Store,
  render: h => h(MapsApp)
})
