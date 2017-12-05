const webpack = require('webpack')
const chalk = require('chalk')

process.stdout.write('\x1Bc')

console.log('Spinning up ...')

let config = [
  require('./js/webpack.dev.config'),
  require('./scss/webpack.base.config')
]

webpack(config, (err, result) => {
  
  process.stdout.write('\x1Bc')
  
  if (err) throw err
  
  result.stats.forEach(stat => {
    process.stdout.write(stat.toString({
      colors: true,
      modules: false,
      children: false,
      chunks: false,
      chunkModules: false,
      hash: false,
      version: false
    }) + '\n\n\n')
  })
  
  console.log(chalk.cyan('  Waiting for changes ...'))
})
