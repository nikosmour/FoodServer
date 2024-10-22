<template>
    <v-container class="d-flex align-center justify-center fill-height">
        <v-card class="elevation-12">
                    <v-card-title class="text-h4 text-center">Welcome to FoodUni</v-card-title>

            <v-card-text class="text-center">

                        <v-btn
                            color="primary"
                            large
                            @click="login"
                            class="mt-5"
                        >
                            Login with SAML
                        </v-btn>
                    </v-card-text>
                </v-card>

    </v-container>
</template>

<script>
export default {
    methods: {
        login() {
            window.location.href = 'saml2/auth/login';
        }
    }
};
</script>

<style scoped>
.v-container {
    background-color: #f5f5f5;
}
.v-card {
    margin-top: 100px;
    padding: 20px;
}
</style>
