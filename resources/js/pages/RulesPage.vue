<template>
    <div>
        <h1>{{ $t('nav.rules') }}</h1>

        <DeckAndCardRules />
        <FieldRules />
        <CostRules />
        <FlowOfGameRules />
        <TurnRules />
        <BattleRules />
        <BasicAbilityRules />
    </div>
</template>

<script>

  import BasicAbilityRules from '../components/rules/BasicAbilityRules';
  import BattleRules from '../components/rules/BattleRules';
  import CostRules from '../components/rules/CostRules';
  import DeckAndCardRules from '../components/rules/DeckAndCardRules';
  import FieldRules from '../components/rules/FieldRules';
  import FlowOfGameRules from '../components/rules/FlowOfGameRules';
  import TurnRules from '../components/rules/TurnRules';

  /** @class RulesPage */
  export default {
    name: 'RulesPage',
    components: { BasicAbilityRules, BattleRules, TurnRules, FlowOfGameRules, CostRules, FieldRules, DeckAndCardRules },
  };
</script>

<style scoped>
</style>
