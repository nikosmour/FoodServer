<script>
import {mapActions} from "vuex";

export default {
    name: "CouponsTransactions",
    props: {
        couponOwner: Object
    },
    data() {
        return {
            transactions: null,
            temp: {...this.couponOwner},
        };
    },
    computed: {
        url() {
            return route('coupons.history');
        },
    },
    methods: {
        ...mapActions([
            'getUser'
        ]),
        fetchData() {
            axios.get(this.url).then(
                response => {
                    console.log(response.data);
                    let transactions = response.data.transactions;
                    transactions = Array.isArray(transactions) ? transactions : [transactions]
                    // transactions = this.reformatTransactions(transactions);
                    if (new Date(transactions[0].created_at) > new Date(this.couponOwner.updated_at))
                        this.getUser().then(response => {
                            this.temp = {...this.couponOwner};
                            this.transactions = this.reformatTransactions(transactions)
                        });
                    else
                        this.transactions = this.reformatTransactions(transactions);
                }
            );
        },
        calculateMoney(amount, transaction) {
            if (transaction === 'buying' || transaction === 'sending')
                this.temp.money += amount;
            else if (transaction === 'receiving')
                this.temp.money -= amount;
            return this.temp.money;
        },
        calculateMeal(amount, transaction, meal) {
            if (transaction === 'buying' || transaction === 'using')
                this.temp[meal] -= amount;
            else if (transaction === 'sending' || transaction === 'receiving')
                this.temp[meal] += amount;
            return this.temp[meal];
        },
        getMealValue(data, meal) {
            if (data.transaction === 'using' && data.academic_id === this.$enums.MealPlanPeriodEnum[meal]) {
                return 1;
            }
            return Number(data[meal]) || 0;
        },
        reformatTransactions(transactions) {
            transactions.forEach(transaction => {
                for (let meal in this.$enums.MealPlanPeriodEnum) {
                    transaction[meal] = this.getMealValue(transaction, meal);
                    transaction['total.' + meal] = this.calculateMeal(transaction[meal], transaction.transaction, meal);
                }
                transaction.totalMoney = this.calculateMoney(Number(transaction.money), transaction.transaction);
            });
            return transactions;
        },
    },
    mounted() {
        this.fetchData();
    },

}
</script>

<template>
    <div v-if="transactions" class="table-responsive">
        <table class="table table-striped table-hover text-center">
            <caption>{{ $t('transactions') }}</caption>
            <thead class="thead-dark">
            <tr>
                <th scope="col">{{ $t('Category') }}</th>
                <th scope="col">{{ $t('Comments') }}</th>
                <th scope="col">{{ $t('Money') }}</th>
                <th v-for="(value, meal) in $enums.MealPlanPeriodEnum" :key="meal" scope="col">{{ meal }}</th>
                <th scope="col">{{ $t('Date') }}</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="transaction in transactions" :key="transaction.id">
                <th scope="row">{{ transaction.transaction }}</th>
                <td>
                    <span v-if="transaction.money != 0">{{ transaction.money }}€</span>
                    <template v-for="(value, meal) in $enums.MealPlanPeriodEnum">
                        &nbsp;{{ meal }}: {{ transaction[meal] }}
                    </template>
                    <template v-if="transaction.transaction === 'receiving'">
                        &nbsp;{{ $t('Sender') }}: {{ transaction.academic_id }}
                    </template>
                    <template v-else-if="transaction.transaction === 'sending'">
                        &nbsp;{{ $t('Receiver') }}: {{ transaction.academic_id }}
                    </template>
                </td>
                <td>{{ transaction.totalMoney }} €</td>
                <td v-for="(value, meal) in $enums.MealPlanPeriodEnum"
                    :key="'transaction.' + transaction.id + '.meal.' + meal">
                    {{ transaction['total.' + meal] }}
                </td>
                <td>{{ new Date(transaction.created_at).toLocaleDateString() }}</td>
            </tr>
            </tbody>
        </table>
    </div>
</template>

<style scoped>
.table-responsive {
    margin-top: 20px;
}
</style>
