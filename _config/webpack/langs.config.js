
const ExtractTextPlugin = require('extract-text-webpack-plugin')


const isProduction = process.env.NODE_ENV === 'production'
const shouldExtract = isProduction
const shouldSourceMap = true


exports.css = {
  minimize: process.env.NODE_ENV === 'production',
  sourceMap: shouldSourceMap
}

exports.scss = {
  data: '@import "_config/scss/vars"; @import "_config/scss/common";',
  outputStyle: 'compact',
  includePaths: [ 'node_modules' ],
  sourceMap: shouldSourceMap
}

exports.vue = {
  loaders: {
    css: makeVueStyleLoader(),
    scss: makeVueStyleLoader('sass', exports.scss)
  }
}


/** Generates a style loader for vue-loader */
function makeVueStyleLoader(name = null, options = null) {
  
  // Start a stack of loaders, starting with css
  let loaderStack = [
    { loader: 'css-loader', options: exports.css }
  ]
  
  // If a name was passed, add that to the stack
  if (name) {
    loaderStack.push({
      loader: `${name}-loader`,
      options: Object.assign({ sourceMap: shouldSourceMap }, options)
    })
  }
  
  // If we are extracting, return a loader that extracts to a external file
  if (shouldExtract) {
    return ExtractTextPlugin.extract({
      use: loaderStack,
      fallback: 'vue-style-loader'
    })
  }
  else {
    
    // If not extracting, use a stack with vue-style-loader at the start
    return ['vue-style-loader'].concat(loaderStack)
  }
}
