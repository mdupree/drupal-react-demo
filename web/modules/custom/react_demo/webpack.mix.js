const mix = require('laravel-mix');
mix.react('js/src/index.js', 'js/dist')
  .sourceMaps()
  .webpackConfig({
    devtool: "source-map",
    externals: {
      "drupal": "Drupal",
      "react": "React",
      "react-dom": "ReactDOM"
    },
  });
