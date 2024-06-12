<template>
    <div>
        <table class="table table-hover table-bordered">
            <caption>{{ caption }}</caption>
            <thead class="thead-dark">
            <tr>
                <th v-for="key in attributes" :key="key" scope="col">{{ key }}</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="(model, index) in models" :key="'model-' + index">
                <td v-for="key in attributes" :key="'model-' + index + '-attribute-' + key">{{ model[key] }}</td>
            </tr>
            <tr v-for="(relationship, index) in relationships" :key="'relationship-' + index">
                <td :colspan="attributes.length" class="p-0">
                    <button class="btn btn-link w-100 text-start" @click="toggleRelationship(index)">
                        {{ relationship }}
                        <span v-if="expandedRelationships.includes(index)">&#9650;</span>
                        <span v-else>&#9660;</span>
                    </button>
                    <div v-if="expandedRelationships.includes(index)">
                        <models-to-table :caption="relationship" :models="dataToArray(models[0][relationship])"/>
                    </div>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
export default {
    props: {
        models: {
            type: Array,
            required: false,
            default: () => [],
        },
        caption: {
            type: String,
            default: 'Table',
        },
    },
    data() {
        return {
            expandedRelationships: [],
        };
    },
    computed: {
        firstModel() {
            return this.models.length > 0 ? this.models[0] : {};
        },
        attributes() {
            return Object.keys(this.firstModel).filter(key => typeof this.firstModel[key] !== 'object');
        },
        relationships() {
            return Object.keys(this.firstModel).filter(key => typeof this.firstModel[key] === 'object');
        },
    },
    methods: {
        dataToArray(data) {
            return Array.isArray(data) ? data : [data];
        },
        toggleRelationship(index) {
            const pos = this.expandedRelationships.indexOf(index);
            if (pos > -1) {
                this.expandedRelationships.splice(pos, 1);
            } else {
                this.expandedRelationships.push(index);
            }
        },
    },
};
</script>

<style scoped>
.table {
    table-layout: auto;
    width: 100%;
    margin-bottom: 1rem;
    color: #212529;
}

.table th,
.table td {
    padding: 0.75rem;
    vertical-align: top;
    border-top: 1px solid #dee2e6;
}

.table-hover tbody tr:hover {
    color: #495057;
    background-color: rgba(0, 0, 0, 0.075);
}

.table-bordered {
    border: 1px solid #dee2e6;
}

.table-bordered th,
.table-bordered td {
    border: 1px solid #dee2e6;
}

.thead-dark th {
    color: #fff;
    background-color: #343a40;
    border-color: #454d55;
}
</style>
