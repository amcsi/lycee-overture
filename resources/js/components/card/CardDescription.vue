<template>
    <div>
        <CardText
            v-if="translation.basic_abilities"
            class="basic-abilities"
            :text="translation.basic_abilities"
        ></CardText>
        <div v-if="translation.pre_comments" class="comments">
            <CardText :text="translation.pre_comments" />
        </div>
        <div v-for="[abilityCost, abilityDescription] in abilities">
            <CardText v-if="abilityCost" class="ability-cost" :text="abilityCost" />
            <CardText class="ability-description" :text="abilityDescription" />
        </div>
        <div v-if="translation.comments" class="comments">
            <CardText :text="translation.comments" />
        </div>
    </div>
</template>

<script>
import CardText from './CardText';

/** @class CardDescription */
  export default {
    name: 'CardDescription',
    components: { CardText },
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
          ret.push([abilityCost, abilityDescription]);
        });
        return ret;
      },
    },
    methods: {},
  };
</script>
