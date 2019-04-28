<template>
    <div v-if="!listLoading">
        <slot :decks="list"></slot>
    </div>
    <div v-else v-loading="true" style="height: 300px;"></div>
</template>

<script>
import { mapActions, mapState } from 'vuex';

/** @class DeckContainer */
export default {
  name: 'DeckContainer',
  computed: {
    ...mapState('decks', ['listLoading', 'list']),
  },
  methods: {
    ...mapActions('decks', ['listDecks']),
  },
  created() {
    this.listDecks();
  },
};
</script>

<style scoped>

</style>
