import { keyBy, pick } from 'lodash-es';

export default {
  computed: {
    isLocaleJapanese() {
      return this.locale === 'ja';
    },
    translationsByLocale() {
      return keyBy(this.card.translations, 'locale');
    },
    cardHasApprovedTranslation() {
      return !this.isLocaleJapanese && !!this.translationsByLocale.en;
    },
    shouldShowSuggestion() {
      return !this.cardHasApprovedTranslation && this.suggestion;
    },
    bestTranslation() {
      return (
        this.translationsByLocale[this.locale] ??
        this.translationsByLocale[`${this.locale}-auto`] ??
        this.translationsByLocale[`${this.locale}-deepl`] ??
        this.translationsByLocale.ja
      );
    },
    cardText() {
      if (this.isLocaleJapanese || !this.bestTranslation) {
        return this.translationsByLocale.ja;
      }

      const translation = this.bestTranslation;
      if (this.shouldShowSuggestion) {
        const suggestionTranslationProperties = [
          'basic_abilities',
          'pre_comments',
          'ability_cost',
          'ability_description',
          'comments',
        ];
        return { ...translation, ...pick(this.suggestion, suggestionTranslationProperties) };
      }
      return this.isLocaleJapanese ? this.translationsByLocale.ja : this.bestTranslation;
    },
    suggestion() {
      return this.card.suggestions?.filter(v => v.locale === this.locale)[0] ?? null;
    },
    locale() {
      return this.localLocale ?? window.locale;
    },
  },
};
