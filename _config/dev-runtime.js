const webpack = require('webpack')
const chalk = require('chalk')

console.log('Spinning up ...')

process.env.NODE_ENV = 'development'

const config = require('./webpack/webpack.dev.config')

webpack(config, (err, result) => {
  // process.stdout.write('\x1Bc')
  
  if (err) throw err
  
  console.log(chalk.cyan('  Waiting for changes ...'))
})
