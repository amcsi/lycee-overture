<template>
    <CardListContainer>
        <CardListPrint v-loading="printListLoading" :cards="printList" />
    </CardListContainer>
</template>

<script>
  import { mapActions, mapState } from 'vuex';
  import CardListPrint from '../components/CardListPrint';
  import CardListContainer from '../components/container/CardListContainer';

  /** @class CardListPrintPage */
  export default {
    name: 'CardListPrintPage',
    components: { CardListContainer, CardListPrint },
    computed: {
      ...mapState('cards', [
        'printList',
        'printListLoading',
      ]),
    },
    methods: {
      ...mapActions('cards', [
        'listCardsForPrinting',
      ]),
    },
    mounted() {
      this.listCardsForPrinting(this.$route.query);
    },
  };
</script>

<style scoped>

</style>
