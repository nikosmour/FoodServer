<template>
    <div>
        <models-to-table v-if="transactions" :caption="$t('transactions')" :models="transactions"/>
    </div>
</template>

<script>
import ModelsToTable from "../Components/modelsToTable.vue";

export default {
    components: {ModelsToTable},
    props: {
        urlName: String
    },
    data() {
        return {
            transactions: null,
        };
    },
    computed: {
        url() {
            return route(this.urlName);
        }
    },
    methods: {
        fetchData() {
            axios.get(this.url).then(
                response => {
                    console.log(response.data);
                    let transactions = response.data.transactions;
                    this.transactions = Array.isArray(transactions) ? transactions : [transactions];
                }
            );
        }
    },
    mounted() {
        this.fetchData();
    },
    watch: {
        url(newValue) {
            this.fetchData()
        }
    }


}
</script>
