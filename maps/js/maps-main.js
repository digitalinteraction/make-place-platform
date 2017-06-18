import axios from 'axios'
import Vue from 'vue'
import VueX from 'vuex'

import MapsApp from './MapsApp.vue'
import storeConfig from './store'


// Setup Vue plugins
Vue.use(VueX)


// Add config to all components
let url = window.location.href
let base = window.location.origin
if (url.substr(-1) !== '/') url += '/'
let config = { url, base }

Vue.use({
  install(Vue, options) {
    Vue.prototype.$config = config
  }
})



let store = new VueX.Store(storeConfig)

let app = new Vue({
  el: '#map-app',
  store: store,
  template: '<MapsApp/>',
  components: { MapsApp }
})
