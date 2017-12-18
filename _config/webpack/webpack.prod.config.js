const webpack = require('webpack')
const merge = require('webpack-merge')

const base = require('./webpack.base.config')
const config = require('./config')

module.exports = merge(base, {
  plugins: [
    new webpack.DefinePlugin({
      'process.env': config.build.env
    }),
    new webpack.optimize.UglifyJsPlugin({
      compress: { warnings: false },
      sourceMap: true
    }),
    new webpack.SourceMapDevToolPlugin({
      filename: 'js/[name].js.map',
      exclude: [/vendor/, /manifest/]
    })
  ]
})
