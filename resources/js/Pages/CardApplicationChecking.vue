<template>
    <div class="container-fluid row">
        <router-view :applications="applications" class="col" @getId="getId($event)"/>

        <div class="col-auto">
            <div v-if="cursor.data">
                <button v-if="cursor.next_cursor" class="btn btn-primary" @click="nextPage">Next Application</button>
                <button v-if="cursor.prev_cursor" class="btn btn-secondary" @click="prevPage">Previous Application
                </button>
            </div>
            <CardApplicationShowData :application="selectedItem"/>
        </div>

        <message v-bind="result"></message>
    </div>
</template>

<script>
import CardApplicationShowData from "../Components/CardApplicationShowData.vue";

export default {
    components: {CardApplicationShowData},
    data() {
        return {
            cursor: {data: []},
            selectedItem: null,
            result: {
                message: '',
                success: true,
                hide: false,
                errors: []
            },
        };
    },
    computed: {
        category() {
            return this.$route.params.category ? this.$enums.CardStatusEnum[this.$route.params.category.toUpperCase()] : null;
        },
        applicationId() {
            return this.$route.params.application || this.$route.query.application;
        },
        applications() {
            return this.cursor.data;
        }
    },
    methods: {
        broadcasting() {
            if (typeof Echo !== 'undefined' && this.category)
                Echo.join(`cardChecking.${this.category}`)
                    .here((users) => console.log('Joined channel', users))
                    .joining((user) => console.log('Joining', user))
                    .leaving((user) => console.log('Leaving', user))
                    .error((error) => console.error(error))
                    .listen('CardApplicationUpdated', this.updateApplicationsIds);
        },
        getId(formData) {
            this.getApplications(formData[0], formData[1]).then(applications => {
                if (applications[0]) {
                    this.$router.replace({name: this.$route.name, query: {'application': applications[0].id}});
                }
            });
        },
        async getApplications(name, value, url = route('cardApplication.checking.search')) {
            try {
                const response = await axios.get(url, {params: {[name]: value}});
                console.log('get Applications', response.data);

                if (name !== 'application_id') this.cursor = response.data;

                const applications = response.data.data;
                const success = applications.length > 0;

                this.result.success = success;
                this.result.errors = [];
                this.result.message = success ? "Applications found" : "Request failed: Application not found";

                return applications.length > 0 ? applications : [null];
            } catch (errors) {
                console.log(errors);
                this.result.message = "Request failed: Application not found";
                this.result.success = false;
                return [null];
            }
        },
        async startingData() {
            console.log('cardApplicationChecking.startingData');
            if (this.category) {
                const applications = await this.getApplications('status', this.category);
                if (!this.applicationId && applications.length > 0 && applications[0]) {
                    this.$router.replace({name: this.$route.name, query: {'application': applications[0].id}});
                }
            }
            this.selectedItem = this.applicationId ? (await this.getApplications('application_id', this.applicationId))[0] : null;
        },
        updateApplicationsIds(e) {
            const cardApplicationId = e.cardApplication_id;
            const status = e.status;
            const position = this.applications.findIndex(obj => obj.id >= cardApplicationId);

            if (position !== -1)
                if (status !== this.category)
                    this.applications.splice(position, 1);
                else
                    this.applications.splice(position, 0, {id: cardApplicationId});
            else if (status === this.category)
                this.applications.push({id: cardApplicationId});
        },
        nextPage() {
            this.changePage(this.cursor.next_page_url);
        },
        prevPage() {
            this.changePage(this.cursor.prev_page_url);
        },
        async changePage(url) {
            if (url) {
                const applications = await this.getApplications('status', this.category, url);
                if (applications[0]) {
                    this.$router.replace({name: this.$route.name, query: {'application': applications[0]?.id}});
                }
            }
        }
    },
    mounted() {
        this.startingData();
        this.broadcasting();
    },
    watch: {
        category(newValue, oldValue) {
            this.applications = [];
            this.startingData();
            this.broadcasting();
            if (typeof Echo !== 'undefined' && oldValue) Echo.leave(`cardChecking.${oldValue}`);
        },
        async applicationId(newValue) {
            if (newValue) {
                const position = this.applications.findIndex(obj => obj.id == newValue);
                this.selectedItem = position === -1 ? (await this.getApplications('application_id', newValue))[0] : this.applications[position];
            }
        }
    },
    beforeRouteLeave(to, from) {
        if (typeof Echo !== 'undefined' && this.category) Echo.leave(`cardChecking.${this.category}`);
    }
};
</script>

<style scoped>
.container-fluid {
    padding: 20px;
}

button.btn {
    margin: 5px;
}
</style>
