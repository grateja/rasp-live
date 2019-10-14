import Vuex from 'vuex';
import Vue from 'vue';
Vue.use(Vuex);

import auth from './modules/auth.js';
import client from './modules/client.js';
import account from './modules/account.js';

export default new  Vuex.Store({
    state: {
        currentUser: null,
        activeBranch: null,
        flashMessage: null
    },
    getters: {
        getCurrentUser(state) {
            return state.currentUser;
        },
        getActiveBranch(state) {
            return state.activeBranch;
        },
        getFlashMessage(state) {
            return state.flashMessage;
        }
    },
    actions: {
        setAuth(context, data) {
            context.commit('setUser', data);
            if(data.retainToken) {
                localStorage.setItem('token', data.token.accessToken);
            } else {
                localStorage.removeItem('token');
            }
        }
    },
    mutations: {
        setUser(state, data) {
            // console.log('set user asfsdf sf a', data.user)
            state.currentUser = data.user;
            window.axios.defaults.headers.common['Authorization'] = `Bearer ${data.token.accessToken}`;
        },
        updateEmail(state, data) {
            state.currentUser.email = data.email;
        },
        clearUser(state) {
            state.currentUser = null;
        },
        clearToken(state) {
            window.axios.defaults.headers.common['Authorization'] = null;
            localStorage.removeItem('token');
        },
        updateName(state, user) {
            state.currentUser.fullname = user.fullname;
        },
        clearActiveBranch(state) {
            state.activeBranch = null;
        },
        setFlash(state, config) {
            state.flashMessage = config;
        }
    },
    modules: {
        auth,
        client,
        account
    }
});
