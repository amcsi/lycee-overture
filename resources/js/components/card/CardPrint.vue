<template>
  <div class="card-print-container" :class="{ 'with-images': withImages }">
    <div class="card-print-inner">
      <CardImage
        v-if="withImages"
        class="card-image"
        :id="card.id"
        :variant="imageVariant"
        :height="100"
        :cloudinary-height="150"
      />
      <div class="card-text">
        <div>
          <span v-if="card.translation.basic_abilities || abilities.length">
            <strong>{{ card.id }}</strong>
            <CardText
              v-if="card.translation.basic_abilities"
              class="basic-abilities"
              :text="card.translation.basic_abilities"
            />
            <CardText
              v-if="card.translation.pre_comments"
              class="comments"
              :text="card.translation.pre_comments"
            />
            <span v-for="[abilityCost, abilityDescription] in abilities">
              <CardText v-if="abilityCost" class="ability-cost" :text="abilityCost" />
              <CardText class="ability-description" :text="abilityDescription" />
            </span>
            <CardText
              v-if="card.translation.comments"
              class="comments"
              :text="card.translation.comments"
            />
          </span>
        </div>
        <div style="color: grey; font-style: italic">
          {{ card.translation.name }}
          <span v-if="card.type === 0">
            - {{ card.translation.ability_name }} - {{ card.translation.character_type }}
          </span>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { savedCardVariants } from '../../utils/cardVariant';
import CardImage from './CardImage';
import CardText from './CardText';

/**
 * Represents a card to print
 *
 * @class CardPrint
 **/
export default {
  name: 'CardPrint',
  components: { CardText, CardImage },
  props: ['card', 'withImages'],
  computed: {
    abilities() {
      const abilityCostsSplit = this.card.translation.ability_cost.split('\n');
      const abilityDescriptionsSplit = this.card.translation.ability_description.split('\n');
      const ret = [];
      abilityDescriptionsSplit.forEach((abilityDescription, i) => {
        if (!abilityDescription || abilityDescription.trim() === '-') {
          // Skip ability; no description.
          return;
        }
        let abilityCost = abilityCostsSplit[i];

        // The simplest way to check if the ability cost has a cost, but doesn't only contain the ability type;
        // check if the ability cost has a string in it. All [Activate] effects have a space in them. Free costs are [0]
        if (abilityCost && abilityCost.indexOf(' ') !== -1) {
          abilityCost += ': ';
        }
        ret.push([abilityCost, abilityDescription]);
      });
      return ret;
    },
    imageVariant() {
      return savedCardVariants.preferences[this.card.id] ?? '';
    },
  },
};
</script>

<style scoped lang="scss">
.card-text > div {
  display: inline;

  .with-images & {
    display: block;
  }
}

.with-images {
  display: inline-block;
  width: 300px;

  .card-print-inner {
    height: 100%;
    display: flex;
  }

  .card-image {
    width: 75px;
    margin-right: 3px;
  }

  .card-text {
    flex: 1;
  }
}
</style>
