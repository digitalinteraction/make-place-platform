const path = require('path')
const ExtractTextPlugin = require('extract-text-webpack-plugin')

const extractSass = new ExtractTextPlugin({
  filename: '[name].css'
})

function resolve (dir) { return path.join(__dirname, '..', '..', dir) }


let cssOptions = {
  minimize: true,
  sourceMap: true
}

let scssOptions = Object.assign({
  data: '@import "_config/scss/vars"; @import "_config/scss/common";',
  outputStyle: 'compact',
  sourceMap: true
})


module.exports = {
  entry: {
    generic: resolve('themes/generic/css/generic.scss'),
    auth: resolve('auth/css/auth.scss')
  },
  output: {
    filename: '[name].css',
    path: resolve('public/css')
  },
  devtool: '#source-map',
  module: {
    rules: [
      {
        test: /\.scss$/,
        use: extractSass.extract({
          use: [
            { loader: 'css-loader', options: cssOptions },
            { loader: 'sass-loader', options: scssOptions } ]
        })
      }
    ]
  },
  plugins: [
    extractSass
  ]
}
