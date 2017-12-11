
import axios from 'axios'

/**
 * The listening of something for responses to a survey
 */
class ResponseListener {
  
  /** Creates a new listener with an arg object */
  constructor(arg) {
    this.fetched = arg.fetched || (() => {})
    this.redraw = arg.redraw || (() => {})
    this.created = arg.created || (() => {})
    this.calls = 0
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
  }
  
  /** Adds a listener to the state */
  addListener(fields, listener) {
    this.fields = this.fields.concat(fields)
    this.listeners.push(new ResponseListener(listener))
  }
  
  /** Resolves the state, updating the listeners */
  resolve(responses) {
    this.listeners.forEach(l => {
      l.calls++ ? l.redraw(responses) : l.fetched(responses)
    })
    this.state = 'resolved'
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
  }
  
  /** Start the listener, ticking every x milliseconds */
  start(interval = 100) {
    setInterval(() => this.tick(), interval)
  }
  
  /** Register a listener for a survey, using its id */
  request(surveyId, fields, listener) {
    
    // If we aren't already listening, create a state
    if (!this.requests.has(surveyId)) {
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
    let request = this.requests.get(response.surveyId)
    
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
  
  
  
  
  /** Tick the listener, will lock when processing to avoid repeated processing */
  async tick() {
    
    // Get the pending requests (Map only has a .forEach method)
    let pending = []
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
      let params = {}
      if (request.fields.length > 0) params['pluck'] = request.fields.join(',')
      
      // Perform the request
      let res = await axios.get(`/api/survey/${request.surveyId}/responses`, { params })
      
      // Resolve the request
      request.resolve(res.data)
    }
    catch (error) {
      
      // Mark as failed is something went wrong
      request.failed()
    }
  }
    
  
}

export default new ResponsesService()
