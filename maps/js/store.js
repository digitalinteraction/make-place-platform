
export default {
  state: {
    actions: [],
    mapState: null
  },
  mutations: {
    addAction(state, newAction) { state.actions.push(newAction) },
    setMapState(state, newState) { state.mapState = newState }
  }
}
