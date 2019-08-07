const webpack = require('webpack')
const ora = require('ora')
const chalk = require('chalk')

process.env.NODE_ENV = 'production'

const config = require('./webpack/webpack.prod.config')

let spinner = ora('Compiling assets ...')
spinner.start()

let compiler = webpack(config)

compiler.run((err, result) => {
  spinner.stop()
  if (err) throw err

  console.log(chalk.cyan('  Build complete.\n'))
})
