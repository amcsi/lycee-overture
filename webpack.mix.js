let mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/assets/js/app.js', 'public/js')
  .sass('resources/assets/sass/app.scss', 'public/css');

if (!mix.inProduction()) {
  mix.sourceMaps();
}

// https://github.com/JeffreyWay/laravel-mix/issues/845#issuecomment-343188420
if (mix.config.hmr) {
  const host = process.env.HOT_HOST || '127.0.0.1';
  mix.options({
    hmrOptions: {
      host,
    },
  });

  mix.setResourceRoot(`//${host}:8080/`);
} else {
  // Only do cache-busting when not doing hot module replacement, otherwise we get:
  // "ENOENT: no such file or directory" every several hot reloads.
  mix.version();
}
