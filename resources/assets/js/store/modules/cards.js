import { listCards } from '../../api/endpoints/cards';

export default {
  namespaced: true,
  state: {
    listLoading: false,
    list: null,
  },
  mutations: {
    CARDS_LOADING(state) {
      state.listLoading = true;
    },
    CARDS_LOADED(state, cardsResponse) {
      state.list = cardsResponse;
      state.listLoading = false;
    },
    CARDS_LOADING_FAILED(state) {
      state.listLoading = false;
    },
  },
  actions: {
    async listCards({ commit }, page) {
      commit('CARDS_LOADING');
      try {
        const cards = await listCards(page);
        commit('CARDS_LOADED', cards);
      } catch (e) {
        commit('CARDS_LOADING_FAILED');
      }
    },
  },
};
