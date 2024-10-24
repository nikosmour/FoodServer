import Vuex from 'vuex';

export const state = {
    auth: { // Add the auth property here
        isLoggedIn: false,
        user: null,
    },
};

export const mutations = {
    setLogin(state, payload) {
        state.auth.user = payload.user;
        state.auth.abilities = payload.abilities;
    },
    setLogout(state) {
        state.auth.user = null;
        state.auth.abilities = null;
    },
};

export const actions = {
    async loginUser({commit}, credentials) {
        // Send login request to Laravel backend
        const response = await axios.post('/api/login', credentials).then(response => {
            return response;
        });
        commit('setLogin', response.data);
        localStorage.setItem('user', JSON.stringify(response.data.user)); // Store user data
        return true;

    },
    async getUser({state, commit}) {
        // Send login request to Laravel backend

        const data = await axios.get('/api/user').then(response => {
            return response.data;
        }).catch(error => {
            if (error.response.status === 401)
                return null;

        });
        console.log(data);

        if (data) {
            await commit('setLogin', data);
            localStorage.setItem('user', JSON.stringify(data.user)); // Store user data
            return true;
        } else {
            // dispach('logout');
            localStorage.removeItem('user'); // Store user data
            commit('setLogout');
            return false
        }
    },
    async logout({commit}) {
        await axios.post(route('logout'));
        localStorage.removeItem('user'); // Store user data
        commit('setLogout');
    },
};

export const getters = {
    isAuthenticated(state) {
        return state.auth.user != null;
    },
    currentUser(state) {
        return state.auth.user;
    },
    hasAbility(state) {
        return (ability) => {
            return state.auth.user && state.auth.abilities.includes(ability);
        };
    },

};
export default new Vuex.Store({
    state,
    mutations,
    actions,
    getters,
});
