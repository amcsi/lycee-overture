export default {
  namespaced: true,
  state: {
    dirtyTranslationCardIds: [],
  },
  mutations: {
    ADD_DIRTY_CARD_ID(state, cardId) {
      if (!state.dirtyTranslationCardIds.includes(cardId)) {
        state.dirtyTranslationCardIds.push(cardId);
      }
    },
    REMOVE_DIRTY_CARD_ID(state, cardId) {
      const index = state.dirtyTranslationCardIds.indexOf(cardId);
      if (index >= 0) {
        state.dirtyTranslationCardIds.splice(index, 1);
      }
    },
    CLEAR_ALL_DIRTY(state) {
      state.dirtyTranslationCardIds = [];
    },
  },
};
