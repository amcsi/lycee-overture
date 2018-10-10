import api from '../../api/index';

export default {
  namespaced: true,
  state: {
    lastStatisticsParams: null,
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
    STATISTICS_SET_LAST_PARAMS(state, stringifiedParams) {
      state.lastStatisticsParams = stringifiedParams;
    },
  },
  actions: {
    async fetchStatistics({ commit, state }, query) {
      const params = { ...query };
      // The page doesn't matter when getting statistics.
      delete params.page;

      const stringifiedParams = JSON.stringify(params);

      // If e.g. only a page change happened, then the statistics would not need to be reloaded.
      if (stringifiedParams !== state.lastStatisticsParams) {
        commit('STATISTICS_LOADING');
        try {
          const statistics = (await api.get('/statistics', { params })).data.data;
          commit('STATISTICS_SET_LAST_PARAMS', stringifiedParams);
          commit('STATISTICS_LOADING_SUCCEEDED', statistics);
        } catch (e) {
          commit('STATISTICS_LOADING_FAILED');
        }
      }
    },
  },
};
