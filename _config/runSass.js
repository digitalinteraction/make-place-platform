#!/usr/bin/env node

const { join } = require('path')
const { readFileSync, writeFileSync } = require('fs')
const crypto = require('crypto')
const webpack = require('webpack')
const chalk = require('chalk')
const rm = require('rimraf')

const ExtractTextPlugin = require('extract-text-webpack-plugin')
const OptimizeCSSPlugin = require('optimize-css-assets-webpack-plugin')

process.env.NODE_ENV = 'production'
const config = require('./webpack/webpack.prod.config')

const varsHashPath = join(__dirname, '../assets/vars.hash')
const readVarsHash = () => readFileSync(varsHashPath, 'utf8')
const readVars = () => readFileSync(join(__dirname, 'scss/vars.scss'), 'utf8')

// Gets a hash of the current sass variables
function hashCurrentVars() {
  return crypto
    .createHash('sha256')
    .update(readVars())
    .digest('hex')
}

// Only regenerate if the sass variables have changed
function skipRegeneration() {
  try {
    return hashCurrentVars() === readVarsHash()
  }
  catch (err) {
    return false
  }
}

// Write the hash of css variables to a file in a volume
function writeVarsHash() {
  try {
    writeFileSync(varsHashPath, hashCurrentVars())
  }
  catch (err) {
    console.log('Failed', err)
    process.exit(1)
  }
}

// Exit now if we should skip sass regeneration
if (skipRegeneration()) {
  console.log('Skipping sass regeneration')
  process.exit(0)
}

//
// Regenerate css assets based on new variables
//

console.log('Regenerating css with changed variables')

// Point to a different directory
const newPath = join(__dirname, '../public/_tmp')
config.output.path = newPath

// Only use the theme input
config.entry = { theme: config.entry.theme }

// Use different plugins
config.plugins = [
  new ExtractTextPlugin('../css/[name].css'),
  new OptimizeCSSPlugin({
    cssProcessorOptions: { safe: true }
  })
]

// Run webpack
webpack(config).run((err, result) => {
  if (err) throw err

  process.stdout.write(
    result.toString({
      colors: true,
      modules: false,
      children: false,
      chunks: false,
      chunkModules: false,
      hash: false,
      version: false
    }) + '\n\n'
  )

  // Remove the temporary dir
  rm(newPath, () => chalk.cyan('  Build complete.\n'))
  
  // Write the variables hash
  writeVarsHash()
})
