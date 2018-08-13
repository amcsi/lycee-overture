import Vue from 'vue';
import Vuex from 'vuex';
import cards from './modules/cards';
import cardSets from './modules/cardSets';
import statistics from './modules/statistics';

/**
 * In this file we instantiate the store with configuration and return it.
 *
 * We also add an axios interceptor to log out the user if any response says that his token expired.
 */

Vue.use(Vuex);

const store = new Vuex.Store({
  modules: {
    cards,
    cardSets,
    statistics,
  },
  state: {
    startedInitialTasks: false,
  },
  mutations: {
    STARTED_INITIAL_TASKS(state) {
      state.startedInitialTasks = true;
    },
  },
  actions: {
    async doInitialCardTasks({ commit, dispatch, state }) {
      if (!state.startedInitialTasks) {
        commit('STARTED_INITIAL_TASKS');
        await Promise.all([
          dispatch('cards/listCards'),
          dispatch('cardSets/listCardSets'),
          dispatch('statistics/fetchStatistics'),
        ]);
      }
    },
  },
});

export default store;
