import { pick } from 'lodash-es';

export default {
  computed: {
    isLocaleJapanese() {
      return this.locale === 'ja';
    },
    cardHasApprovedTranslation() {
      return !this.isLocaleJapanese && !!this.card.auto_translation;
    },
    shouldShowSuggestion() {
      return !this.cardHasApprovedTranslation && this.suggestion;
    },
    cardText() {
      if (this.isLocaleJapanese || !this.card.translation) {
        return this.card.japanese;
      }

      const translation = this.card.translation;
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
      return this.isLocaleJapanese ? this.card.japanese : this.card.translation;
    },
    suggestion() {
      return this.card.suggestions.filter(v => v.locale === this.locale)[0] ?? null;
    },
  },
};
