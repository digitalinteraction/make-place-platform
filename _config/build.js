const webpack = require('webpack')
// const config = require('./js/webpack.prod.config')
const ora = require('ora')
const chalk = require('chalk')

let config = []

if (process.argv.includes('js')) {
  config.push(require('./js/webpack.prod.config'))
}

if (process.argv.includes('scss')) {
  config.push(require('./scss/webpack.base.config'))
}

var spinner = ora('Compiling assets ...')
spinner.start()

let compiler = webpack(config)

compiler.run((err, result) => {

  spinner.stop()
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
    }) + '\n\n')
  })

  console.log(chalk.cyan('  Build complete.\n'))
})
