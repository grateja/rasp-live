<template>
    <v-dialog :value="value" max-width="600px" persistent>
        <v-card>
            <v-card-title class="title grey--text">Summary</v-card-title>
            <v-divider></v-divider>

            <v-progress-linear v-if="loading" indeterminate></v-progress-linear>

            <template v-else-if="!!summary">
                <v-data-table :headers="headers" :items="summary" :loading="loading" hide-actions>
                    <template v-slot:items="props">
                        <tr>
                            <td>{{props.item.name}}</td>
                            <td>{{props.item.total_count}}</td>
                            <td>P {{props.item.total_price.toFixed(2)}}</td>
                        </tr>
                    </template>
                    <tr slot="footer" v-if="!!summary" class="font-weight-bold grey lighten-3">
                        <td>Total </td>
                        <td></td>
                        <td>P {{grandTotal.toFixed(2)}}</td>
                    </tr>
                </v-data-table>
            </template>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn @click="$emit('input', false)">close</v-btn>
                <v-spacer></v-spacer>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script>
export default {
    props: [
        'value', 'date'
    ],
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
            axios.get(`/api/daily-sales/${this.date}/breakdown`).then((res, rej) => {
                console.log(res.data);
                this.summary = res.data.result;
                this.grandTotal = res.data.grandTotal;
            }).finally(() => {
                this.loading = false;
            });
        }
    },
    watch: {
        date(val) {
            if(val) {
                this.load();
            }
        }
    }
}
</script>
