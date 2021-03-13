<template>
  <CardListContainer>
    <CardFilters />
    <CardList />
  </CardListContainer>
</template>

<script>
import { mapMutations, mapState } from 'vuex';
import CardFilters from '../components/card/CardFilters';
import CardList from '../components/CardList';
import CardListContainer from '../components/container/CardListContainer';

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
  components: { CardList, CardFilters, CardListContainer },
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
  mounted() {
    const listener = event => {
      if (this.dirtyTranslationCardIds.length) {
        event.preventDefault();
        event.returnValue = '';
        return '';
      }
    };
    window.addEventListener('beforeunload', listener);
    this.$once('destroy', () => {
      window.removeEventListener('beforeunload', listener);
    });
  },
  beforeRouteUpdate: beforeRouteLeave,
  beforeRouteLeave,
};
</script>

<style scoped></style>
