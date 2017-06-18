
export default {
  state: {
    actions: [],
    mapState: null,
    map: null,
    clusterer: null
  },
  mutations: {
    addAction(state, newAction) { state.actions.push(newAction) },
    setMapState(state, newState) { state.mapState = newState },
    setMap(state, map) { state.map = map },
    setClusterer(state, clusterer) { state.clusterer = clusterer }
  }
}
