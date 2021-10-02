import { createStore } from "vuex";

const store = createStore({
  state() {
    return {
      user: JSON.parse(window.localStorage.getItem('USER')) || null,
    }
  },
  mutations: {
    setUser (state, user) {
      state.user = user;
    }
  },
  getters: {
    getUser (state) {
      console.log(state.user)
      return state.user
    },
    loggedIn (state, getters) {
      console.log(getters.getUser)
      return (getters.getUser !== null);
    }
  },
  actions: {
    async getUser({commit}) {
      const response = await fetch('/api/profile');
    
      if (response.status === 200) {
        const user = await response.json();

        window.localStorage.setItem('USER', JSON.stringify(user));
        commit('setUser', user);
      } else {
        commit('setUser', null);
      }
    }
  },
  modules: {}
});

export default store;
