import Vue from 'vue';
import Vuex from 'vuex';
import cards from './modules/cards';
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
    statistics,
  },
});

export default store;
