<script>
import {mapActions} from "vuex";

export default {
    name: "CouponsTransactions",
    props: {
        couponOwner: Object
    },
    data() {
        return {
            transactions: [],
          temp: {...this.couponOwner},
            url: route('coupons.history'),
        };
    },
    /*computed: {
        url() {
            return route('coupons.history');
        },
    },*/
    methods: {
        ...mapActions('auth', [
            'getUser'
        ]),
        fetchData() {
            if (this.url)
                axios.get(this.url).then(
                    response => {
                        console.log(response.data);
                        let transactions = response.data.transactions.data;
                        transactions = Array.isArray(transactions) ? transactions : [transactions]
                        this.url = response.data.transactions.next_page_url;
                        // transactions = this.reformatTransactions(transactions);
                        /*if (new Date(transactions[0].created_at) > new Date(this.couponOwner.updated_at))
                            this.getUser().then(response => {
                                this.temp = {...this.couponOwner};
                                this.transactions.push(...this.reformatTransactions(transactions));
                            });
                        else*/
                        this.transactions.push(...this.reformatTransactions(transactions));
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
            if (transaction === 'buying' || transaction === 'receiving')
                this.temp[meal] -= amount;
            else if (transaction === 'sending' || transaction === 'using')
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
  <div v-if="transactions">
      <table :aria-label="$t('transactions')"
             class="table text-center table-hover table-bordered table-col-to-row-sm caption-top">
            <caption>{{ $t('transactions') }}</caption>
            <thead class="thead-dark">
            <tr>
                <th scope="col">{{ $t('transaction.kind') }}</th>
                <th scope="col">{{ $t('comment.value', 2) }}</th>
                <th scope="col">{{ $t('money') }}</th>
                <th v-for="(value, meal) in $enums.MealPlanPeriodEnum" :key="meal" scope="col">
                    {{ $t('meal_statistics.' + meal.toLowerCase()) }}
                </th>
                <th scope="col">{{ $t('date') }}</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="transaction in transactions" :key="transaction.id">
                <th scope="row">{{ $t('transaction.' + transaction.transaction) }}</th>
                <td>
                    <span v-if="transaction.money != 0">{{ transaction.money }}€</span>
                    <template v-for="(value, meal) in $enums.MealPlanPeriodEnum">
                        &nbsp;{{ $t('meal_statistics.' + meal.toLowerCase()) }}: {{ transaction[meal] }}
                    </template>
                    <template v-if="transaction.transaction === 'receiving'">
                        &nbsp;{{ $t('sender') }}: {{ transaction.academic_id }}
                    </template>
                    <template v-else-if="transaction.transaction === 'sending'">
                        &nbsp;{{ $t('receiver') }}: {{ transaction.academic_id }}
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
      <button v-if="this.url" v-on:click="fetchData">More</button>
  </div>
</template>
<style scoped>
.table {
  width: 100%;
  max-width: 100%;
  margin-bottom: 1rem;
  background-color: transparent;
}

@media (max-width: 576px) {
  .table {
    display: block;
    overflow-x: auto;
    width: 100%;
  }
}
</style>
