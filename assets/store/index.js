import { createStore } from "vuex";
import { SET_USER } from "./const.js";

const store = createStore({
  strict: process.env.NODE_ENV !== 'production',
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
