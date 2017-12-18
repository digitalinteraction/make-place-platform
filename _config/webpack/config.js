const path = require('path')

const resolve = filepath => path.join(__dirname, '../..', filepath)

module.exports = {
  build: {
    env: { NODE_ENV: '"production"' },
    
    assetsRoot: resolve('public'),
    assetsPublicPath: '/public/',
    
    productionSourceMap: true
  },
  dev: {
    env: { NODE_ENV: '"development"' },
    
    assetsPublicPath: '/public/'
    
  }
}
