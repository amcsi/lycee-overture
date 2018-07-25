<template>
    <span>
        <span v-if="basicAbilities" class="basic-abilities" v-html="basicAbilities"></span>
        <br v-if="basicAbilities" />
        <span v-if="abilityCost" class="ability-cost" v-html="abilityCost"></span>
        <span v-if="abilityCost">: </span>
        <span class="ability-description" v-html="abilityDescription"></span>
        <br v-if="comments" />
        <span v-if="comments" class="comments" v-html="comments"></span>
    </span>
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
      basicAbilities() {
        return formatCardText(this.translation.basic_abilities);
      },
      abilityCost() {
        return formatCardText(this.translation.ability_cost);
      },
      abilityDescription() {
        return formatCardText(this.translation.ability_description);
      },
      comments() {
        return formatCardText(this.translation.comments);
      },
    },
    methods: {},
  };
</script>

<style scoped>
    .basic-abilities {
        color: #49a97b;
    }
</style>
