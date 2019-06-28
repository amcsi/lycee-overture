<template>
    <div v-if="list">
        <slot></slot>
    </div>
    <div v-else v-loading="true" style="height: 300px;"></div>
</template>

<script>
import { mapActions, mapState } from 'vuex';
import CardFilters from '../card/CardFilters';

/**
 * Wrapper for card lists
 *
 * @class CardListContainer
 **/
export default {
  name: 'CardListContainer',
  components: { CardFilters },
  created() {
    this.listCardsAndFetchStatistics(this.$route.query);
  },
  computed: {
    ...mapState('cards', ['list']),
  },
  methods: {
    ...mapActions({
      listCardsAndFetchStatistics: 'listCardsAndFetchStatistics',
    }),
  },
  watch: {
     '$route.query': {
      immediate: true,
        async handler() {await this.listCardsAndFetchStatistics(this.$route.query);
    },
  },
},
  };
</script>

<style scoped>

</style>
