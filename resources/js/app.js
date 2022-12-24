/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
import { BrowserTracing } from '@sentry/tracing';
import * as Sentry from '@sentry/vue';
import { createApp } from 'vue';
import { createVuetify } from 'vuetify';
import * as components from 'vuetify/components';
import * as directives from 'vuetify/directives';
import 'vuetify/styles';
// TODO put back
//import { ElMessage } from 'element-plus';
//import 'element-plus/theme-chalk/index.css';
import App from './App.vue';
import i18n from './i18n';
import router from './router';
import store from './store/index';

const vuetify = createVuetify({
  components,
  directives,
});

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
const app = createApp(App);
app.config.globalProperties.$formatDate = formatDate;
app.use(store).use(router).use(i18n).use(vuetify);

Sentry.init({
  app,
  dsn: window.vars.sentryToken,
  integrations: [
    new BrowserTracing({
      routingInstrumentation: Sentry.vueRouterInstrumentation(router),
      tracingOrigins: ['localhost', 'lycee-tcg.eu', /^\//],
    }),
  ],
  // Set tracesSampleRate to 1.0 to capture 100%
  // of transactions for performance monitoring.
  // We recommend adjusting this value in production
  tracesSampleRate: 1.0,
});

app.mount('#app');

function formatDate(value) {
  if (typeof value === 'string') {
    value = new Date(value);
  }
  return value ? dateFormatter.format(value) : '';
}

//console.info('app.config.globalProperties', app.config.globalProperties);

//app.config = {
//  errorHandler(err) {
//    console.error(err);
//    // todo add back
//    //rollbar.error(err);
//  },
//  globalProperties: {
//    // todo add back
//    //$rollbar: rollbar,
//    formatDate(value) {
//      if (typeof value === 'string') {
//        value = new Date(value);
//      }
//      return value ? dateFormatter.format(value) : '';
//    },
//    //$displaySuccess(message) {
//    //  return ElMessage({
//    //    type: 'success',
//    //    message,
//    //    duration: toastDuration,
//    //  });
//    //},
//    //$displayError(message) {
//    //  return ElMessage({
//    //    type: 'error',
//    //    message,
//    //    duration: toastDuration,
//    //  });
//    //},
//  },
//};

const dateFormatter = new Intl.DateTimeFormat(void 0, {
  year: 'numeric',
  month: 'numeric',
  day: 'numeric',
  hour: 'numeric',
  minute: 'numeric',
});
