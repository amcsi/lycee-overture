import Vue from 'vue';
import Vuex, { createStore } from 'vuex';
import articles from './modules/articles';
import auth from './modules/auth';
import cards from './modules/cards';
import decks from './modules/decks';
import sets from './modules/sets';
import statistics from './modules/statistics';
import translation from './modules/translation';

/**
 * In this file we instantiate the store with configuration and return it.
 *
 * We also add an axios interceptor to log out the user if any response says that his token expired.
 */

Vue.use(Vuex);

const store = createStore({
  modules: {
    articles,
    auth,
    cards,
    decks,
    sets,
    statistics,
    translation,
  },
  state: {
    isLocaleJapanese: window.locale === 'ja',
  },
  actions: {
    async listCardsAndFetchStatistics({ dispatch, state }, query) {
      const listCardsPromise = dispatch('cards/listCards', query);
      if (!state.isLocaleJapanese) {
        dispatch('statistics/fetchStatistics', query);
      }
      return listCardsPromise;
    },
  },
});

export default store;
