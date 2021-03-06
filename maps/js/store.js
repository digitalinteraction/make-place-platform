import Vue from 'vue'
import VueX from 'vuex'

Vue.use(VueX)

export default new VueX.Store({
  state: {
    actions: [],
    mapState: null,
    map: null,
    clusterer: null,
    mapDetail: null,
    highlight: null,
    controls: [],
    isMobile: false,
    page: null,
    mapComponents: null
  },
  getters: {
    mapComponent: state => id => state.mapComponents.find(comp => comp.id === id)
  },
  mutations: {
    addAction(state, newAction) {
      state.actions.push(newAction)
    },
    addControl(state, control) {
      if (!control.type) return
      control.options = control.options || {}
      state.controls.push(control)
    },
    
    
    resetMapState(state) {
      state.mapState = { type: 'DefaultMapState', options: {} }
    },
    setMapState(state, mapState) {
      if (typeof mapState === 'string') {
        mapState = { type: mapState }
      }
      mapState.options = mapState.options || {}
      state.mapState = mapState
    },
    
    
    setMap(state, map) {
      state.map = map
    },
    setClusterer(state, clusterer) {
      state.clusterer = clusterer
    },
    
    
    setMapDetail(state, detail) {
      state.mapDetail = detail
    },
    setHighlight(state, highlight) {
      if (state.highlight) {
        state.highlight.remove()
      }
      if (highlight) highlight.addTo(state.map)
      state.highlight = highlight
    },
    
    setIsMobile(state, newIsMobile) {
      state.isMobile = newIsMobile
    },
    
    setPage(state, newPage) {
      state.page = newPage
    },
    setMapComponents(state, newMapComponents) {
      state.mapComponents = newMapComponents
    }
  }
})
