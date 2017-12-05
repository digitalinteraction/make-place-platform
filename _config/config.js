const path = require('path')

function resolve(dir) {
  return path.join(__dirname, '..', dir)
}

module.exports = {
  build: {
    env: { NODE_ENV: '"production"' },
    
    assetsRoot: resolve('public'),
    assetsSubDirectory: '',
    assetsPublicPath: '/public/',
    
    productionSourceMap: true
  },
  dev: {
    env: { NODE_ENV: '"development"' },
    
    port: 8080,
    
    assetsSubDirectory: '',
    assetsPublicPath: '/public/',
    
    cssSourceMap: true
  }
}
