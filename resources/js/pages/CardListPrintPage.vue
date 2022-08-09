<template>
  <CardListContainer>
    <CardListPrint v-loading="printListLoading" :cards="printList" />
  </CardListContainer>
</template>

<script>
import { mapActions, mapState } from 'vuex';
import CardListPrint from '../components/CardListPrint.vue';
import CardListContainer from '../components/container/CardListContainer.vue';

/** @class CardListPrintPage */
export default {
  name: 'CardListPrintPage',
  components: { CardListContainer, CardListPrint },
  computed: {
    ...mapState('cards', ['printList', 'printListLoading']),
  },
  methods: {
    ...mapActions('cards', ['listCardsForPrinting']),
  },
  mounted() {
    this.listCardsForPrinting(this.$route.query);
  },
  beforeRouteEnter(to, from, next) {
    if (window.locale === 'ja') {
      // Redirect cards; there's no need for Japanese speaker to print out translations :)
      next({ path: '/cards', query: to.query, hash: to.hash });
      return;
    }
    next();
  },
};
</script>

<style scoped></style>
