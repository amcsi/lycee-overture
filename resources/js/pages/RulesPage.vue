<template>
  <div>
    <h1>{{ $t('nav.rules') }}</h1>

    <el-row>
      <el-col :span="8">
        <el-menu
          router
          default-active="2"
          class="el-menu-vertical-demo"
          background-color="#545c64"
          text-color="#fff"
          active-text-color="#ffd04b"
          :default-active="$route.path"
        >
          <el-menu-item index="/rules/deck-and-card">
            <span v-t="'rules.deckAndCardsRulesTitle'"></span>
          </el-menu-item>
          <el-menu-item index="/rules/field">
            <span v-t="'rules.boardRulesTitle'"></span>
          </el-menu-item>
          <el-menu-item index="/rules/cost">
            <span v-t="'rules.costRulesTitle'"></span>
          </el-menu-item>
          <el-menu-item index="/rules/flow">
            <span v-t="'rules.flowOfGameRules.navTitle'"></span>
          </el-menu-item>
          <el-menu-item index="/rules/turn">
            <span v-t="'rules.turnRulesTitle'"></span>
          </el-menu-item>
          <el-menu-item index="/rules/battle">
            <span v-t="'rules.battleRulesTitle'"></span>
          </el-menu-item>
          <el-menu-item index="/rules/basic-abilities-and-the-stack">
            <span v-t="'rules.basicAbilityRulesTitle'"></span>
          </el-menu-item>
        </el-menu>
      </el-col>

      <el-col :span="16" class="content-column">
        <router-view></router-view> </el-col
    ></el-row>
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

/** @class RulesPage */
export default {
  name: 'RulesPage',
  components: {
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

<style scoped>
.content-column {
  padding: 0 1em;
}
</style>
