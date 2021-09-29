import { createStore } from "vuex";

const store = createStore({
  state() {
    return {
      user: JSON.parse(window.localStorage.getItem('USER')) || null,
    }
  },
  mutations: {
    [SET_USER] (state, user) {
      state.user = user;
    }
  },
  getters: {},
  actions: {
    setUser({commit}, user) {
      commit(SET_USER, user)
    }
  },
  modules: {}
});

export default store;
