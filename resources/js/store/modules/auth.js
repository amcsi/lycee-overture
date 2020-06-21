export default {
  namespaced: true,
  state: {
    user: window.vars.auth || null,
  },
};
