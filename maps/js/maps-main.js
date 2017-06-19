import axios from 'axios'
import Vue from 'vue'
import VueX from 'vuex'

import MapsApp from './MapsApp.vue'
import storeConfig from './store'

import Loading from './components/Loading.vue'
import VoteSection from './components/interaction/VoteSection.vue'
import Emoji from './components/interaction/Emoji.vue'
import EmojiSummary from './components/interaction/EmojiSummary.vue'
import CommentSection from './components/interaction/CommentSection.vue'
import CommentComposer from './components/interaction/CommentComposer.vue'
import Comment from './components/interaction/Comment.vue'
import ProfileImage from './components/interaction/ProfileImage.vue'


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
Vue.component('vote-section', VoteSection)
Vue.component('emoji', Emoji)
Vue.component('emoji-summary', EmojiSummary)
Vue.component('comment-section', CommentSection)
Vue.component('comment-composer', CommentComposer)
Vue.component('comment', Comment)
Vue.component('profile-image', ProfileImage)


let store = new VueX.Store(storeConfig)

let app = new Vue({
  el: '#map-app',
  store: store,
  template: '<MapsApp/>',
  components: { MapsApp }
})
