const path = require('path')
const webpack = require('webpack')

// const config = require('./config')
const loaders = require('./loaders')

const EslintFriendlyFormatter = require('eslint-friendly-formatter')
const FriendlyErrorsPlugin = require('friendly-errors-webpack-plugin')
const MiniCssExtractPlugin = require('mini-css-extract-plugin')
const VueLoaderPlugin = require('vue-loader/lib/plugin')

// const langOptions = require('./langs.config')

const resolve = filepath => path.join(__dirname, '../..', filepath)

module.exports = {
  entry: {
    theme: resolve('themes/generic/js/theme-main.js'),
    common: resolve('themes/generic/js/common-main.js'),
    maps: resolve('maps/js/maps-main.js'),
    auth: resolve('auth/js/auth-main.js')
  },
  output: {
    path: resolve('public'),
    filename: 'js/[name].js',
    publicPath: '/public/'
  },
  resolve: {
    extensions: [ '.js', '.vue', '.json' ],
    alias: {
      'vue$': 'vue/dist/vue.esm.js'
    }
  },
  mode: process.env.NODE_ENV || 'production',
  module: {
    rules: [
      {
        test: /\.js$/,
        loader: 'eslint-loader',
        exclude: /node_modules/,
        enforce: 'pre',
        options: { formatter: EslintFriendlyFormatter }
      },
      {
        test: /\.vue$/,
        loader: 'vue-loader'
      },
      {
        test: /\.js$/,
        exclude: /node_modules/,
        loader: 'babel-loader'
      },
      // {
      //   test: /\.css/,
      //   use: ExtractTextPlugin.extract({
      //     fallback: 'style-loader',
      //     use: [
      //       { loader: 'css-loader', options: langOptions.css }
      //     ]
      //   })
      // },
      {
        test: /\.css$/,
        use: loaders.css
      },
      {
        test: /\.sass/,
        use: loaders.sass
      },
      {
        test: /\.scss/,
        use: loaders.sass
      },
      // {
      //   test: /\.(sa|sc|c)ss$/,
      //   use: [
      //     {
      //       loader: MiniCssExtractPlugin.loader,
      //       options: langOptions.cssExtractor
      //     },
      //     { loader: 'css-loader', options: langOptions.css },
      //     { loader: 'sass-loader', options: langOptions.scss }
      //   ]
      // },
      // {
      //   test: /\.scss$/,
      //   use: ExtractTextPlugin.extract({
      //     fallback: 'style-loader',
      //     use: [
      //       { loader: 'css-loader', options: langOptions.css },
      //       { loader: 'sass-loader', options: langOptions.scss }
      //     ]
      //   })
      // },
      {
        test: /\.(png|jpe?g|gif|svg)(\?.*)?$/,
        loader: 'file-loader',
        options: {
          name: 'img/[name].[ext]'
        }
      },
      {
        test: /\.(woff2?|eot|ttf|otf)(\?.*)?$/,
        loader: 'file-loader',
        options: {
          name: 'font/[name].[ext]'
        }
      }
    ]
  },
  plugins: [
    new MiniCssExtractPlugin({
      filename: 'css/[name].css',
      chunkFilename: 'css/[id].css'
    }),
    new webpack.NoEmitOnErrorsPlugin(),
    new FriendlyErrorsPlugin(),
    new VueLoaderPlugin()
    // new webpack.optimize.CommonsChunkPlugin({
    //   name: 'vendor',
    //   minChunks: function(module, count) {
    //     // any required modules inside node_modules are extracted to vendor
    //     return module.resource &&
    //       /\.js$/.test(module.resource) &&
    //       module.resource.indexOf(path.join(__dirname, '../../node_modules')) === 0
    //   }
    // }),
    // new webpack.optimize.CommonsChunkPlugin({
    //   name: 'manifest',
    //   chunks: ['vendor']
    // })
  ]
}
