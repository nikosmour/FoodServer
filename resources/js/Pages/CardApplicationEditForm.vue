<template>
    <!-- Vue component template -->
    <div v-if="!cardApplication">
        <loading/>
        <message v-bind="result"></message>
    </div>
    <div v-else>
        <!-- Display application status and expiration date -->
        <p>{{ $t('applicationStatus') }} : {{ $t(status) + ' ' + $t('and') + ' ' + $t('expiration date') }} : {{
                expiration_date
            }}</p>
        <div class='row '>
            <div class='col-5'>
                <!-- Application form -->
                <header>
                    <br/>
                    <h4 class="text-center">{{ title }}</h4>
                    <button v-if="status === $enums.CardStatusEnum.TEMPORARY_SAVED" :aria-label="$t('save')"
                            class="btn btn-secondary" @click="changeToSave()">
                        <i aria-hidden="true" class="bi bi-save"></i>
                        <span class="visually-hidden">{{ $t('save') }}</span>
                    </button>
                    <button v-else-if="applicationEdit" :aria-label="$t('edit')" class="btn btn-secondary"
                            @click="changeToEdit()">
                        <i aria-hidden="true" class="bi bi-pencil"></i>
                        <span class="visually-hidden">{{ $t('edit') }}</span>
                    </button>
                </header>

                <!-- Form for editing card application -->
                <form v-if="status === $enums.CardStatusEnum.TEMPORARY_SAVED" id="card_application_form"
                      v-on:submit.prevent="">
                    <!-- Show card documents -->
                    <CardDocumentsShow ref="CardDocuments" v-bind:applicationEdit="true"
                                       v-bind:cardApplication="cardApplication?.id"
                                       v-on:previewFile="this.docLink= $event"/>
                    <!-- Comment input field -->
                    <div>
                        <label for="commentStudent">{{ $t('comment.enter') }}</label>
                        <input id="commentStudent" v-model="commentStudent" type="text">
                    </div>
                    <!-- Submit button -->
                    <button class="btn btn-primary" type="submit" @click="submit_form">
                        {{ $t('submit') }}
                    </button>
                </form>
                <!-- Show card documents if not in edit mode -->
                <CardDocumentsShow v-else :applicationEdit="false"
                                   v-bind:cardApplication="cardApplication?.id"
                                   v-on:previewFile="this.docLink= $event"/>
                <br/>
                <!-- Display result message -->
                <message v-bind="result"></message>
                <br/>
            </div>
            <!-- Display PDF document -->
            <object class='col' height="500px" type="application/pdf" v-bind:data="docLink" width="100%"/>
        </div>
    </div>
</template>

<script>
// Import necessary dependencies
export default {
    data() {
        return {
            // Initialize data properties
            result: {
                message: this.$t('test.message'),
                success: true,
                hide: false,
                errors: []
            },
            docLink: '', // Initialize docLink
            cardApplication: null, // Initialize cardApplication
            commentStudent: null // Initialize commentStudent
        }
    },
    computed: {
        // Computed properties
        status: function () {
            return this.cardApplication ? this.cardApplication.card_last_update.status : null;
        },
        expiration_date: function () {
            return this.cardApplication ? this.cardApplication.expiration_date : null;
        },
        // Check if application is in edit mode
        applicationEdit() {
            return [
                this.$enums.CardStatusEnum.INCOMPLETE,
                this.$enums.CardStatusEnum.SUBMITTED,
                this.$enums.CardStatusEnum.TEMPORARY_SAVED,
            ].includes(this.status);
        },
        // Set title based on application mode
        title() {
            return document.title = this.$t((this.applicationEdit) ? 'card.edit' : 'card.show');
        }
    },
    methods: {
        // Method to listen for updates
        broadcasting() {
            if (typeof Echo !== 'undefined')
                Echo.private(`cardApplication.${this.cardApplication.id}`)
                    .listen('CardApplicationUpdated', (e) => {
                        this.cardApplication.expiration_date = e['expiration_date'];
                        this.cardApplication.card_last_update.status = e['status'];
                    });
        },
        async changeToEdit() {
            return await this.update_form(this.$enums.CardStatusEnum.TEMPORARY_SAVED);
        },
        async changeToSave() {
            await this.$refs.CardDocuments.submitFiles();
            return await this.update_form(this.$enums.CardStatusEnum.TEMPORARY_SAVED);

        },
        // Method to fetch initial data
        startingData() {
            this.getApplication()
        },
        // Method to fetch card application data
        getApplication() {
            let url = route('cardApplication.index');
            console.log('getApplication');
            return axios.get(url
            ).then(responseJson => {
                let json = responseJson['data'];
                this.cardApplication = json['cardApplication'];

                this.broadcasting();
            }).catch(errors => {
                if (errors.response.status === 404)
                    return this.$router.push({name: 'card.application.create'});
                this.result.message = this.$t('retrieving_application_failed');
                this.result.errors = errors;
                this.result.success = false;
            });

        },
        // Method to submit form
        async submit_form() {
            if (!(await this.$refs.CardDocuments.submitFiles())) {
                this.result.success = false;
                this.result.message = this.$t('some_files_not_updated');
                return;
            }
            return await this.update_form(this.$enums.CardStatusEnum.SUBMITTED);
        },
        async update_form(status) {
            let url = route('cardApplication.update', this.cardApplication);
            let params = new FormData();
            this.result.message = ''; //#todo more clever way to show if the value is the same
            if (!this.applicationEdit) {
                this.result.success = false;
                this.result.message = this.$t('application_status_not_allow_submission');
            }
            params.append('_method', 'PUT');
            params.append('status', status);
            if (this.commentStudent) {
                params.append('comment', this.commentStudent);
            }
            console.log('start axios to application for submission');
            axios.post(url, params
            ).then(responseJson => {
                console.log(responseJson);
                let json = responseJson['data'];
                this.result.success = json['success'];
                this.result.message = (json['success']) ?
                    this.$t('application') + " " + this.$t(
                        'changeFromTo', {
                            "from1": `status.${this.cardApplication.card_last_update.status.replace(' ', '_')}`,
                            'to1': `status.${status.replace(' ', '_')}`
                        }) : this.$t(json['message']);
                this.result.errors = [];
            }).catch(errors => {
                console.log(errors);
                this.result.success = false;
                this.result.errors = errors.response.data.errors || errors;
                this.result.message = this.$t('request_failed_status_wont_change');
            }).finally(() => {
                if (this.result.success) {
                    this.cardApplication.card_last_update.status = status;
                }

                }
            )
        }
    },
    created() {
        this.startingData();
        document.title = this.title;
    },
}
</script>
