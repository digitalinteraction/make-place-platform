
module.exports = {
  css: {
    minimize: true,
    sourceMap: true
  },
  sass: {
    data: '@import "_config/scss/vars"; @import "_config/scss/common";',
    outputStyle: 'compact',
    includePaths: [ 'node_modules' ],
    sourceMap: true
  },
  vue: {
    extract: true
  }
}
