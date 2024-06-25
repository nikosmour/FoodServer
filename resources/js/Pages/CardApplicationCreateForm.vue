<template>
    <div>
        <card-applicant-info :aria-label="$t('user.information')" :caption="$t('user.value')" :model="currentUser"/>
        <form id="accept-form" aria-label="Accept Form" method="POST" @submit.prevent="createApplication">
            <button aria-label="Accept" class="btn btn-primary">{{ $t('accept') }}</button>
        </form>
        <message v-bind="result"/>
    </div>
</template>


<script>
import {mapGetters} from 'vuex';
import ModelsToTable from "../Components/modelsToTable.vue";
import Message from "../Components/Message.vue";
import CardApplicantInfo from "../Components/cardApplicantInfo.vue";

export default {
    components: {CardApplicantInfo, Message, ModelsToTable},
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
        ...mapGetters([
            'currentUser',
        ]),
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
