const MiniCssExtractPlugin = require('mini-css-extract-plugin')

const isProduction = process.env.NODE_ENV === 'production'

exports.css = [
  isProduction ? MiniCssExtractPlugin.loader : 'vue-style-loader',
  'css-loader'
]

exports.sass = [
  ...exports.css,
  {
    loader: 'sass-loader',
    options: {
      data: '@import "_config/scss/vars"; @import "_config/scss/common";',
      outputStyle: 'compact'
      // includePaths: [ 'node_modules' ]
    }
  }
]
