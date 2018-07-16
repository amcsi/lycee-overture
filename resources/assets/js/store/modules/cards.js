import { listCards } from '../../api/endpoints/cards';

export default {
  namespaced: true,
  state: {
    list: null,
  },
  mutations: {
    CARDS_LOADING(state) {
      state.list = null;
    },
    CARDS_LOADED(state, cardsResponse) {
      state.list = cardsResponse;
    },
  },
  actions: {
    async listCards({ commit }, page) {
      commit('CARDS_LOADING');
      const cards = await listCards(page);
      commit('CARDS_LOADED', cards);
    },
  },
};
