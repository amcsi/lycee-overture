import { listCards } from '../../api/endpoints/cards';

export default {
  namespaced: true,
  state: {
    listLoading: false,
    list: null,
    loadedInitial: false,
    lastPrintParams: false, // Cache key to avoid unnecessary API calls.
    printListLoading: null,
    printList: null,
    newestDate: undefined,
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
    CARDS_PRINT_LOADING(state) {
      state.printListLoading = true;
    },
    CARDS_PRINT_LOADED(state, cardsResponse) {
      state.printList = cardsResponse;
      state.printListLoading = false;
    },
    CARDS_PRINT_LOADING_FAILED(state) {
      state.printListLoading = false;
    },
    CARDS_PRINT_SET_LAST_PARAMS(state, stringifiedParams) {
      state.lastPrintParams = stringifiedParams;
    },
    CARDS_SET_NEWEST_DATE(state, newestDate) {
      state.newestDate = newestDate;
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
    async listCardsForPrinting({ commit, state }, queryInput) {
      // Manipulate query to remove the page and make sure the limit is 60.
      const query = { ...queryInput, limit: 60 };
      delete query.page;

      const stringifiedParams = JSON.stringify(query);

      if (stringifiedParams === state.lastPrintParams) {
        // Nothing to do.
        return;
      }

      commit('CARDS_PRINT_LOADING');
      try {

        const cards = (await listCards(query)).data.sort(
          // Ensure cards are sorted in ID order.
          (card1, card2) => card1.id > card2.id ? 1 : -1,
        );
        commit('CARDS_PRINT_SET_LAST_PARAMS', stringifiedParams);
        commit('CARDS_PRINT_LOADED', cards);
      } catch (e) {
        commit('CARDS_PRINT_LOADING_FAILED');
      }
    },
    async loadNewestCardDate({ commit }) {
      const createdOn =
        (await listCards({ sort: 'id', desc: 1, limit: 1 })).data[0].created_at;
      commit('CARDS_SET_NEWEST_DATE', new Date(createdOn));
    },
  },
};
