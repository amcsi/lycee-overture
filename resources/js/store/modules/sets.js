import { listSets } from '../../api/endpoints/sets';

export default {
  namespaced: true,
  state: {
    listLoading: false,
    list: null,
  },
  getters: {
    brands({ list }) {
      if (!list) {
        return [];
      }

      function filterByUnique(value, index, self) {
        return self.indexOf(value) === index;
      }

      return list.map(({ brand }) => brand).filter(filterByUnique);
    },
    brandsMarkupRegexp(state, { brands }) {
      const pattern = `\\[(${brands.join('|')})]`;
      console.info({ pattern });
      return new RegExp(pattern);
    },
  },
  mutations: {
    SETS_LOADING(state) {
      state.listLoading = true;
    },
    SETS_LOADED(state, cardsResponse) {
      state.list = cardsResponse;
      state.listLoading = false;
    },
    SETS_LOADING_FAILED(state) {
      state.listLoading = false;
    },
  },
  actions: {
    async listSets({ commit }) {
      commit('SETS_LOADING');
      try {
        const cards = await listSets();
        commit('SETS_LOADED', cards);
      } catch (e) {
        commit('SETS_LOADING_FAILED');
        throw e;
      }
    },
  },
};
