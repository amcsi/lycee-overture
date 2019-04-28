import Vue from 'vue';
import Vuex from 'vuex';
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

const store = new Vuex.Store({
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
    startedInitialTasks: false,
    isLocaleJapanese: window.locale === 'ja',
  },
  mutations: {
    STARTED_INITIAL_TASKS(state) {
      state.startedInitialTasks = true;
    },
  },
  actions: {
    async doInitialCardTasks({ commit, dispatch, state }, query) {
      if (!state.startedInitialTasks) {
        commit('STARTED_INITIAL_TASKS');
        await Promise.all([
          // Dropdowns for filters.
          dispatch('decks/listDecks'),
          dispatch('sets/listSets'),
          // First cards.
          dispatch('listCardsAndFetchStatistics', query),
        ]);
      }
    },
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
