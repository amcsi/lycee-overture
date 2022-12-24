import { createI18n } from 'vue-i18n';
import en from './en';

const i18n = createI18n({
  locale: window.locale,
  fallbackLocale: 'en',
  messages: {
    en,
  },
});

export default i18n;
