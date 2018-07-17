<template>
    <div>
        <h2>Card list</h2>
        <div v-if="cards">
            <h3>Total: {{ cards.meta.pagination.total }}</h3>

            <Paginator :pagination="cards.meta.pagination" @page-change="pageChange" />

            <el-table
                v-loading="cardsLoading"
                :data="cards.data"
            >
                <el-table-column
                    prop="id"
                    label="ID"
                    width="100"
                >
                </el-table-column>
                <el-table-column
                    prop="translation.name"
                    label="Name"
                    width="200"
                >
                </el-table-column>
                <el-table-column
                    label="Text"
                >
                    <template slot-scope="scope">
                        <div>
                            <span v-if="scope.row.translation.ability_cost">{{ scope.row.translation.ability_cost }}:</span>
                            {{ scope.row.translation.ability_description }}
                        </div>
                    </template>
                </el-table-column>
                <el-table-column
                    prop="ex"
                    label="Ex"
                    width="50"
                >
                </el-table-column>
                <el-table-column
                    prop="dmg"
                    label="DMG"
                    width="60"
                >
                </el-table-column>
                <el-table-column
                    prop="ap"
                    label="AP"
                    width="50"
                >
                </el-table-column>
                <el-table-column
                    prop="dp"
                    label="DP"
                    width="50"
                >
                </el-table-column>
                <el-table-column
                    prop="sp"
                    label="SP"
                    width="50"
                >
                </el-table-column>
            </el-table>

            <Paginator :pagination="cards.meta.pagination" @page-change="pageChange" />
        </div>
        <div v-else v-loading="cardsLoading" style="height: 300px;"></div>
    </div>
</template>

<script>
  import { mapActions, mapState } from 'vuex';
  import Paginator from './common/Paginator';

  /** @class Cards */
  export default {
    components: { Paginator },
    beforeRouteEnter(to, from, next) {
      next(vm => vm.listCards(to.query.page));
    },
    computed: {
      ...mapState({
        cardsLoading: state => state.cards.listLoading,
        cards: state => state.cards.list,
      }),
    },
    methods: {
      ...mapActions({
        listCards: 'cards/listCards',
      }),
      pageChange(page) {
        this.$router.push({ path: '/cards', query: { page } });
      },
    },
    watch: {
      '$route.query.page'() {
        this.listCards(this.$route.query.page);
      },
    },

  };
</script>

<style scoped>

</style>
