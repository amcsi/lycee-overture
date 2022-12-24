import vue from '@vitejs/plugin-vue';
import laravel from 'laravel-vite-plugin';
import AutoImport from 'unplugin-auto-import/vite';
import { ElementPlusResolver } from 'unplugin-vue-components/resolvers';
import Components from 'unplugin-vue-components/vite';
import { defineConfig } from 'vite';
import vuetify from 'vite-plugin-vuetify';

export default defineConfig({
  resolve: {
    alias: {
      vue: '@vue/compat',
    },
  },
  plugins: [
    laravel([
      //'resources/css/app.scss',
      'resources/js/app.js',
    ]),
    vue({
      template: {
        transformAssetUrls: {
          base: null,
          includeAbsolute: false,
        },
        compilerOptions: {
          compatConfig: {
            MODE: 2,
            COMPILER_V_BIND_OBJECT_ORDER: false,
          },
        },
      },
    }),
    vuetify({ autoImport: true }),
    AutoImport({
      resolvers: [ElementPlusResolver()],
    }),
    Components({
      resolvers: [ElementPlusResolver()],
    }),
  ],
  css: {
    preprocessorOptions: {
      scss: {
        quietDeps: true,
      },
    },
  },
});
