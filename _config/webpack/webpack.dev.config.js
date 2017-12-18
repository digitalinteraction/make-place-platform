const webpack = require('webpack')
const merge = require('webpack-merge')

const FriendlyErrorsPlugin = require('friendly-errors-webpack-plugin')

const base = require('./webpack.base.config')
const config = require('./config')

module.exports = merge(base, {
  watch: true,
  devtool: '#cheap-module-eval-source-map',
  plugins: [
    new webpack.DefinePlugin({
      'process.env': config.dev.env
    }),
    new webpack.NoEmitOnErrorsPlugin(),
    new FriendlyErrorsPlugin()
  ]
})
