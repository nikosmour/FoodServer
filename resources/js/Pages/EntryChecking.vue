<template>
    <div class="row my_flex_height">
        <entry-checking-form v-on:newEntry="newEntry($event)"></entry-checking-form>
        <export-statistics-form v-if="statistics" v-bind:statistics="statistics"></export-statistics-form>
    </div>
</template>

<script>
export default {
    props: {
        statisticsServer: {
            type: Object,
        }

    },
    data: function () {
        return {
            statistics: null,
        }
    },
    methods: {
        newEntry(entryCategory) {
            this.statistics[entryCategory] += 1;
        },
        async fetchData() {
            return await axios.get(route('entryChecking.create')).then(
                response => {
                    return response.data
                }
            );
            // return {cards: 0, coupons: 0,}
        }
    },
    async mounted() {
        this.statistics = this.statisticsServer || (await this.fetchData())
    },
}
</script>
