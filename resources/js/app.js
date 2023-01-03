/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
import { BrowserTracing } from '@sentry/tracing';
import * as Sentry from '@sentry/vue';
import { ElMessage } from 'element-plus';
import 'element-plus/theme-chalk/index.css';
import { createApp } from 'vue';
import App from './App.vue';
import i18n from './i18n';
import router from './router';
import store from './store/index';

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
const app = createApp(App);
app.config.globalProperties.$formatDate = formatDate;
app.config.globalProperties.$displaySuccess = displaySuccess;
app.config.globalProperties.$displayError = displayError;
app.use(store).use(router).use(i18n);

Sentry.init({
  app,
  dsn: window.vars.sentryToken,
  integrations: [
    new BrowserTracing({
      routingInstrumentation: Sentry.vueRouterInstrumentation(router),
      tracingOrigins: ['localhost', 'https://lycee-tcg.eu'],
    }),
  ],
  // Set tracesSampleRate to 1.0 to capture 100%
  // of transactions for performance monitoring.
  // We recommend adjusting this value in production
  tracesSampleRate: 1.0,
  logErrors: true,
});

app.mount('#app');

function formatDate(value) {
  if (typeof value === 'string') {
    value = new Date(value);
  }
  return value ? dateFormatter.format(value) : '';
}

const toastDuration = 10000;

function displaySuccess(message) {
  return ElMessage({
    type: 'success',
    message,
    duration: toastDuration,
  });
}

function displayError(message) {
  return ElMessage({
    type: 'error',
    message,
    duration: toastDuration,
  });
}

const dateFormatter = new Intl.DateTimeFormat(void 0, {
  year: 'numeric',
  month: 'numeric',
  day: 'numeric',
  hour: 'numeric',
  minute: 'numeric',
});
