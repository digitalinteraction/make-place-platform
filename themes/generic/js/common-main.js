
// Import css & sass so they get extracted to /public/css/theme.css
import 'font-awesome/css/font-awesome.min.css'
import 'bootstrap/dist/css/bootstrap.min.css'

import 'jquery/src/jquery'
import 'bootstrap/dist/js/bootstrap.min'

import './cookies'

let selector = ['h1', 'h2', 'h3', 'h4', 'h5', 'h6']
  .map(h => `.content.navigable-headers ${h}`)

document.querySelectorAll(selector).forEach(elem => {
  if (!elem.id) return
  elem.addEventListener('click', e => {
    window.location.hash = elem.id
  })
})
