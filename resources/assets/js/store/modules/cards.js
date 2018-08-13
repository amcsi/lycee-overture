import { listCards } from '../../api/endpoints/cards';

export default {
  namespaced: true,
  state: {
    listLoading: false,
    list: null,
    loadedInitial: false,
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
    async listCards({ commit }, query) {
      commit('CARDS_LOADING');
      try {
        const cards = await listCards(query);
        commit('CARDS_LOADED', cards);
      } catch (e) {
        commit('CARDS_LOADING_FAILED');
      }
    },
  },
};
