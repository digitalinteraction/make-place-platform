const path = require('path')
const ExtractTextPlugin = require('extract-text-webpack-plugin')

const extractSass = new ExtractTextPlugin({
  filename: 'css/[name].css'
})

function resolve(dir) { return path.join(__dirname, '..', '..', dir) }


let cssOptions = {
  minimize: true,
  sourceMap: true
}

let scssOptions = {
  data: '@import "_config/scss/vars"; @import "_config/scss/common";',
  outputStyle: 'compact',
  includePaths: [ 'node_modules' ],
  sourceMap: true
}


module.exports = {
  entry: {
    styles: [
      resolve('themes/generic/css/generic.scss'),
      resolve('auth/css/auth.scss'),
      resolve('maps/css/maps.scss'),
      resolve('interaction/css/interaction.scss'),
      resolve('surveys/css/surveys.scss')
    ]
  },
  output: {
    filename: 'css/[name].css',
    path: resolve('public')
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
