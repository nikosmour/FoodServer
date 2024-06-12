<script>
import {mapActions, mapGetters} from "vuex";

export default {
    computed: {
        ...mapGetters([
            'isAuthenticated',
        ]),
    },
    methods: {
        ...mapActions([
            'getUser'
        ]),
    },
    watch: {
        isAuthenticated(newValue) {
            if (!newValue && this.$route.meta.requiresAuth)
                this.$router.push({
                    name: 'login',
                    query: {redirect: this.$route.fullPath},
                })
            else if (newValue && this.$route.name === 'login')
                this.$router.push(this.$route.query.redirect || {name: 'userProfile'});
        }
    },
    mounted() {
        let timeoutMin = process.env.MIX_SESSION_TIME_OUT;
        let timeout = (timeoutMin - 1) * 60000
        if (timeoutMin < 1)
            timeout = (timeoutMin) * 60000
        else if (timeoutMin < 2)
            timeout = 60000

        setInterval(() => {
            axios.get(route('sanctum.csrf-cookie'));
        }, timeout);
        setInterval(() => {
            if (this.isAuthenticated)
                this.getUser();
        }, timeout)
        window.axios.interceptors.response.use(
            response => response,
            error => {
                // csrf expired
                if (error.response.status === 419) {
                    // Attempt to refresh token
                    return axios.get(route('sanctum.csrf-cookie'))
                        .then(() => {
                            return axios(error.config);
                        })
                        .catch(refreshError => {
                            // Handle refresh error (e.g., failed to refresh token)
                            console.error('Failed to refresh CSRF token:', refreshError);
                            // Optionally display an error message to the user
                            return Promise.reject(error);
                        });
                    // Unauthenticated
                } else if (error.response.status === 401) {
                    this.$store.commit('setLogout');
                    return Promise.reject(error);
                    // unauthorized
                } else if (error.response.status === 403) {
                    this.$router.push({name: 'error.403'})
                }
                return Promise.reject(error);
            }
        );
    },
}
</script>

<style scoped>
</style>

<template>
    <div>
        <nav-view/>
        <main class="py-4 my_flex_height" role="main">
            <router-view class="container"/>
        </main>
        <footer class="text-center" role="contentinfo">
            <div>© {{ "UNIVERSITY OF PATRAS" + ' ' + (new Date()).getFullYear() }}</div>
            <div>Food Department</div>
            <img alt="University of Patras Logo" src="/img/big_logo_Upatras.png"/>
        </footer>
    </div>
</template>
