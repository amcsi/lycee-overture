<template>
    <el-table
        v-if="cards"
        :data="cards.data"
    >
        <el-table-column
            prop="id"
            label="ID"
        >
        </el-table-column>
        <el-table-column
            prop="translation.name"
            label="Name"
        >
        </el-table-column>
        <el-table-column
            prop="ex"
            label="Ex"
        >
        </el-table-column>
        <el-table-column
            prop="dmg"
            label="DMG"
        >
        </el-table-column>
        <el-table-column
            prop="ap"
            label="AP"
        >
        </el-table-column>
        <el-table-column
            prop="dp"
            label="DP"
        >
        </el-table-column>
        <el-table-column
            prop="sp"
            label="SP"
        >
        </el-table-column>
    </el-table>
    <div v-else>Loading...</div>
</template>

<script>
  import { mapActions, mapState } from 'vuex';

  /** @class Cards */
  export default {
    components: {},
    beforeRouteEnter(to, from, next) {
      next(vm => vm.listCards());
    },
    computed: {
      ...mapState({
        cards: state => state.cards.list,
      }),
    },
    methods: {
      ...mapActions({
        listCards: 'cards/listCards',
      }),
    },
  };
</script>

<style scoped>

</style>
