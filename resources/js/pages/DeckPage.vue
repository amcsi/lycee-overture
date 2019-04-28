<template>
    <div>
        <h1>{{ $t('nav.deck') }}</h1>

        <deck-container v-slot="{ decks }">
            <el-select placeholder="-" :value="$route.params.deck" @change="deckChange">
                <el-option label="-" value=""></el-option>
                <el-option
                    v-for="deck in decks"
                    :key="deck.id"
                    :label="deck.name"
                    :value="'' + deck.id"
                ></el-option>
            </el-select>

            <router-view />

        </deck-container>
    </div>
</template>

<script>
import DeckContainer from '../components/container/DeckContainer';

/** @class DeckPage */
export default {
  name: 'DeckPage',
  components: { DeckContainer },
  methods: {
    deckChange(deck) {
      let path = '/deck';
      if (deck) {
        path += '/' + deck;
      }
      this.$router.push(path);
    },
  },
};
</script>

<style scoped>

</style>
