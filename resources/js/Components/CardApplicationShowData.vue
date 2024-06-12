<template>
    <div v-if="application" class="row">
        <div class="col-12 col-sm-6 col-md-5 col-lg-4">
            <header>
                <h4 class="text-left mt-3">Application: {{ application.id }}</h4>
            </header>
            <h5 class="mt-3">Files</h5>
            <div class="mb-3">
                <select v-model="selectFile" class="form-select">
                    <option v-for="file in files" :key="file.id" :value="file">File ID: {{ file.id }}</option>
                </select>
            </div>
            <div v-if="selectFile" class="mb-3">
                <select v-model="selectFile.status" class="form-select" @change="updateStatus(selectFile)">
                    <option disabled value="">Please select one</option>
                    <option v-for="(value, status) in $enums.CardDocumentStatusEnum" :key="status" :value="value">
                        {{ status }}
                    </option>
                </select>
            </div>
            <message v-bind="resultFile"></message>
        </div>

        <div class="col-12 col-sm-6 col-md-5 col-lg-4 mt-3">
            <h4>Application Status</h4>
            <div>
                <label>Last comment from {{
                        application.card_last_update.card_application_staff_id ? 'staff' : 'student'
                    }}:</label>
                <p>{{ application.card_last_update.comment ?? 'No comment' }}</p>
            </div>
            <div>
                <label for="commentStaff">Enter text:</label>
                <input id="commentStaff" v-model="commentChecking" class="form-control mb-2" type="text">
                <label for="expiration_date">Expiration date:</label>
                <input id="expiration_date" v-model="expirationDate" class="form-control mb-2" type="date">
            </div>
            <div aria-label="Status buttons" class="btn-group mb-2" role="group">
                <button :class="{ active: application.card_last_update.status === $enums.CardStatusEnum.ACCEPTED }" class="btn btn-outline-primary"
                        type="button"
                        @click="changeStatus('ACCEPTED')">Accepted
                </button>
                <button :class="{ active: application.card_last_update.status === $enums.CardStatusEnum.REJECTED }" class="btn btn-outline-secondary"
                        type="button"
                        @click="changeStatus('REJECTED')">Rejected
                </button>
                <button :class="{ active: application.card_last_update.status === $enums.CardStatusEnum.INCOMPLETE }" class="btn btn-outline-warning"
                        type="button"
                        @click="changeStatus('INCOMPLETE')">Incomplete
                </button>
            </div>
            <message v-bind="result"></message>
        </div>

        <div class="col-12 col-md-7 col-lg-8">
            <object v-if="selectFile" :data="selectedFileUrl" class="pdf-object" type="application/pdf"></object>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        application: Object
    },
    data() {
        return {
            resultFile: {
                message: '',
                success: null,
                hide: true,
                errors: []
            },
            currentStatus: null,
            currentFileStatus: null,
            selectFile: null,
            files: [],
            commentChecking: null,
            expirationDate: null,
            result: {
                message: '',
                success: true,
                hide: false,
                errors: []
            }
        };
    },
    methods: {
        startingData() {
            this.currentStatus = this.application.card_last_update.status;
            this.files = this.application.card_application_document;
            this.expirationDate = this.application.expiration_date;
        },
        async updateStatus(file) {
            let params = new FormData();
            let vue = this;
            let url = route('document.update', {'document': file.id});
            vue.resultFile.message = '';
            params.append('_method', 'PUT');
            params.append('status', file.status);
            try {
                const response = await axios.post(url, params);
                let json = response.data;
                vue.resultFile.success = json.success;
                vue.resultFile.message = json.message;
                vue.resultFile.errors = [];
                return json.success;
            } catch (errors) {
                vue.resultFile.success = false;
                vue.resultFile.errors = errors.response.data.errors;
                vue.resultFile.message = "Request failed:";
                return false;
            }
        },
        async changeStatus(status) {
            this.application.card_last_update.status = this.$enums.CardStatusEnum[status];
            await this.updateApplicationStatus(this.application);
        },
        updateApplicationStatus(application) {
            let params = new FormData();
            let vue = this;
            params.append('status', application.card_last_update.status);
            params.append('card_application_id', application.id);
            if (this.expirationDate) {
                params.append('expiration_date', this.expirationDate);
            }
            if (this.commentChecking) {
                params.append('card_application_staff_comment', this.commentChecking);
            }
            axios.post(route('cardApplication.checking.store', {'category': application.card_last_update.status}), params)
                .then(response => {
                    let json = response.data;
                    vue.result.success = json === 1;
                    vue.result.errors = [];
                    if (vue.result.success) {
                        vue.result.message = `Change from ${vue.currentStatus} to ${application.card_last_update.status}`;
                        vue.currentStatus = application.card_last_update.status;
                    } else {
                        application.card_last_update.status = vue.currentStatus;
                        vue.result.message = "Request failed:";
                    }
                })
                .catch(errors => {
                    vue.result.errors = errors.response.data.errors;
                    vue.result.success = false;
                    application.card_last_update.status = vue.currentStatus;
                });
        }
    },
    computed: {
        selectedFileUrl() {
            return route('document.show', {'document': this.selectFile?.id});
        }
    },
    watch: {
        application(newValue) {
            if (newValue) this.startingData();
            this.selectFile = null;
        },
        async selectFile(newValue, oldValue) {
            if (oldValue && oldValue.status !== this.currentFileStatus) {
                if (!await this.updateStatus(oldValue)) {
                    oldValue.status = this.currentFileStatus;
                }
            }
            this.currentFileStatus = newValue ? newValue.status : null;
        }
    },
    created() {
        if (this.application) this.startingData();
    }
};
</script>

<style scoped>
.pdf-object {
    height: 500px;
    width: 100%;
    border: 1px solid #ccc;
}
</style>
