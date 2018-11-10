import Vue from 'vue';
import VueI18n from 'vue-i18n';
import en from './en';

Vue.use(VueI18n);

const i18n = new VueI18n({
  locale: window.locale,
  messages: {
    en,
  },
});

// Hot updates
if (module.hot) {
  module.hot.accept('./en', function () {
    i18n.setLocaleMessage('en', require('./en').default)
  })
}

export default i18n;
