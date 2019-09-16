<template>
    <el-card class="card-list-item">
        <div class="card-list-item-inner">
            <div class="language-selector" v-if="showLanguageSelectors">
                <span
                    class="language-link clickable"
                    tabindex="0"
                    :class="{ active: localLocale !== 'ja' }"
                    @click="localLocale = 'en'"
                >
                    <img src="../../../images/flags/gb.png" alt="English" title="English" />
                </span>
                <span
                    class="language-link clickable"
                    tabindex="0"
                    :class="{ active: localLocale === 'ja' }"
                    @click="localLocale = 'ja'"
                >
                    <img src="../../../images/flags/jp.png" alt="日本語" title="日本語" />
                </span>
            </div>
            <CardThumbnail class="card-thumbnail" :id="card.id" />
            <div class="card-details">
                <div class="names-and-type">
                    <span class="card-id">{{ card.id }}</span>
                    <span class="card-name">{{ cardText.name }}</span>
                    <span v-if="isCharacter">
                        - <span class="card-ability-name">{{ cardText.ability_name }}</span>
                        <span class="card-character-type" v-if="characterType">- Type: {{ cardText.character_type || '-' }}</span>
                    </span>
                </div>
                <div class="stats-and-stuff">
                    <CardText class="element" :text="card.element" />
                    <span class="stat ex">{{ card.ex }} EX</span>
                    <template v-if="isCharacter">
                        <span class="stat dmg">{{ card.dmg }} DMG</span>
                        <span class="stat ap">{{ card.ap }} AP</span>
                        <span class="stat dp">{{ card.dp }} DP</span>
                        <span class="stat sp">{{ card.sp }} SP</span>
                    </template>
                    Cost:
                    <CardText class="cost" :text="card.cost" />
                </div>
                <div class="card-description" v-if="hasCardDescription">
                    <CardDescription :translation="cardText" />
                </div>
            </div>
        </div>
    </el-card>
</template>

<script>
import CardDescription from './CardDescription';
import CardText from './CardText';
import CardThumbnail from './CardThumbnail';

/** @class CardListItem */
  export default {
    name: 'CardListItem',
    components: { CardText, CardDescription, CardThumbnail },
    props: {
      card: {
        type: Object,
        required: true,
      },
    },
  data() {
    return {
      localLocale: null,
    };
  },
    computed: {
      isCharacter() {
        return this.card.type === 0;
      },
      characterType() {
        if (!this.isCharacter) {
          return '';
        }
        const characterType = this.cardText.character_type.trim();
        if (characterType.length <= 1) {
          // Empty or just a dash; return empty string.
          return '';
        }
        return characterType;
      },
      hasCardDescription() {
        const translation = this.cardText;
        return translation.ability_cost.trim().length > 1 || translation.ability_description.trim().length > 1 || translation.comments.trim().length > 1;
      },
      cardText() {
        if (!this.card.translation) {
          return this.card.japanese;
        }
        return this.localLocale === 'ja' ? this.card.japanese : this.card.translation;
      },
      showLanguageSelectors() {
        return !!this.card.translation;
      },
    },
  created() {
    this.localLocale = this.card.translation ? 'en' : 'ja';
  },
  };
</script>

<style scoped lang="scss">

    .card-list-item {
        position: relative;
        margin-bottom: .5rem;
    }

    .card-list-item-inner {
        display: flex;
        flex-direction: row;
    }

    .card-thumbnail {
        margin-left: -1rem;
        padding-left: 1rem;
        padding-right: 1rem;
    }

    .card-details {
        flex: 1;
    }

    .names-and-type {
        margin-bottom: .5rem;
        color: gray;
    }

    .card-id {
        font-weight: bold;
        font-size: 1.2em;
        color: #555555;
    }

    .stats-and-stuff {
        margin-bottom: .5rem;
    }

    .element {
        font-size: 1.5em;
        vertical-align: middle;
    }

    .stat {
        display: inline-block;
        color: white;
        padding: .3rem .4rem;
        font-size: .75rem;
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

    .ex {
        background-color: #333333;
    }

    .dmg {
        background-color: rgba(0, 128, 0, 1);
    }

    .ap {
        background-color: rgba(255, 0, 0, 1);
    }

    .dp {
        background-color: rgba(0, 0, 255, 1);
    }

    .sp {
        background-color: rgb(255, 146, 35);
    }

    .card-description {
        border-top: 1px dashed #a4a4a4;
        padding-top: .5rem;
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
</style>
