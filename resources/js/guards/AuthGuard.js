// authGuard.js
import store from '../store'

export default async (to, from) => {
    // Check for routes requiring authentication
    console.log(Date.now() % 10000, 'AuthGuard');
    if ((to.meta.requiresAuth || to.meta.requiresAbility) && !store.getters['auth/isAuthenticated']) {
            return {
                name: 'login',
                query: {redirect: to.fullPath},
            };
    }
    if (to.meta.requiresAbility && !store.getters['auth/hasAbility'](to.meta.requiresAbility)) {
        return {
            name: 'error.403',
        };
    }
};
