<template>
    <div>
        <div class="spacer" />

        <el-alert show-icon :closable="false">
            This card is currently
            <span class="translatedText" :class="{autoTranslated}">{{ autoTranslated ? 'automatically' : 'manually'}}</span>
            translated.
        </el-alert>

        <div class="spacer" />

        <el-card>
            <div slot="header">
                <FlagEmoji locale="ja" />
                Original
            </div>

            <CardDescription :translation="card.japanese" />
        </el-card>

        <div v-if="card.auto_translation">
            <div class="spacer" />

            <el-card>
                <div slot="header">
                    🤖 Auto Translated
                </div>

                <CardDescription :translation="card.auto_translation" />
            </el-card>
        </div>

        <div class="spacer" />

        <el-card>
            <div slot="header">
                <FlagEmoji locale="en" />
                Manual Translation
            </div>

            <el-form>
                <translatable-textarea
                    v-if="relevantPropertyMap.preComments"
                    v-model="currentDraft.preComments"
                    :dirty="dirtyValues.preComments"
                    label="Pre-comments"
                    placeholder="E.g. Equip Restriction: ..."
                />
                <translatable-textarea
                    v-if="relevantPropertyMap.basicAbilities"
                    v-model="currentDraft.basicAbilities"
                    :dirty="dirtyValues.basicAbilities"
                    label="Basic abilities"
                    placeholder="Basic abilities"
                />
                <div v-if="relevantPropertyMap.abilityDescriptionLines">
                    <div v-for="(n, i) in lineCount" :key="i">
                        <translatable-textarea
                            v-if="currentDraft.abilityCostLines"
                            v-model="currentDraft.abilityCostLines[i]"
                            :dirty="dirtyValues.abilityCostLines[i]"
                            :label="`Ability Cost ${n}`"
                            placeholder="E.g. [0]"
                        />
                        <translatable-textarea
                            v-if="i in currentDraft.abilityDescriptionLines"
                            v-model="currentDraft.abilityDescriptionLines[i]"
                            :dirty="dirtyValues.abilityDescriptionLines[i]"
                            :label="`Ability Description ${n}`"
                            placeholder="Ability Description"
                        />
                    </div>
                </div>

                <translatable-textarea
                    v-if="relevantPropertyMap.comments"
                    v-model="currentDraft.comments"
                    :dirty="dirtyValues.comments"
                    label="Comments"
                    placeholder="E.g. Deck Restriction: ..."
                />
            </el-form>
        </el-card>

        <div class="spacer" />

        <el-card>
            <div slot="header">
                👀 Preview
            </div>

            <CardDescription :translation="resultTranslation" />
        </el-card>

        <div class="spacer" />

        <el-button type="primary" :disabled="!dirty">Suggest Translation</el-button>
        <el-button @click="toAutoTranslated">Revert to Auto-Translated</el-button>

        <div class="spacer" />
    </div>
</template>

<script>
import { mapMutations } from 'vuex';
import { characterType, itemType } from '../../value/cardType';
import FlagEmoji from '../common/FlagEmoji';
import TranslatableTextarea from '../form/TranslatableTextarea';
import CardDescription from './CardDescription';

function draftToCardTranslation(draft) {
  return {
    basic_abilities: draft.basicAbilities,
    pre_comments: draft.preComments,
    comments: draft.comments,
    ability_cost: draft.abilityCostLines.join('\n'),
    ability_description: draft.abilityDescriptionLines.join('\n'),
  };
}

function cardTranslationToDraft(translation) {
  const abilityCostLines = translation.ability_cost.split('\n');
  const abilityDescriptionLines = translation.ability_description.split('\n');

  const draft = {};
  draft.basicAbilities = translation.basic_abilities;
  draft.preComments = translation.pre_comments;
  draft.comments = translation.comments;
  draft.abilityCostLines = abilityCostLines;
  draft.abilityDescriptionLines = abilityDescriptionLines;

  return draft;
}

/** @class CardTranslator */
export default {
  name: 'CardTranslator',
  components: { TranslatableTextarea, FlagEmoji, CardDescription },
  props: {
    id: String,
    card: Object,
  },
  data() {
    return {
      // Current draft.
      currentDraft: {
        basicAbilities: '',
        preComments: '',
        comments: '',
        abilityCostLines: [],
        abilityDescriptionLines: [],
      },
    };
  },
  computed: {
    autoTranslated() {
      return this.card.auto_translation || this.card.translation;
    },
    lineCount() {
      return Math.max(
        this.currentDraft.abilityCostLines.length,
        this.currentDraft.abilityDescriptionLines.length,
      );
    },
    relevantProperties() {
      const cardType = this.card.type;
      if (cardType === characterType) {
        return ['basicAbilities', 'comments', 'abilityCostLines', 'abilityDescriptionLines'];
      } else {
        const ret = ['comments', 'abilityDescriptionLines'];
        if (cardType === itemType) {
          ret.push('preComments');
        }
        return ret;
      }
    },
    relevantPropertyMap() {
      const ret = {};
      for (const property of this.relevantProperties) {
        ret[property] = true;
      }
      return ret;
    },
    resultTranslation() {
      return draftToCardTranslation(this.currentDraft);
    },
    lastSavedTranslationDraft() {
      return cardTranslationToDraft(this.card.translation);
    },
    // Show a dirty check per translate input.
    dirtyValues() {
      const base = this.lastSavedTranslationDraft;
      const compare = this.currentDraft;

      const changes = {};

      for (let translationKey in base) {
        const baseTranslation = base[translationKey];
        const compareTranslation = compare[translationKey];
        changes[translationKey] = Array.isArray(baseTranslation) ?
          // Recurse the array components.
          baseTranslation.map((value, key) => value !== compareTranslation[key]) :
          // Just compare the strings here.
          baseTranslation !== compareTranslation;
      }

      return changes;
    },
    dirty() {
      for (const changed of Object.values(this.dirtyValues)) {
        if (Array.isArray(changed)) {
          if (changed.some(value => value)) {
            return true;
          }
        } else if (changed) {
          return true;
        }
      }

      return false;
    },
  },
  methods: {
    ...mapMutations('translation', ['ADD_DIRTY_CARD_ID', 'REMOVE_DIRTY_CARD_ID']),
    toAutoTranslated() {
      this.setCurrentTranslation(this.autoTranslated);
    },
    setCurrentTranslation(translation) {
      this.currentDraft = cardTranslationToDraft(translation);
    },
  },
  watch: {
    'card.translation': {
      immediate: true,
      handler() {
        this.setCurrentTranslation(this.card.translation);
      },
    },
    dirty(dirty) {
      if (dirty) {
        this.ADD_DIRTY_CARD_ID(this.id);
      } else {
        this.REMOVE_DIRTY_CARD_ID(this.id);
      }
    },
  },
};
</script>

<style scoped lang="scss">
@import 'resources/sass/variables';

.spacer {
    height: 1rem;
}

.translatedText {
    font-weight: bold;
    color: $--color-primary;

    &.autoTranslated {
        color: $--color-success;
    }
}
</style>
