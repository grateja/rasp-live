<template>
    <v-card>
        <v-card-title class="title grey--text">Service summary</v-card-title>
        <v-divider></v-divider>

        <v-progress-linear v-if="loading" indeterminate></v-progress-linear>

        <template v-if="!!summary">
            <v-data-table :headers="headers" :items="summary" :loading="loading" hide-actions>
                <template v-slot:items="props">
                    <tr>
                        <td>{{props.item.name}}</td>
                        <td>{{props.item.total_count}}</td>
                        <td>P {{props.item.total_amount.toFixed(2)}}</td>
                    </tr>
                </template>
                <tr slot="footer" v-if="!!summary" class="font-weight-bold grey lighten-3">
                    <td>Total </td>
                    <td></td>
                    <td>P {{grandTotal.toFixed(2)}}</td>
                </tr>
            </v-data-table>
        </template>
    </v-card>
</template>

<script>
export default {
    data() {
        return {
            summary: null,
            grandTotal: 0,
            loading: false,
            headers: [
                {
                    text: 'Service name',
                    sortable: false
                },
                {
                    text: 'Total count',
                    sortable: false
                },
                {
                    text: 'Total amount',
                    sortable: false
                }
            ]
        }
    },
    methods: {
        load() {
            this.loading = true;
            axios.get(`/api/summary/${this.$route.params.date}/services-summary`).then((res, rej) => {
                console.log('service summary', res.data);
                this.summary = res.data.summary;
                this.grandTotal = res.data.grandTotal;
            }).finally(() => {
                this.loading = false;
            });
        }
    },
    created() {
        this.load();
    }
}
</script>
