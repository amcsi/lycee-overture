<template>
    <div class="card-print-container" :class="{ 'with-images': withImages }">
        <div class="card-print-inner">
            <CardImage
                v-if="withImages"
                class="card-image"
                :id="card.id"
                :height="100"
                :cloudinary-height="150"
            />
            <div class="card-text">
                <div>
                    <span v-if="basicAbilities || abilities.length">
                        <strong>{{ card.id }}</strong>
                        <span v-if="basicAbilities" class="basic-abilities" v-html="basicAbilities"></span>
                        <span v-for="[abilityCost, abilityDescription] in abilities">
                            <span v-if="abilityCost" class="ability-cost" v-html="abilityCost"></span>
                            <span class="ability-description" v-html="abilityDescription"></span>
                        </span>
                        <span v-if="comments" class="comments" v-html="comments"></span>
                    </span>
                </div>
                <div style="color: grey; font-style: italic;">
                    {{ card.translation.name }}
                    <span v-if="card.type === 0">
                    - {{ card.translation.ability_name }}
                    - {{ card.translation.character_type }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
  import formatCardText from '../../utils/formatCard';
  import CardImage from './CardImage';

  /**
   * Represents a card to print
   *
   * @class CardPrint
   **/
  export default {
    name: 'CardPrint',
    components: { CardImage },
    props: [
      'card',
      'withImages',
    ],
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
          ret.push([formatCardText(abilityCost), formatCardText(abilityDescription)]);
        });
        return ret;
      },
      basicAbilities() {
        return formatCardText(this.card.translation.basic_abilities);
      },
      comments() {
        return formatCardText(this.card.translation.comments);
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
