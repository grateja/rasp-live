<template>
    <v-card>
        <v-progress-linear v-if="loading" indeterminate></v-progress-linear>
        <v-list v-if="dailySales && dailySales.length">
            <v-list-tile v-for="dailySale in dailySales" :key="dailySale.id" @click="breakdown(dailySale.created_at)">
                <v-list-tile-title>{{moment(dailySale.created_at).format("D, dddd")}}</v-list-tile-title>
                <v-list-tile-action>P{{dailySale.total_price.toFixed(2)}}</v-list-tile-action>
            </v-list-tile>
        </v-list>
        <daily-summary v-model="openSummary" :date="activeDate"/>
    </v-card>
</template>

<script>

import DailySummary from './Summary.vue';

export default {
    components: {
        DailySummary
    },
    props: [
        'year', 'month'
    ],
    data() {
        return {
            dailySales: [],
            openSummary: false,
            activeDate: null,
            loading: false
        }
    },
    methods: {
        load() {
            this.loading = true;
            axios.get(`/api/daily-sales/${this.year}/${this.month}`).then((res, rej) => {
                console.log(res.data.result);
                this.dailySales = res.data.result;
            }).finally(() => {
                this.loading = false;
            });
        },
        breakdown(date) {
            this.openSummary = true;
            this.activeDate = date;
        }
    },
    created() {
        this.load();
    },
    watch: {
        month(val) {
            this.load();
        },
        year(val) {
            this.load();
        }
    }
}
</script>
