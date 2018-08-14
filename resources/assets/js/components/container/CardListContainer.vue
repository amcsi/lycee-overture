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
    created() {
      this.doInitialCardTasks(this.$route.query).then(() => this.initialCardTasksDone = true);
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
