// const webpack = require('webpack')

const merge = require('webpack-merge')

const base = require('./webpack.base.config')

module.exports = merge(base, {
  watch: true,
  devtool: '#cheap-module-eval-source-map'
})
