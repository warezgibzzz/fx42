import { createStore } from "vuex";

const store = createStore({
  strict: process.env.NODE_ENV !== 'production',
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
      return getters.getUser !== null;
    }
  },
  actions: {
    async getUser({commit}) {
      const response = await fetch('/api/profile');
    
      if (response.status === 200) {
        const user = response.json();

        commit('setUser', user);
        window.localStorage.setItem('USER', JSON.stringify(user));
        
        console.log(response);
        
        return;
      }
      commit('setUser', null);
    }
  },
  modules: {}
});

export default store;
