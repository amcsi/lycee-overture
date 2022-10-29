<template>
  <el-card class="card-list-item" :class="cls">
    <div class="card-list-item-inner">
      <CardThumbnail class="card-thumbnail" :id="card.id" :variants="card.variants" />
      <div class="card-details">
        <div class="names-and-type">
          <div>
            <span class="card-id">{{ card.id }}{{ currentVariant }}</span>
            <span class="card-name">{{ cardText.name }}</span>
            <span v-if="isCharacter">
              <span class="normal-screen-separator">-</span>
              <span class="card-ability-name">{{ cardText.ability_name }}</span>
              <span class="card-character-type" v-if="characterType"
                ><span class="normal-screen-separator">-</span> Type:
                {{ cardText.character_type || '-' }}</span
              >
            </span>
            <el-button
              v-if="!isLocaleJapanese"
              class="show-when-hovering"
              size="mini"
              @click="translateNamesOpen = !translateNamesOpen"
              >{{ translateNamesOpen ? 'Hide' : 'Help Translate' }}
            </el-button>
          </div>
          <div style="flex: 1" />
          <div>
            <span class="rarity">{{ currentRarity }}</span>
          </div>
        </div>
        <CardNameTranslator v-if="translateNamesOpen" :card="card" />
        <div class="stats-and-stuff">
          <div class="flex-center stats-and-stuff-flex">
            <div class="flex-center gaps" style="flex-wrap: wrap">
              <CardText class="element" :text="card.element" />
              <StatValue type="ex" :value="card.ex" />
              <template v-if="isCharacter">
                <StatValue type="dmg" :value="card.dmg" style="margin-left: 0.75rem" />
                <StatValue type="ap" :value="card.ap" />
                <StatValue type="dp" :value="card.dp" />
                <StatValue type="sp" :value="card.sp" />
              </template>
              <span class="cost">
                <span style="margin-left: 0.75rem">Cost:{{ ' ' }}</span>
                <CardText class="cost" :text="card.cost"
              /></span>
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
      </div>
      <div class="card-description-container">
        <div class="card-description" v-if="hasCardDescription">
          <div v-if="shouldShowSuggestion"><em>(Unapproved translation)</em></div>
          <CardDescription :translation="cardText" />
          <CardTranslator v-if="translateMode" :card="card" :id="this.card.id" />
        </div>
        <div style="flex: 1" />
        <div class="show-when-hovering" style="text-align: right">
          <span
            v-if="isLocaleJapanese"
            title="Please switch the site language to English on the top right before translating."
            >Suggest Translation</span
          >
          <span v-else class="clickable" @click="onSuggestTranslationClick"
            >{{ translateMode ? 'Collapse' : '' }} Suggest Translation</span
          >
          -
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
import cardMixin from '../../utils/cardMixin';
import { getCurrentVariant } from '../../utils/cardVariant';
import formatCardMixin from '../../utils/formatCardMixin';
import { areaType, characterType, eventType, itemType } from '../../value/cardType';
import ExternalLink from '../common/ExternalLink.vue';
import FlagImage from '../common/FlagImage.vue';
import CardDescription from './CardDescription.vue';
import CardNameTranslator from './CardNameTranslator.vue';
import CardText from './CardText.vue';
import CardThumbnail from './CardThumbnail.vue';
import CardTranslator from './CardTranslator.vue';
import StatValue from './StatValue.vue';

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
  mixins: [formatCardMixin, cardMixin],
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
    currentVariant() {
      return getCurrentVariant(this.card.id);
    },
    currentRarity() {
      const index = this.card.variants.findIndex(obj => obj.variant === this.currentVariant);
      return this.card.variants[index].rarity;
    },
    locale() {
      return this.localLocale;
    },
    ...mapComputed('auth', ['user']),
  },
  methods: {
    onSuggestTranslationClick() {
      if (this.user) {
        this.translateMode = !this.translateMode;
      } else {
        console.log('Navigating to /login');
        window.location.href = '/login';
      }
    },
  },
  created() {
    this.localLocale = this.card.translation ? 'en' : 'ja';
  },
};
</script>

<style scoped lang="scss">
@import '../../../sass/variables.module';

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
  display: inline-block;
  width: 7rem;
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

$smallScreenLimit: 600;

@media screen and (max-width: #{$smallScreenLimit}px) {
  .card-id,
  .card-name,
  .card-ability-name,
  .card-character-type {
    display: block;
  }

  .normal-screen-separator {
    display: none;
  }

  .cost {
    margin-top: 0.5rem;
  }

  .stats-and-stuff-flex {
    flex-direction: column;
    gap: 0.25rem;
  }
}

.card-list-item-inner {
  display: grid;
  grid-template-columns: 140px 1fr;
}

.card-thumbnail {
  grid-column-start: 1;
  grid-column-end: 2;
  grid-row-start: 1;
  grid-row-end: 3;

  @include not-for-tablet-landscape-up {
    grid-row-end: 2;
  }
}

.card-details {
  grid-column-start: 2;
  grid-row-end: 2;

  @include not-for-tablet-landscape-up {
    grid-row-end: 2;
  }
}

.card-description-container {
  grid-row-start: 2;
  grid-column-start: 2;

  @include not-for-tablet-landscape-up {
    grid-column-start: 1;
    grid-column-end: 3;
    grid-row-start: 3;
  }
}
</style>
