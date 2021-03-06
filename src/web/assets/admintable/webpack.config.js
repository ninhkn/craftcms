/* jshint esversion: 6 */
/* globals module, require */
const {getConfig} = require('@craftcms/webpack');

module.exports = getConfig({
  context: __dirname,
  type: 'vue',
  config: {
    entry: {app: './main.js'},
    output: {
      filename: 'js/app.js',
      chunkFilename: 'js/[name].js',
    },
  },
});
