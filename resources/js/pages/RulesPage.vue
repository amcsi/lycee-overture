<template>
  <div>
    <h1>{{ $t('nav.rules') }}</h1>

    <v-row>
      <v-col cols="2">
        <v-list nav>
          <v-list-item to="/rules/deck-and-card" :title="$t('rules.deckAndCardsRulesTitle')">
          </v-list-item>
          <v-list-item to="/rules/field" :title="$t('rules.boardRulesTitle')"> </v-list-item>
          <v-list-item to="/rules/cost" :title="$t('rules.costRulesTitle')"> </v-list-item>
          <v-list-item to="/rules/flow" :title="$t('rules.flowOfGameRules.navTitle')">
          </v-list-item>
          <v-list-item to="/rules/turn" :title="$t('rules.turnRulesTitle')"> </v-list-item>
          <v-list-item to="/rules/battle" :title="$t('rules.battleRulesTitle')"> </v-list-item>
          <v-list-item
            to="/rules/basic-abilities-and-the-stack"
            :title="$t('rules.basicAbilityRulesTitle')"
          >
          </v-list-item> </v-list
      ></v-col>

      <v-col>
        <div style="max-width: 1120px; margin: auto">
          <router-view></router-view>
        </div>
      </v-col>
    </v-row>
  </div>
</template>

<script>
import BasicAbilityRules from '../components/rules/BasicAbilityRules.vue';
import BattleRules from '../components/rules/BattleRules.vue';
import CostRules from '../components/rules/CostRules.vue';
import DeckAndCardRules from '../components/rules/DeckAndCardRules.vue';
import FieldRules from '../components/rules/FieldRules.vue';
import FlowOfGameRules from '../components/rules/FlowOfGameRules.vue';
import TurnRules from '../components/rules/TurnRules.vue';
import AMenu from '../components/ui/AMenu.vue';
import AMenuItem from '../components/ui/AMenuItem.vue';

/** @class RulesPage */
export default {
  name: 'RulesPage',
  components: {
    AMenu,
    AMenuItem,
    BasicAbilityRules,
    BattleRules,
    TurnRules,
    FlowOfGameRules,
    CostRules,
    FieldRules,
    DeckAndCardRules,
  },
};
</script>

<style scoped></style>
