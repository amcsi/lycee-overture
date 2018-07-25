<template>
    <div>
        <el-container>

            <el-header height="auto" class="container">
                <NavMenu />
                <h1>
                    Lycee Overture TCG Translation Website
                </h1>

                <h2>Card list</h2>
            </el-header>

            <el-main>

                <div v-if="cards">
                    <h3>
                        Total: {{ cards.meta.pagination.total }}.
                        Fully translated: {{ statistics.translated_cards }}
                        ({{ getPercentOfRatio(statistics.fully_translated_ratio) }}).
                        Text translation percent: {{ getPercentOfRatio(statistics.kanji_removal_ratio) }}.
                    </h3>


                    <Paginator :pagination="cards.meta.pagination" @page-change="pageChange" />

                    <el-table
                        v-loading="cardsLoading"
                        :data="cards.data"
                        row-key="id"
                    >
                        <el-table-column
                            label="Image"
                            width="130"
                        >
                            <template slot-scope="scope">
                                <CardImage :id="scope.row.id" />
                            </template>
                        </el-table-column>
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
                                <CardDescription :translation="scope.row.translation" />
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
            </el-main>
        </el-container>

    </div>
</template>

<script>
  import { mapActions, mapState } from 'vuex';
  import CardDescription from './card/CardDescription';
  import CardImage from './card/CardImage';
  import Paginator from './common/Paginator';
  import NavMenu from './NavMenu';

  /** @class CardList */
  export default {
    components: { CardImage, CardDescription, NavMenu, Paginator },
    beforeRouteEnter(to, from, next) {

      next(vm => {
        if (!vm.cards || vm.cards.meta.pagination.page !== to.query.page) {
          // Cards aren't loaded yet, or pagination shows a different page now: load the cards!
          vm.loadCards(to.query.page);
        }
      });
    },
    computed: {
      ...mapState({
        cardsLoading: state => state.cards.listLoading || state.statistics.statisticsLoading,
        cards: state => state.cards.list,
        statistics: state => state.statistics.statistics,
      }),
    },
    methods: {
      ...mapActions({
        listCards: 'cards/listCards',
        fetchStatistics: 'statistics/fetchStatistics',
      }),
      pageChange(page) {
        this.$router.push({ path: '/cards', query: { page } });
      },
      loadCards(page) {
        this.listCards(page);
        this.fetchStatistics();
      },
      getPercentOfRatio(ratio) {
        return new Intl.NumberFormat({
          maximumFractionDigits: 3,
          style: 'percent',
        }).format(ratio * 100) + '%';
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
