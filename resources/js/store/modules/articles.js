import { listArticles } from "../../api/endpoints/articles";

export default {
  namespaced: true,
  state: {
    articles: undefined,
  },
  mutations: {
    SET_ARTICLES(state, articles) {
      state.articles = articles;
    },
  },
  actions: {
    async loadArticles({ commit }, limit) {
      commit("SET_ARTICLES", null);
      const articles = await listArticles(limit);
      commit("SET_ARTICLES", articles || undefined);
    },
  },
};
