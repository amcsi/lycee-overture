<template>
  <div>
    <el-card>
      <el-alert show-icon title="Guidelines" type="warning">
        Please read
        <ExternalLink href="/help-translate#card-names-character-types"
          >the guidelines
        </ExternalLink>
        for <strong>"Card Names, Ability Names, Character Types"</strong> before submitting name /
        ability type translations.
      </el-alert>
      <div v-for="nameProperty of nameProperties" :key="nameProperty.key">
        <template v-if="translationsByLocale.ja[nameProperty.key]">
          <h3>{{ nameProperty.name }}</h3>
          <div v-for="(japanese, index) of japaneseComponentsByProperty[nameProperty.key]">
            <el-tag effect="plain">
              <FlagImage locale="ja" />
              {{ japanese }}
            </el-tag>
            <el-tag effect="plain">
              <FlagImage locale="en" />
              {{ translatedComponentsByProperty[nameProperty.key][index] }}
            </el-tag>
            <ExternalLink :href="translateLinkByProperty[nameProperty.key][index]">
              Translate
            </ExternalLink>
          </div>
        </template>
      </div>
    </el-card>
    <div style="height: 2rem" />
  </div>
</template>

<script>
import cardMixin from '../../utils/cardMixin';
import { oneSkyProjectUrl } from '../../value/env';
import ExternalLink from '../common/ExternalLink.vue';
import FlagImage from '../common/FlagImage.vue';

const splitBy = {
  en: /, |\//,
  ja: /[・／]/,
};

const oneSkyKeywordBaseUrl = `${oneSkyProjectUrl}#/?keyword=`;

/** @class CardNameTranslator */
export default {
  name: 'CardNameTranslator',
  components: { ExternalLink, FlagImage },
  props: {
    card: Object,
  },
  mixings: [cardMixin],
  data() {
    const nameProperties = [
      { key: 'name', name: 'Name' },
      { key: 'ability_name', name: 'Ability Name' },
      { key: 'character_type', name: 'Character Type' },
    ];
    const japaneseComponentsByProperty = {};
    const translatedComponentsByProperty = {};
    for (const { key } of Object.values(nameProperties)) {
      const japanese = this.translationsByLocale.ja[key];
      const translated = this.bestTranslation[key];

      let japaneseSplit;
      let translatedSplit;
      if (japanese === translated) {
        // If the Japanese and translated names are the same, then translate them as a whole.
        japaneseSplit = [japanese];
        translatedSplit = [translated];
      } else {
        japaneseSplit = japanese.split(splitBy.ja);
        translatedSplit = translated.split(splitBy.en);
      }
      japaneseComponentsByProperty[key] = japaneseSplit;
      translatedComponentsByProperty[key] = translatedSplit;
    }

    return {
      nameProperties,
      japaneseComponentsByProperty,
      translatedComponentsByProperty,
    };
  },
  computed: {
    translateLinkByProperty() {
      const ret = {};

      for (const { key } of this.nameProperties) {
        ret[key] = this.japaneseComponentsByProperty[key].map(
          japaneseName => `${oneSkyKeywordBaseUrl}${encodeURIComponent(japaneseName)}`
        );
      }

      return ret;
    },
  },
};
</script>

<style scoped lang="scss"></style>
