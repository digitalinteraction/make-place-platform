const webpack = require('webpack')
const merge = require('webpack-merge')

const OptimizeCSSPlugin = require('optimize-css-assets-webpack-plugin')

const base = require('./webpack.base.config')
const config = require('./config')

module.exports = merge(base, {
  devtool: '#source-map',
  plugins: [
    // new webpack.DefinePlugin({
    //   'process.env': config.build.env
    // }),
    // new webpack.optimize.UglifyJsPlugin({
    //   compress: { warnings: false },
    //   sourceMap: true
    // }),
    
    
    // new webpack.SourceMapDevToolPlugin({
    //   filename: 'js/[name].js.map',
    //   exclude: [/vendor/, /manifest/]
    // }),
    // new OptimizeCSSPlugin({
    //   cssProcessorOptions: { safe: true }
    // })
  ]
})
