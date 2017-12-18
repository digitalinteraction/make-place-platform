const webpack = require('webpack')
const chalk = require('chalk')

console.log('Spinning up ...')

const config = require('./webpack/webpack.dev.config')

webpack(config, (err, result) => {
  
  process.stdout.write('\x1Bc')
  
  if (err) throw err
  
  process.stdout.write(result.toString({
    colors: true,
    modules: false,
    children: false,
    chunks: false,
    chunkModules: false,
    hash: false,
    version: false
  }) + '\n\n\n')
  
  console.log(chalk.cyan('  Waiting for changes ...'))
})
