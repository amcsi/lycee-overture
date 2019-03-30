import { listCardSets } from '../../api/endpoints/cardSets';

export default {
  namespaced: true,
  state: {
    listLoading: false,
    list: null,
  },
  mutations: {
    CARD_SETS_LOADING(state) {
      state.listLoading = true;
    },
    CARD_SETS_LOADED(state, cardsResponse) {
      state.list = cardsResponse;
      state.listLoading = false;
    },
    CARD_SETS_LOADING_FAILED(state) {
      state.listLoading = false;
    },
  },
  actions: {
    async listCardSets({ commit }) {
      commit('CARD_SETS_LOADING');
      try {
        const cards = await listCardSets();
        commit('CARD_SETS_LOADED', cards);
      } catch (e) {
        commit('CARD_SETS_LOADING_FAILED');
        throw e;
      }
    },
  },
};
