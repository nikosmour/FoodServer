<script>
import {mapActions, mapGetters} from "vuex";
import NavView from "./navView.vue";

export default {
    components: {NavView},
    data() {
        return {
            imageUrl: '/img/big_logo_Upatras.png'
        };
    },

    computed: {
        ...mapGetters([
            'isAuthenticated',
        ]),
    },
    methods: {
        ...mapActions([
            'getUser'
        ]),
        redirectAuth(isAuthenticated) {
            console.log('App.vue/redirectAuth', this.$route.fullPath, isAuthenticated, this.$route.meta.requiresAuth);
            if (!isAuthenticated && (this.$route.meta.requiresAuth || this.$route.meta.requiresAbility))
                this.$router.push({
                    name: 'login',
                    query: {redirect: this.$route.fullPath},
                })
            else if (isAuthenticated && this.$route.name === 'login')
                this.$router.push(this.$route.query.redirect || {name: 'userProfile'});
        }
    },
    watch: {
        isAuthenticated(newValue) {
            console.log('App.vue/watch/isAuthenticated', this.$route.fullPath);
            this.redirectAuth(newValue);
        }
    },
    created() {
        //console.log('created',this.$route,this.$router.resolve(this.$route.fullPath).fullPath,Date());

    },
    mounted() {
        this.redirectAuth(this.isAuthenticated);
        //console.log('mounted',this.$route,this.$router.resolve(this.$route.fullPath).fullPath,Date());
        // console.log('mounted',this.$route);
        let timeoutMin = import.meta.env.VITE_SESSION_TIME_OUT;
        let timeout = (timeoutMin - 1) * 60000
        //console.log('App.vue/mounted/timeout',timeout)
        if (timeoutMin < 2)
            timeout = 60000

        setInterval(() => {
            axios.get(route('sanctum.csrf-cookie'));
        }, timeout);
        setInterval(() => {
            console.log('setInterval', this.isAuthenticated);
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
    <nav-view/>
    <main class="py-4 h-100" role="main">
        <router-view class="container"/>
    </main>
    <footer class="text-center" role="contentinfo">
        <div>© {{ $t('company.name').toUpperCase() + ' ' + (new Date()).getFullYear() }}</div>
        <div> {{ $t('company.department').toUpperCase() }}</div>
        <img :src="imageUrl" alt="{{$t('logo') +' '+$t('company.name')}}"/>
    </footer>
</template>
