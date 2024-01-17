// store.js
import {createStore} from 'vuex';
import axios from 'axios'
export default createStore({
    state: {
        isLoggedIn: false,
        user: null,
        token: null,
    },
    mutations: {
        setLoggedIn(state, payload) {
            state.isLoggedIn = payload;
        },
        setUser(state, payload) {
            state.user = payload;
        },
        setToken(state, payload) {
            state.token = payload;
        },
    },
    actions: {
        async login({commit}, userData) {
            const response = await axios.post('/api/login', userData);
            commit('setLoggedIn', true);
            commit('setUser', response.data.user);
            commit('setToken', response.data.token);
            return response.data;
        },
        async logout({commit, state}) {
            const headers = {
                Authorization: `Bearer ${state.token}`,
            };
            const response = await axios.post('/api/logout', {user: state.user}, {headers});
            commit('setLoggedIn', false);
            commit('setUser', null);
            commit('setToken', null);
        },
        async register({commit}, userData) {
            const response = await axios.post('/api/register', userData);
            console.log('Registration successful:', response);
            return response.data;
        },
    }
});
