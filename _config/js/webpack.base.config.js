const path = require('path')
const vueConfig = require('./vue-loader.config')

function resolve(dir) { return path.join(__dirname, '..', '..', dir) }


module.exports = {
  entry: {
    maps: resolve('maps/js/maps-main.js'),
    auth: resolve('auth/js/auth-main.js')
  },
  output: {
    filename: '[name].js',
    path: resolve('public/js')
  },
  resolve: {
    alias: {
      'vue$': 'vue/dist/vue.esm.js',
    }
  },
  module: {
    rules: [
      {
        test: /\.(js|vue)$/,
        loader: 'eslint-loader',
        exclude: /node_modules/,
        enforce: 'pre',
        // include: [resolve('src')],
        options: {
          formatter: require('eslint-friendly-formatter')
        }
      },
      {
        test: /\.vue$/,
        loader: 'vue-loader',
        options: vueConfig
      },
      {
        test: /\.js$/,
        exclude: /node_modules/,
        loader: 'babel-loader'
      }
    ]
  }
}
