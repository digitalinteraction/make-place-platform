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
    controls: {}
  },
  mutations: {
    addAction(state, newAction) {
      state.actions.push(newAction)
    },
    addControl(state, control) {
      if (!control.group || !control.type) return
      if (!state.controls[control.group]) Vue.set(state.controls, control.group, [])
      control.options = control.options || {}
      state.controls[control.group].push(control)
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
    }
  }
})
