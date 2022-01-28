<template>
  <div>
    <div v-if="cards">
      <h3>
        <span v-if="statistics" v-show="totalCards > 0">
          Fully translated: {{ statistics.translated_cards }} ({{
            getPercentOfRatio(statistics.fully_translated_ratio)
          }}). Text translation percent: {{ getPercentOfRatio(statistics.kanji_removal_ratio) }}.
        </span>
        <span v-else-if="!isLocaleJapanese" v-loading="true">&nbsp;</span>
      </h3>

      <Paginator :pagination="cards.meta" @page-change="pageChange" />

      <div class="card-list" v-loading="cardsLoading">
        <CardListItemOuter
          v-for="card in cards.data"
          :card="card"
          :key="card.id"
        ></CardListItemOuter>
      </div>

      <Paginator :pagination="cards.meta" @page-change="pageChange" />
    </div>
    <div v-else v-loading="cardsLoading" style="height: 300px"></div>
  </div>
</template>

<script>
import { mapState } from 'vuex';
import CardListItem from './card/CardListItem';
import CardListItemOuter from './card/CardListItemOuter';
import Paginator from './common/Paginator';

/** @class CardList */
export default {
  components: { CardListItem, CardListItemOuter, Paginator },
  data() {
    return {
      isLocaleJapanese: window.locale === 'ja',
    };
  },
  computed: {
    ...mapState({
      cardsLoading: state => state.cards.listLoading,
      cards: state => state.cards.list,
      statistics: state => state.statistics.statistics,
    }),
    totalCards() {
      return this.cards.meta && this.cards.meta.total;
    },
  },
  methods: {
    pageChange(page) {
      const query = { ...this.$route.query, page };
      this.$router.push({ query });
    },
    getPercentOfRatio(ratio) {
      return (
        new Intl.NumberFormat({
          maximumFractionDigits: 3,
          style: 'percent',
        }).format(ratio * 100) + '%'
      );
    },
  },
};
</script>
