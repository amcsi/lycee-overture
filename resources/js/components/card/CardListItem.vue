<template>
  <el-card class="card-list-item" :class="cls">
    <div class="card-list-item-inner">
      <CardThumbnail class="card-thumbnail" :id="card.id" :variants="card.variants" />
      <div class="card-details">
        <div class="names-and-type">
          <div>
            <span class="card-id">{{ card.id }}</span>
            <span class="card-name">{{ cardText.name }}</span>
            <span v-if="isCharacter">
              - <span class="card-ability-name">{{ cardText.ability_name }}</span>
              <span class="card-character-type" v-if="characterType"
                >- Type: {{ cardText.character_type || '-' }}</span
              >
            </span>
            <el-button
              class="show-when-hovering"
              size="mini"
              @click="translateNamesOpen = !translateNamesOpen"
              >{{ translateNamesOpen ? 'Hide' : 'Help Translate' }}
            </el-button>
          </div>
          <div style="flex: 1" />
          <div>
            <span class="rarity">{{ card.rarity }}</span>
          </div>
        </div>
        <CardNameTranslator v-if="translateNamesOpen" :card="card" />
        <div class="stats-and-stuff">
          <div class="flex-center">
            <div class="flex-center gaps">
              <CardText class="element" :text="card.element" />
              <StatValue type="ex" :value="card.ex" />
              <template v-if="isCharacter">
                <StatValue type="dmg" :value="card.dmg" style="margin-left: 0.75rem" />
                <StatValue type="ap" :value="card.ap" />
                <StatValue type="dp" :value="card.dp" />
                <StatValue type="sp" :value="card.sp" />
              </template>
              <span style="margin-left: 0.75rem">Cost:</span>
              <CardText class="cost" :text="card.cost" />
            </div>
            <div style="flex: 1" />
            <div v-if="card.set">
              <router-link :to="{ path: '/cards', query: { set: card.set.id } }">
                {{ card.set.full_name }}
              </router-link>
              <router-link
                :to="{ path: '/cards', query: { brand: card.set.brand } }"
                v-html="formattedBrand"
              />
            </div>
          </div>
        </div>
        <div class="card-description" v-if="hasCardDescription">
          <CardDescription :translation="cardText" />
          <CardTranslator v-if="translateMode" :card="card" :id="this.card.id" />
        </div>
        <div style="flex: 1" />
        <div class="show-when-hovering" style="text-align: right">
          <span v-if="user">
            <span class="clickable" @click="translateMode = !translateMode"
              >{{ translateMode ? 'Collapse' : '' }} Suggest Translation</span
            >
            -
          </span>
          <ExternalLink :href="rulingsLink">Rulings</ExternalLink>
          <span v-if="showLanguageSelectors">
            <span
              class="language-link clickable"
              tabindex="0"
              :class="{ active: localLocale !== 'ja' }"
              @click="localLocale = 'en'"
            >
              <FlagImage locale="en" />
            </span>
            <span
              class="language-link clickable"
              tabindex="0"
              :class="{ active: localLocale === 'ja' }"
              @click="localLocale = 'ja'"
            >
              <FlagImage locale="ja" />
            </span>
          </span>
        </div>
      </div>
    </div>
  </el-card>
</template>

<script>
import { mapComputed } from '../../store/storeUtils';
import formatCardMixin from '../../utils/formatCardMixin';
import { areaType, characterType, eventType, itemType } from '../../value/cardType';
import ExternalLink from '../common/ExternalLink';
import FlagImage from '../common/FlagImage';
import CardDescription from './CardDescription';
import CardNameTranslator from './CardNameTranslator';
import CardText from './CardText';
import CardThumbnail from './CardThumbnail';
import CardTranslator from './CardTranslator';
import StatValue from './StatValue';

/** @class CardListItem */
export default {
  name: 'CardListItem',
  components: {
    FlagImage,
    CardNameTranslator,
    StatValue,
    ExternalLink,
    CardText,
    CardDescription,
    CardThumbnail,
    CardTranslator,
  },
  props: {
    card: {
      type: Object,
      required: true,
    },
  },
  mixins: [formatCardMixin],
  data() {
    return {
      localLocale: null,
      translateMode: false,
      translateNamesOpen: false,
    };
  },
  computed: {
    cls() {
      return [`card-type-${this.cardTypeKey}`];
    },
    cardTypeKey() {
      switch (this.card.type) {
        case characterType:
          return 'character';
        case itemType:
          return 'item';
        case eventType:
          return 'event';
        case areaType:
          return 'area';
        default:
          return null;
      }
    },
    isCharacter() {
      return this.card.type === 0;
    },
    isLocaleJapanese() {
      return this.localLocale === 'ja';
    },
    characterType() {
      if (!this.isCharacter) {
        return '';
      }
      const characterType = this.cardText.character_type.trim();
      if (!characterType.length || characterType === '-') {
        // Empty or just a dash; return empty string.
        return '';
      }
      return characterType;
    },
    hasCardDescription() {
      const translation = this.cardText;
      return (
        translation.ability_cost.trim().length > 1 ||
        translation.ability_description.trim().length > 1 ||
        translation.comments.trim().length > 1
      );
    },
    cardText() {
      if (!this.card.translation) {
        return this.card.japanese;
      }
      return this.isLocaleJapanese ? this.card.japanese : this.card.translation;
    },
    showLanguageSelectors() {
      return !!this.card.translation && !this.translateMode;
    },
    rulingsLink() {
      let link = `https://lycee-tcg.com/faq/?word=${this.card.id}`;
      if (!this.isLocaleJapanese) {
        link = `https://translate.google.com/translate?sl=ja&tl=en&u=${encodeURI(link)}`;
      }
      return link;
    },
    formattedBrand() {
      return this.formatBrands(`[${this.card.set.brand}]`);
    },
    ...mapComputed('auth', ['user']),
  },
  created() {
    this.localLocale = this.card.translation ? 'en' : 'ja';
  },
};
</script>

<style scoped lang="scss">
@import 'resources/sass/variables';

.card-list-item {
  position: relative;
  margin-bottom: 1rem;
}

.card-list-item-inner {
  display: flex;
  flex-direction: row;
  justify-content: stretch;
}

.card-thumbnail {
  margin-left: -1rem;
  padding-left: 1rem;
  padding-right: 1rem;
}

.card-details {
  flex: 1;
  display: flex;
  flex-direction: column;
  min-height: 100%;
}

.names-and-type {
  display: flex;
  align-items: center;
  margin-bottom: 0.5rem;
  color: gray;
}

.card-id {
  font-weight: bold;
  font-size: 1.2em;
  color: #555555;
}

.stats-and-stuff {
  margin-bottom: 0.5rem;
}

.element {
  font-size: 1.5em;
  vertical-align: middle;
}

.stat {
  display: inline-block;
  color: white;
  padding: 0.3rem 0.4rem;
  font-size: 0.75rem;
  line-height: 1rem;
  border-radius: 1rem;
  vertical-align: middle;

  &:before {
    content: '';
    display: inline-block;
    vertical-align: middle;
    height: 100%;
  }
}

.card-description {
  border-top: 1px dashed #a4a4a4;
  padding-top: 0.5rem;
  line-height: 1.4;
}

.language-selector {
  position: absolute;
  top: 0.5rem;
  right: 0.5rem;
  display: none;

  .card-list-item:hover & {
    display: block;
  }
}

.language-link {
  margin-left: 0.25em;

  &:not(.active) {
    opacity: 0.25;
  }
}

.show-when-hovering {
  visibility: hidden;

  .card-list-item:hover & {
    visibility: visible;
  }
}

.flex-center {
  display: flex;
  align-items: center;
}

.gaps > * {
  margin-right: 0.25rem;
}

.clickable {
  color: $link-color;
}

.card-type-character {
  background-color: lighten(#000110, 94);
}

.card-type-area {
  background-color: lighten(#755819, 70);
}

.card-type-item {
  background-color: lighten(#346738, 65);
}

.card-type-event {
  background-color: lighten(#60011d, 77);
}
</style>
