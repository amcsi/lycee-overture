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
      <div
        v-for="nameProperty of nameProperties"
        v-if="card.japanese[nameProperty.key]"
        :key="nameProperty.key"
      >
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
      </div>
    </el-card>
    <div style="height: 2rem" />
  </div>
</template>

<script>
import { oneSkyProjectUrl } from '../../value/env';
import ExternalLink from '../common/ExternalLink';
import FlagImage from '../common/FlagImage';

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
  data() {
    const nameProperties = [
      { key: 'name', name: 'Name' },
      { key: 'ability_name', name: 'Ability Name' },
      { key: 'character_type', name: 'Character Type' },
    ];
    const japaneseComponentsByProperty = {};
    const translatedComponentsByProperty = {};
    for (const { key } of Object.values(nameProperties)) {
      japaneseComponentsByProperty[key] = this.card.japanese[key].split(splitBy.ja);
      translatedComponentsByProperty[key] = this.card.translation[key].split(splitBy.en);
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
