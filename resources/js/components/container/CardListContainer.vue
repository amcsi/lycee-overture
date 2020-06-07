<template>
    <div v-if="initialCardTasksDone">
        <slot></slot>
    </div>
    <div v-else v-loading="true" style="height: 300px;"></div>
</template>

<script>
import { mapActions } from 'vuex';
import CardFilters from '../card/CardFilters';

/**
   * Wrapper for card lists
   *
   * @class CardListContainer
   **/
  export default {
    name: 'CardListContainer',
    components: { CardFilters },
    data() {
      return {
        initialCardTasksDone: false,
      };
    },
  async created() {
    await this.doInitialCardTasks(this.$route.query).then(() => this.initialCardTasksDone = true);
    },
    methods: {
      ...mapActions({
        doInitialCardTasks: 'doInitialCardTasks',
        listCardsAndFetchStatistics: 'listCardsAndFetchStatistics',
      }),
    },
    watch: {
      '$route.query': {
        immediate: true,
        async handler() {
          await this.listCardsAndFetchStatistics(this.$route.query);
        },
      },
    },
  };
</script>

<style scoped>

</style>
