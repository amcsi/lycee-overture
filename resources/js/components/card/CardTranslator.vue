<template>
    <div>
        <h3>Original</h3>

        <CardDescription :translation="card.japanese" />

        <div v-if="card.auto_translation">
            <h3>Auto Translated</h3>

            <CardDescription :translation="card.auto_translation" />
        </div>

        <h3>Manual Translation</h3>

        <el-input type="textarea" v-model="currentTranslation.ability_description"></el-input>
        <div class="spacer" />

        <h3>Preview</h3>

        <CardDescription :translation="currentTranslation" />

        <div class="spacer" />

        <el-button type="primary">Suggest Translation</el-button>
        <el-button @click="toAutoTranslated">Revert to Auto-Translated</el-button>

        <div class="spacer" />
    </div>
</template>

<script>
import CardDescription from './CardDescription';

/** @class CardTranslator */
export default {
  name: 'CardTranslator',
  components: { CardDescription },
  props: {
    id: String,
    card: Object,
  },
  data() {
    return {
      currentTranslation: {},
    };
  },
  computed: {
    autoTranslated() {
      return this.card.auto_translation || this.card.translation;
    },
  },
  methods: {
    toAutoTranslated() {
      this.currentTranslation = { ...this.autoTranslated };
    },
  },
  watch: {
    'card.translation': {
      immediate: true,
      handler() {
        this.currentTranslation = { ...this.card.translation };
      },
    },
  },
};
</script>

<style scoped lang="scss">
.spacer {
    height: 1rem;
}
</style>
