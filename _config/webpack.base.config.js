const path = require("path")

function resolve (dir) { return path.join(__dirname, '..', dir) }

module.exports = {
  entry: {
    maps: path.resolve(__dirname, '../maps/js/maps.js'),
    auth: path.resolve(__dirname, '../auth/js/auth.js')
  },
  output: {
    filename: '[name].js',
    path: path.resolve(__dirname, '../public/js')
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
      // {
      //   test: /\.vue$/,
      //   loader: 'vue-loader',
      //   options: vueLoaderConfig
      // },
      {
        test: /\.js$/,
        exclude: /node_modules/,
        loader: 'babel-loader',
      },
    ]
  }
}
