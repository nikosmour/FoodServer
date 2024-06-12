<template>
    <div class="col-12 col-sm-6 col-md-7 col-lg-8">
        <header class="mb-4">
            <h4 class="text-left">{{ $t('entry_check') }}</h4>
        </header>
        <form id="use_form" class="needs-validation" novalidate @submit.prevent="check_id">
            <div class="form-group mx-auto" style="max-width: 20em;">
                <label for="academic_id"><strong>{{ $t('card_number') }}</strong></label>
                <input
                    id="academic_id"
                    v-model.number="academic_id"
                    class="form-control"
                    required
                    type="number"
                />
                <div class="invalid-feedback">{{ $t('required_field') }}</div>
                <message v-bind="result"></message>
            </div>
        </form>
    </div>
</template>

<script>
import axios from "axios";

export default {
    data() {
        return {
            academic_id: '',
            url: route('entryChecking.store'),
            result: {
                message: 'ready',
                success: true,
                hide: true,
                errors: ['']
            }
        };
    },
    methods: {
        check_id() {
            if (0 === this.academic_id)
                return;
            let params = new FormData();
            params.append('academic_id', this.academic_id);
            this.result.message = '';
            axios.post(this.url, params)
                .then(response => {
                    let json = response.data;
                    this.result.success = json.pass;
                    if (json.pass) {
                        this.result.message = json.passWith;
                        this.$emit('newEntry', json.passWith + 's');
                        this.result.errors = [];
                    } else {
                        this.result.message = "Request failed:";
                        this.result.errors = json;
                    }
                })
                .catch(errors => {
                    this.result.success = false;
                    this.result.errors = errors.response.data.errors;
                    this.result.message = "Request failed:";
                });
        }
    }
};
</script>
