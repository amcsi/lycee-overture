import api from '../../api';

export default {
  namespaced: true,
  state: {
    statisticsLoading: false,
    statistics: {},
  },
  mutations: {
    STATISTICS_LOADING(state) {
      state.statisticsLoading = true;
    },
    STATISTICS_LOADING_SUCCEEDED(state, statistics) {
      state.statistics = statistics;
      state.statisticsLoading = false;
    },
    STATISTICS_LOADING_FAILED(state) {
      state.statisticsLoading = false;
    },
  },
  actions: {
    async fetchStatistics({ commit }, page) {
      commit('STATISTICS_LOADING');
      try {
        const statistics = (await api.get('/statistics')).data.data;
        commit('STATISTICS_LOADING_SUCCEEDED', statistics);
      } catch (e) {
        commit('STATISTICS_LOADING_FAILED');
      }
    },
  },
};
