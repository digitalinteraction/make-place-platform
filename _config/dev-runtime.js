const webpack = require('webpack')
const config = require('./webpack.dev.config')
const ora = require("ora")
const chalk = require("chalk")
const moment = require("moment")

var spinner = ora("Spinning up")
process.stdout.write('\033c');

spinner.start()

let watching = webpack(config, (err, stats) => {
  
  spinner.stop();
  
  process.stdout.write('\033c');
  
  if (err) throw err;
  
  
  let now = moment().format('MMMM Do YYYY, h:mm:ss a')
  console.log(chalk.bgGreen(chalk.black(' DONE ')) + ` - ${now}\n\n`)
  
  process.stdout.write(stats.toString({
    colors: true,
    modules: false,
    children: false,
    chunks: false,
    chunkModules: false,
    hash: false,
    version: false
  }) + '\n\n')
  
  console.log(chalk.cyan('  Waiting for changes ...'))
})
