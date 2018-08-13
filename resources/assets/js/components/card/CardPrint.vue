<template>
    <div v-if="basicAbilities || abilities.length">
        <strong>{{ card.id }}</strong>
        <span v-if="basicAbilities" class="basic-abilities" v-html="basicAbilities"></span>
        <span v-for="[abilityCost, abilityDescription] in abilities">
            <span v-if="abilityCost" class="ability-cost" v-html="abilityCost"></span>
            <span class="ability-description" v-html="abilityDescription"></span>
        </span>
        <span v-if="comments" class="comments" v-html="comments"></span>
    </div>
</template>

<script>
  import formatCardText from '../../utils/formatCard';

  /**
   * Represents a card to print
   *
   * @class CardPrint
   **/
  export default {
    name: 'CardPrint',
    props: ['card'],
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

<style scoped>

</style>
