import Vuex from 'vuex';
import auth from './modules/auth'; // Import the auth module

export default new Vuex.Store({
    modules: {
        auth, // Register the auth module under the 'auth' namespace
    },
});
