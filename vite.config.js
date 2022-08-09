import vue2 from '@vitejs/plugin-vue2';
import laravel from 'laravel-vite-plugin';
import { defineConfig } from 'vite';

export default defineConfig({
  plugins: [
    laravel([
      //'resources/css/app.scss',
      'resources/js/app.js',
    ]),
    vue2({
      template: {
        transformAssetUrls: {
          base: null,
          includeAbsolute: false,
        },
      },
    }),
  ],
});
