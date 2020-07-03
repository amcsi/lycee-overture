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
                <el-form-item label="Pre-comments" v-if="relevantPropertyMap.preComments">
                    <el-input
                        type="textarea"
                        v-model="currentTranslation.preComments"
                        placeholder="E.g. Equip Restriction: ..."
                    ></el-input>
                </el-form-item>
                <el-form-item label="Basic abilities" v-if="relevantPropertyMap.basicAbilities">
                    <el-input
                        type="textarea"
                        v-model="currentTranslation.basicAbilities"
                        placeholder="Basic abilities"
                    ></el-input>
                </el-form-item>
                <div v-if="relevantPropertyMap.abilityDescriptionLines">
                    <div v-for="(n, i) in lineCount">
                        <el-form-item
                            :label="`Ability Cost ${n}`"
                            v-if="i in currentTranslation.abilityCostLines"
                        >
                            <el-input
                                type="textarea"
                                v-model="currentTranslation.abilityCostLines[i]"
                                placeholder="E.g. [0]"
                            ></el-input>
                        </el-form-item>
                        <el-form-item
                            :label="`Ability Description ${n}`"
                            v-if="i in currentTranslation.abilityDescriptionLines"
                        >
                            <el-input
                                type="textarea"
                                v-model="currentTranslation.abilityDescriptionLines[i]"
                                placeholder="Ability Description"
                            ></el-input>
                        </el-form-item>
                    </div>
                </div>

                <el-form-item label="Comments" v-if="relevantPropertyMap.comments">
                    <el-input
                        type="textarea"
                        v-model="currentTranslation.comments"
                        placeholder="E.g. Deck Restriction: ..."
                    ></el-input>
                </el-form-item>
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

        <el-button type="primary">Suggest Translation</el-button>
        <el-button @click="toAutoTranslated">Revert to Auto-Translated</el-button>

        <div class="spacer" />
    </div>
</template>

<script>
import { characterType, itemType } from '../../value/cardType';
import FlagEmoji from '../common/FlagEmoji';
import CardDescription from './CardDescription';

/** @class CardTranslator */
export default {
  name: 'CardTranslator',
  components: { FlagEmoji, CardDescription },
  props: {
    id: String,
    card: Object,
  },
  data() {
    return {
      currentTranslation: {
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
        this.currentTranslation.abilityCostLines.length,
        this.currentTranslation.abilityDescriptionLines.length,
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
      return {
        basic_abilities: this.currentTranslation.basicAbilities,
        pre_comments: this.currentTranslation.preComments,
        comments: this.currentTranslation.comments,
        ability_cost: this.currentTranslation.abilityCostLines.join('\n'),
        ability_description: this.currentTranslation.abilityDescriptionLines.join('\n'),
      };
    },
  },
  methods: {
    toAutoTranslated() {
      this.setCurrentTranslation(this.autoTranslated);
    },
    setCurrentTranslation(translation) {
      const abilityCostLines = translation.ability_cost.split('\n');
      const abilityDescriptionLines = translation.ability_description.split('\n');

      const newTranslation = {};
      newTranslation.basicAbilities = translation.basic_abilities;
      newTranslation.preComments = translation.pre_comments;
      newTranslation.comments = translation.comments;
      newTranslation.abilityCostLines = abilityCostLines;
      newTranslation.abilityDescriptionLines = abilityDescriptionLines;

      this.currentTranslation = newTranslation;
    },
  },
  watch: {
    'card.translation': {
      immediate: true,
      handler() {
        this.setCurrentTranslation(this.card.translation);
      },
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
