
import axios from 'axios'

/**
 * The listening of something for responses to a survey
 */
class ResponseListener {
  
  /** Creates a new listener with an arg object */
  constructor(arg) {
    const nop = () => {}
    this.resolve = arg.resolve || nop
    this.created = arg.created || nop
  }
}

/**
 * The state of different listenings to surveyresponses
 */
class RequestState {
  
  /** Creates a new state */
  constructor(surveyId) {
    this.surveyId = surveyId
    this.fields = []
    this.listeners = []
    this.state = 'pending'
    this.responses = []
  }
  
  /** Adds a listener to the state */
  addListener(fields, listener) {
    this.fields = this.fields.concat(fields)
    this.listeners.push(new ResponseListener(listener))
  }
  
  /** Resolves the state, updating the listeners */
  resolve(responses, filters) {
    this.responses = responses
    this.invalidate(filters)
    this.state = 'resolved'
  }
  
  /** Refilters the responses and resolves the listeners again */
  invalidate(filters) {
    const filtered = this.responses.filter(response => {
      return filters.every(f => f(response))
    })
    this.listeners.forEach(l => l.resolve(filtered))
  }
  
  /** Ends the state, marking it as failed */
  failed() {
    this.state = 'failed'
  }
}

/**
 * A global object for fetching / listening for survey responses
 */
class ResponsesService {
  
  /** Creates a new service, ready to make requests */
  constructor() {
    this.requests = new Map()
    this.inProgress = false
    this.listeners = []
    this.filters = []
  }
  
  /** Start the listener, ticking every x milliseconds */
  start(interval = 100) {
    setInterval(() => this.tick(), interval)
  }
  
  /** Register a listener for a survey, using its id */
  request(surveyId, fields, listener) {
    
    // If we aren't already listening, create a state
    if (!this.isRequested(surveyId)) {
      this.requests.set(surveyId, new RequestState(surveyId))
    }
    
    // Add the listener to the state
    this.requests.get(surveyId).addListener(fields, listener)
  }
  
  /** Notifies listeners of a newly created response (assumes all fields are set) */
  responseCreated(response) {
    
    // Do nothing if if there are no states
    if (!this.requests.has(response.surveyId)) return
    
    // Get the state
    const request = this.requests.get(response.surveyId)
    
    // Notify each listener
    request.listeners.forEach(l => {
      if (l.created) l.created(response)
    })
  }
  
  
  
  /** [Meta Layer] Listen for changes in the service (start/end) */
  listenForWork(listener) {
    this.listeners.push(listener)
  }
  
  /** [Internal] Notify the listeners with some arguements */
  notifyListeners(...args) {
    this.listeners.forEach(l => l(...args))
  }
  
  /** Register a filter  */
  registerFilter(id, filter) {
    this.filters[id] = filter
  }
  
  /** Cause responses to be refiltered and resolved again */
  invalidate() {
    const filters = Object.values(this.filters)
    this.requests.forEach(request => request.invalidate(filters))
  }
  
  /** If a survey has been requested */
  isRequested(surveyId) {
    return this.requests.has(surveyId)
  }
  
  
  
  
  /** Tick the listener, will lock when processing to avoid repeated processing */
  async tick() {
    
    // Get the pending requests (Map only has a .forEach method)
    const pending = []
    this.requests.forEach(request => {
      if (request.state === 'pending') pending.push(request)
    })
    
    // If already in progress or there are no requests, do nothing
    if (this.inProgress || pending.length === 0) return
    this.inProgress = true
    
    // Notify meta listeners that we started
    this.notifyListeners('start')
    
    // Perform the requests in parallel
    await Promise.all(pending.map(request => this.fetch(request)))
    
    // Notify meta listeners that we ended
    this.notifyListeners('end')
    
    // Release the lock
    this.inProgress = false
  }
  
  
  /** Perform a request for some survey responses */
  async fetch(request) {
    
    // Perform the request
    try {
      
      // Construct the pluck param (if needed)
      const params = {}
      if (request.fields.length > 0) params['pluck'] = request.fields.join(',')
      
      // Perform the request
      const res = await axios.get(`/api/survey/${request.surveyId}/responses`, { params })
      
      // Add dates to each response
      res.data.forEach(r => {
        r.created = new Date(r.created)
      })
      
      // Resolve the request
      request.resolve(res.data, Object.values(this.filters))
    }
    catch (error) {
      
      // Mark as failed is something went wrong
      request.failed()
    }
  }
    
  
}

export default new ResponsesService()
