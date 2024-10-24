<template>
    <div class="container">
        <form aria-label="Search Form" class="row g-3" @submit.prevent="getId">
            <div class="col-md-6">
                <label class="form-label" for="applicationId">{{ $t('application_id') }}</label>
                <input id="applicationId" v-model="search.application_id" class="form-control" min="1" type="number"/>
            </div>
            <div class="col-md-6">
                <label class="form-label" for="academicId">{{ $t('academic_id') }}</label>
                <input id="academicId" v-model="search.academic_id" class="form-control" min="1" type="number"/>
            </div>
            <div class="col-md-6">
                <label class="form-label" for="aM">{{ $t('a_m') }}</label>
                <input id="aM" v-model="search.a_m" class="form-control" min="1" type="number"/>
            </div>
            <div class="col-md-6">
                <label class="form-label" for="email">{{ $t('email') }}</label>
                <input id="email" v-model="search.email" class="form-control" type="email"/>
            </div>
            <div class="col-12">
                <button aria-label="Submit" class="btn btn-primary" type="submit">{{ $t('submit') }}</button>
            </div>
        </form>
        <div v-if="applications.length" class="mt-4 table-responsive">
            <table class="table table-striped table-bordered caption-top">
                <caption>{{ $t('application', 2) }}</caption>
                <thead class="table-dark">
                <tr>
                    <th>{{ $t('id') }}</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="item in applications" :key="item.id">
                    <td>
                        <router-link :to="{ name: $route.name, query: { application: item.id } }" class="nav-link">
                            {{ item.id }}
                        </router-link>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        applications: {
            type: Array,
            default: () => [],
        },
    },
    data() {
        return {
            search: {
                application_id: null,
                academic_id: null,
                email: null,
                a_m: null,
            },
            result: {
                message: '',
                success: true,
                hide: false,
                errors: [''],
            },
        };
    },
    methods: {
        getId() {
            if (this.search.application_id) {
                this.$router.replace({
                    name: this.$route.name,
                    query: {application: this.search.application_id},
                });
            } else if (this.search.academic_id) {
                this.$emit('getId', ['academic_id', this.search.academic_id]);
            } else if (this.search.a_m) {
                this.$emit('getId', ['a_m', this.search.a_m]);
            } else if (this.search.email) {
                this.$emit('getId', ['email', this.search.email]);
            }
        },
    },
};
</script>

<style scoped>
.container {
    max-width: 900px;
    margin: 0 auto;
}

.form-label {
    font-weight: bold;
}

.table-responsive {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}

.table {
    width: 100%;
    margin-bottom: 1rem;
    color: #212529;
}

.caption-top {
    caption-side: top;
    font-weight: bold;
    text-align: left;
}

.table-dark th {
    background-color: #343a40;
    color: white;
}

.table-hover tbody tr:hover {
    background-color: rgba(0, 0, 0, 0.075);
}

/* Ensure responsive design */
@media (min-width: 768px) {
    .container {
        display: flex;
        flex-direction: column;
    }
}
</style>
