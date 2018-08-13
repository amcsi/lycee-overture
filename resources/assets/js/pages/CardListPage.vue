<template>
    <div v-loading="!initialCardTasksDone">
        <CardFilters />
        <CardList />
    </div>
</template>

<script>
  import { mapActions } from 'vuex';
  import CardFilters from '../components/card/CardFilters';
  import CardList from '../components/CardList';

  /** @class CardListPage */
  export default {
    name: 'CardListPage',
    components: { CardFilters, CardList },
    data() {
      return {
        initialCardTasksDone: false,
      };
    },
    created() {
      this.doInitialCardTasks().then(() => this.initialCardTasksDone = true);
    },
    methods: {
      ...mapActions({
        doInitialCardTasks: 'doInitialCardTasks',
        listCards: 'cards/listCards',
      }),
    },
    watch: {
      '$route.query'() {
        this.listCards(this.$route.query);
      },
    },
  };
</script>

<style scoped>

</style>
