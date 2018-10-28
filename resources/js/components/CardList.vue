<template>
    <div>
        <div v-if="cards">
            <h3>
                Total: {{ cards.meta.pagination.total }}.
                <span v-show="cards.meta.pagination.total > 0">
                    Fully translated: {{ statistics.translated_cards }}
                    ({{ getPercentOfRatio(statistics.fully_translated_ratio) }}).
                    Text translation percent: {{ getPercentOfRatio(statistics.kanji_removal_ratio) }}.
                </span>
            </h3>

            <Paginator :pagination="cards.meta.pagination" @page-change="pageChange" />

            <div class="card-list" v-loading="cardsLoading">
                <CardListItem v-for="card in cards.data" :card="card" :key="card.id"></CardListItem>
            </div>

            <Paginator :pagination="cards.meta.pagination" @page-change="pageChange" />
        </div>
        <div v-else v-loading="cardsLoading" style="height: 300px;"></div>
    </div>
</template>

<script>
  import { mapState } from 'vuex';
  import CardListItem from './card/CardListItem';
  import Paginator from './common/Paginator';

  /** @class CardList */
  export default {
    components: { CardListItem, Paginator },
    computed: {
      ...mapState({
        cardsLoading: state => state.cards.listLoading || state.statistics.statisticsLoading,
        cards: state => state.cards.list,
        statistics: state => state.statistics.statistics,
      }),
    },
    methods: {
      pageChange(page) {
        const query = { ...this.$route.query, page };
        this.$router.push({ query });
      },
      getPercentOfRatio(ratio) {
        return new Intl.NumberFormat({
          maximumFractionDigits: 3,
          style: 'percent',
        }).format(ratio * 100) + '%';
      },
    },
  };
</script>

<style scoped lang="scss">
    .el-card {
        // For the thumbnails.
        overflow: visible;
    }
</style>
