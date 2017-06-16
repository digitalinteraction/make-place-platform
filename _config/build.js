const webpack = require('webpack')
const config = require('./webpack.prod.config')
const ora = require("ora")
const chalk = require("chalk")

var spinner = ora("Compiling...")
spinner.start()

webpack(config, (err, stats) => {
  
  spinner.stop();
  
  if (err) throw err;
  
  process.stdout.write(stats.toString({
    colors: true,
    modules: false,
    children: false,
    chunks: false,
    chunkModules: false
  }) + '\n\n')

  console.log(chalk.cyan('  Build complete.\n'))
})
