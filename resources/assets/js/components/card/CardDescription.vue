<template>
    <div>
        <div v-if="basicAbilities" class="basic-abilities" v-html="basicAbilities"></div>
        <div v-for="[abilityCost, abilityDescription] in abilities">
            <span v-if="abilityCost" class="ability-cost" v-html="abilityCost"></span>
            <span class="ability-description" v-html="abilityDescription"></span>
        </div>
        <div v-if="comments" class="comments" v-html="comments"></div>
    </div>
</template>

<script>
  import formatCardText from '../../utils/formatCard';

  /** @class CardDescription */
  export default {
    name: 'CardDescription',
    props: {
      translation: { required: true },
    },
    computed: {
      abilities() {
        const abilityCostsSplit = this.translation.ability_cost.split('\n');
        const abilityDescriptionsSplit = this.translation.ability_description.split('\n');
        const ret = [];
        abilityDescriptionsSplit.forEach((abilityDescription, i) => {
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
        return formatCardText(this.translation.basic_abilities);
      },
      comments() {
        return formatCardText(this.translation.comments);
      },
    },
    methods: {},
  };
</script>

<style>
    .basic-abilities {
        color: #49a97b;
    }

    .ability-cost {
        color: cornflowerblue;
    }

    /*noinspection CssUnusedSymbol*/
    .target {
        color: #e39000;
    }
</style>
