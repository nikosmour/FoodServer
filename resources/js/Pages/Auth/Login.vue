<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">{{ $t("login") }}</h5>
                    </div>

                    <div class="card-body">
                        <form @submit.prevent="submitForm">
                            <loading v-if="isLoading"/>
                            <div class="row mb-3">
                                <label class="col-md-4 col-form-label text-md-end" for="email">{{ $t('email') }}</label>
                                <div class="col-md-6">
                                    <input id="email" v-model="formData.email" :class="{ 'is-invalid': errors.email || errors.credentials }"
                                           class="form-control " required
                                           type="email">
                                    <div v-for=" error in errors.email" class="invalid-feedback" role="alert">
                                        {{ $t(error) }}
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-md-4 col-form-label text-md-end" for="password">{{
                                        $t("password")
                                    }}</label>
                                <div class="col-md-6">
                                    <input id="password" v-model="formData.password" :class="{ 'is-invalid': errors.password || errors.credentials}"
                                           class="form-control"
                                           required type="password">
                                    <div v-for=" error in errors.password || errors.credentials"
                                         class="invalid-feedback" role="alert">
                                        {{ $t(error) }}
                                    </div>
                                </div>
                            </div>

                            <!--                            <div class="row mb-3">
                                                            <div class="col-md-6 offset-md-4">
                                                                <div class="form-check">
                                                                    <input id="remember" v-model="formData.remember" class="form-check-input"
                                                                           type="checkbox">
                                                                    <label class="form-check-label" for="remember">Remember Me</label>
                                                                </div>
                                                            </div>
                                                        </div>-->

                            <div class="row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <button class="btn btn-primary" type="submit">{{ $t("login") }}</button>
                                    <!--
                                                                        <a class="btn btn-link" href="#">Forgot Your Password?</a>
                                    -->
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import {mapGetters} from "vuex";

export default {
    data() {
        return {
            formData: {
                email: '',
                password: '',
                // remember: false
            },
            errors: {},
            isLoading: false
        };
    },
    methods: {
        async submitForm() {
            try {
                // 1. Fetch CSRF cookie (assuming you have a function to retrieve it)
                /*await axios.get('/sanctum/csrf-cookie').then(response => {
                    // Login...
                });
                const csrfToken = await this.getCookie('XSRF-TOKEN');*/


                // 4. Handle successful login
                this.isLoading = true;
                await this.$store.dispatch('auth/loginUser', this.formData)
                console.log('Login successful! login as ', this.$store.state.auth.user, this.$store.state.auth.isLoggedIn);
            } catch (errors) {
                // 6. Handle network or other error
                if (errors.response.status === 422)
                    this.errors = errors.response.data.errors;
                console.error('Login error:', errors);
            } finally {
                // Reset form after login attempt
                this.isLoading = false
                this.email = '';
                this.password = '';
            }
        },
        redirectAfterLogin() {
            this.$router.push(this.$route.query.redirect || {name: 'userProfile'});
        }

    },
    computed: {
        ...mapGetters('auth', [
            'isAuthenticated',
        ]),
    },
    watch: {
        isAuthenticated(newValue) {
            if (newValue)
                this.redirectAfterLogin();
        }
    },
    created() {
        if (this.isAuthenticated)
            this.redirectAfterLogin();
    }
};
</script>
