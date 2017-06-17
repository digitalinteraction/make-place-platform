const webpack = require('webpack')
// const config = require('./js/webpack.prod.config')
const ora = require("ora")
const chalk = require("chalk")

let config = [
  require('./js/webpack.prod.config'),
  require('./scss/webpack.base.config')
]

process.stdout.write('\033c');

var spinner = ora("Compiling js & scss ...")
spinner.start()

webpack(config, (err, result) => {
  
  spinner.stop();
  
  if (err) throw err;
  
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
