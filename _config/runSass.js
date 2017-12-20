const path = require('path')
const webpack = require('webpack')
const ora = require('ora')
const chalk = require('chalk')
const rm = require('rimraf')

const ExtractTextPlugin = require('extract-text-webpack-plugin')
const OptimizeCSSPlugin = require('optimize-css-assets-webpack-plugin')

process.env.NODE_ENV = 'production'

const config = require('./webpack/webpack.prod.config')

let spinner = ora('Compiling Sass ...')
spinner.start()

// Point to a different directory
const newPath = path.join(__dirname, '../public/_tmp')
config.output.path = newPath

// Use different plugins
config.plugins = [
  new ExtractTextPlugin('../css/[name].css'),
  new OptimizeCSSPlugin({
    cssProcessorOptions: { safe: true }
  })
]


// Run webpack
webpack(config).run((err, result) => {
  
  spinner.stop()
  if (err) throw err
  
  process.stdout.write(result.toString({
    colors: true,
    modules: false,
    children: false,
    chunks: false,
    chunkModules: false,
    hash: false,
    version: false
  }) + '\n\n')
  
  rm(newPath, () => {
    chalk.cyan('  Build complete.\n')
  })
})
