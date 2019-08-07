const MiniCssExtractPlugin = require('mini-css-extract-plugin')

const isProduction = process.env.NODE_ENV === 'production'
// const shouldExtract = isProduction
const shouldSourceMap = false

exports.css = {
  minimize: isProduction,
  sourceMap: shouldSourceMap
}

exports.scss = {
  data: '@import "_config/scss/vars"; @import "_config/scss/common";',
  outputStyle: 'compact',
  includePaths: [ 'node_modules' ],
  sourceMap: shouldSourceMap
}

exports.vue = {
  // loaders: {
  //   css: makeVueStyleLoader(),
  //   scss: makeVueStyleLoader('sass', exports.scss)
  // }
}

exports.cssExtractor = {
  hmr: process.env.NODE_ENV === 'development'
}


/** Generates a style loader for vue-loader */
function makeVueStyleLoader(name = null, options = null) {
  
  // Start a stack of loaders, starting with css
  const loaderStack = [
    { loader: 'css-loader', options: exports.css }
  ]
  
  // If a name was passed, add that to the stack
  if (name) {
    loaderStack.push({
      loader: `${name}-loader`,
      options: Object.assign({ sourceMap: shouldSourceMap }, options)
    })
  }
  
  loaderStack.push('postcss-loader')
  
  // If we are extracting, return a loader that extracts to a external file
  if (isProduction) {
    loaderStack.unshift({
      loader: MiniCssExtractPlugin.loader,
      options: exports.cssExtractor
    })
  }
  else {
    
    // If not extracting, use a stack with vue-style-loader at the start
    loaderStack.unshift('vue-style-loader')
  }
  
  return loaderStack
}
