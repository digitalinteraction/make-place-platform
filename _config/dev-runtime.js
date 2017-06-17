const webpack = require('webpack')
const ora = require("ora")
const chalk = require("chalk")
const moment = require("moment")

var spinner = ora("Spinning up")
process.stdout.write('\033c');

spinner.start()

let config = [
  require('./js/webpack.dev.config'),
  require('./scss/webpack.base.config')
]

let watching = webpack(config, (err, result) => {
  
  spinner.stop();
  
  process.stdout.write('\033c');
  
  if (err) throw err;
  
  
  let now = moment().format('MMMM Do YYYY, h:mm:ss a')
  console.log(chalk.bgGreen(chalk.black(' DONE ')) + ` - ${now}\n`)
  
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
