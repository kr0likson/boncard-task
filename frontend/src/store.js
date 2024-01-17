// store.js
import {createStore} from 'vuex';
import axios from 'axios'
export default createStore({
    state: {
        isLoggedIn: false,
        user: null,
        token: null
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
            try {
                const response = await axios.post('/api/login', userData);
                commit('setLoggedIn', true);
                commit('setUser', response.data.user);
                commit('setToken', response.data.token);
                return response.data;
            } catch (error) {
                console.error('Login failed:', error.response.data);
                throw error;
            }
        },
        async logout({commit}, userData) {
            const response = await  axios.post('/api/logout', userData);
            commit('setLoggedIn', false);
            commit('setUser', null);
        },
        async register({commit}, userData) {
            try {
                const response = await axios.post('/api/register', userData);
                console.log('Registration successful:', response);
                return response.data;
            } catch (error) {
                console.error('Registration failed:', error.response || error);
            }
        },
    }
});
