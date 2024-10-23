<template>
    <loading v-if="!user"/>
    <div v-else>
        <card-applicant-info :aria-label="$t('user.information')" :caption="$t('user.value')" :model="user"/>
        <form id="accept-form" aria-label="Accept Form" method="POST" @submit.prevent="createApplication">
            <button aria-label="Accept" class="btn btn-primary">{{ $t('accept') }}</button>
        </form>
        <message v-bind="result"/>
    </div>
</template>


<script>
import {mapGetters} from 'vuex';

export default {
    data: function () {
        return {
            url: route('cardApplication.store'),
            result: {
                message: this.$t('test.Message'),
                success: true,
                hide: true,
                errors: ['']
            },
        }
    },

    computed: {
        ...mapGetters('auth', [
            'currentUser',
        ]),
        user: function () {

            return this.currentUser ? {
                ...this.currentUser,
                address: this.currentUser.card_applicant.address,
                academic: this.currentUser
            } : null;
        }
    },
    methods: {
        createApplication() {
            if (0 === this.currentUser.academic_id) return;
            this.result.message = '';
            axios.post(this.url).then(responseJson => {
                let json = responseJson.data;
                this.result.success = json.success;
                if (json.success) {
                    this.result.message = this.$t('transfer.successful');
                    this.result.errors = [];
                    setTimeout(() => this.$router.push({name: 'card.application'}), 2000);
                    return;
                }
                this.result.message = this.$t('request_failed');
                this.result.errors = json;
            }).catch(errors => {
                this.result.success = false;
                this.result.errors = errors.response.data.errors;
                console.log(errors.response.data.errors);
                this.result.message = this.$t('request_failed');
            });
        }
    }
};
</script>

<style scoped>
/* Ensure sufficient color contrast */
button.btn-primary {
    background-color: #0056b3; /* Darken the primary color */
    color: #ffffff;
}

button.btn-primary:hover {
    background-color: #004494; /* Darken the hover color */
}
</style>
