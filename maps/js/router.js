import Vue from 'vue'
import Router from 'vue-router'

import MapContent from './views/MapContentView'
import MapDefault from './views/MapDefaultView'
import MapPicker from './views/MapPickerView'
import MapSurveyForm from './views/MapSurveyFormView'
import MapSurveyResponse from './views/MapSurveyResponseView'

Vue.use(Router)

export default new Router({
  routes: [
    {
      path: '/pick',
      name: 'pick',
      component: MapPicker
    },
    {
      path: '/content/:id',
      name: 'content',
      component: MapContent
    },
    {
      path: '/survey/:id',
      name: 'survey-form',
      component: MapSurveyForm
    },
    {
      path: '/view/:componentID/:responseID',
      name: 'survey-response',
      component: MapSurveyResponse
    },
    {
      path: '*',
      name: 'default',
      component: MapDefault
    }
  ]
})
