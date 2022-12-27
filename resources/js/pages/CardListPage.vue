<template>
  <div>
    <CardListContainer>
      <CardFilters />
      <CardList />
    </CardListContainer>
  </div>
</template>

<script>
import { mapMutations, mapState } from 'vuex';
import CardFilters from '../components/card/CardFilters.vue';
import CardList from '../components/CardList.vue';
import CardListContainer from '../components/container/CardListContainer.vue';

const navigateAwayMessage =
  'There are unsaved translation draft changes. Are you sure you would like to navigate away?';

function beforeRouteLeave(to, from, next) {
  if (!this.dirtyTranslationCardIds.length) {
    next();
    return;
  }

  if (!confirm(navigateAwayMessage)) {
    next(false);
    return;
  }

  // Clear the ones marked as dirty before leaving.
  this.CLEAR_ALL_DIRTY();
  next();
}

/** @class CardListPage */
export default {
  name: 'CardListPage',
  components: {
    CardList,
    CardFilters,
    CardListContainer,
  },
  computed: {
    ...mapState({
      dirtyTranslationCardIds: state => state.translation.dirtyTranslationCardIds,
    }),
  },
  methods: {
    ...mapMutations('translation', ['CLEAR_ALL_DIRTY']),
  },
  watch: {
    cards: {
      immediate: true,
      handler() {
        this.CLEAR_ALL_DIRTY();
      },
    },
  },
  listener: null,
  mounted() {
    this.listener = event => {
      if (this.dirtyTranslationCardIds.length) {
        event.preventDefault();
        event.returnValue = '';
        return '';
      }
    };
    window.addEventListener('beforeunload', this.listener);
  },
  destroyed() {
    window.removeEventListener('beforeunload', this.listener);
  },
  beforeRouteUpdate: beforeRouteLeave,
  beforeRouteLeave,
};
</script>

<style scoped></style>
