import * as cookie from 'cookie'
import 'cookieconsent/build/cookieconsent.min.css'
import 'cookieconsent'

const CookieConsent = window.cookieconsent

/* eslint-disable */
;(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
})(window,document,'script','https://www.google-analytics.com/analytics.js','ga');
/* eslint-enable */

const cookieConsentConfig = {
  palette: {
    popup: {
      background: '#3886c9'
    },
    button: {
      background: '#fff',
      text: '#3886c9'
    }
  },
  position: 'bottom-right',
  type: 'opt-in',
  location: true,
  autoAttach: false,
  revokable: true,
  content: {
    allow: 'Allow Cookies',
    dismiss: 'Disable Cookies',
    message: 'We use cookies on this website to analyse traffic, remember your preferences, and optimise your experience.'
  },
  onStatusChange(status, hasChanged) {
    if (status === 'allow') {
      enableAnalytics()
    }
    else {
      clearCookies()
    }
  }
}

const cookiePopup = new CookieConsent.Popup(cookieConsentConfig)
document.body.appendChild(cookiePopup.element)
document.body.appendChild(cookiePopup.revokeBtn)

window.addEventListener('load', () => {
  
  // If they haven't consented or they haven't accepted => Show the popup
  if (!cookiePopup.hasConsented() || cookiePopup.getStatus() !== 'allow') {
    cookiePopup.open()
  }
  else {
    // Otherwise add analytics and show the revoke button
    enableAnalytics()
    cookiePopup.toggleRevokeButton(true)
  }
})

function enableAnalytics() {
  if (!window.gaTrackingCode) return
  window.ga('set', 'anonymizeIp', true)
  window.ga('create', window.gaTrackingCode, 'auto')
  window.ga('send', 'pageview')
}

function deleteCookie(name) {
  document.cookie = name + `=;path=/;expires=${new Date().toUTCString()};`
}

function clearCookies() {
  Object.keys(cookie.parse(document.cookie)).forEach(name => {
    if (name !== 'cookieconsent_status') deleteCookie(name)
  })
}
