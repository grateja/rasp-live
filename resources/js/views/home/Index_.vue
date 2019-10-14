<template>
    <v-container>
        <h3 class="title grey--text">{{dateStr}}</h3>
        <v-divider class="my-4"></v-divider>

        <v-layout row wrap>
            <v-flex sm12 md5 lg4>
                <rfid-summary></rfid-summary>
            </v-flex>
            <v-flex sm12 md7 lg8>
                <v-card class="ma-1">
                    <v-card-title class="grey--text title">RFID Transactions</v-card-title>
                    <v-divider></v-divider>
                    <v-card-text>
                        <form @submit.prevent="loadRfidTransactions">
                            <v-text-field v-model="rfidKeyword" label="Search" append-icon="search"></v-text-field>
                        </form>
                        <v-data-table :headers="rfidHeaders" :items="rfidTransactions" :loading="loadingRfidTransactions" hide-actions>
                            <template v-slot:items="props">
                                <tr>
                                    <td>{{props.item.machine.machine_name}}</td>
                                    <td>{{props.item.service_price.name}}</td>
                                    <td>{{props.item.service_price.price.toFixed(2)}}</td>
                                    <td>{{moment(props.item.created_at).format('hh:mm a')}}</td>
                                </tr>
                            </template>
                        </v-data-table>
                    </v-card-text>
                    <v-card-actions>
                        <v-pagination v-if="totalPage > 1" :length="totalPage" v-model="page" @input="navigate"></v-pagination>
                    </v-card-actions>
                </v-card>
            </v-flex>
            <!-- <v-flex xs12 sm6>
                <service-summary></service-summary>
            </v-flex> -->
        </v-layout>

        <!-- <v-card>
            <v-card-title class="grey--text title">RFID Transactions</v-card-title>
            <v-divider></v-divider>
            <v-card-text>
                <form @submit.prevent="loadRfidTransactions">
                    <v-text-field v-model="rfidKeyword" label="Search" append-icon="search"></v-text-field>
                </form>
                <v-data-table :headers="rfidHeaders" :items="rfidTransactions" :loading="loadingRfidTransactions" hide-actions>
                    <template v-slot:items="props">
                        <tr>
                            <td>{{props.item.machine.machine_name}}</td>
                            <td>{{props.item.service_price.name}}</td>
                            <td>{{props.item.service_price.price.toFixed(2)}}</td>
                            <td>{{moment(props.item.created_at).format('hh:mm a')}}</td>
                        </tr>
                    </template>
                </v-data-table>
            </v-card-text>
        </v-card> -->

    </v-container>
</template>
<script>
import RfidSummary from './RfidSummary.vue';
import ServiceSummary from './ServiceSummary.vue';

export default {
    components: {
        RfidSummary,
        ServiceSummary
    },
    data() {
        return {
            totalPage: 0,
            page: 1,
            date: this.$route.params.date,
            rfidTransactions: [],
            rfidHeaders: [
                {
                    text: 'MACHINE',
                    sortable: false
                },
                {
                    text: 'SERVICE NAME',
                    sortable: false
                },
                {
                    text: 'PRICE',
                    sortable: false
                },
                {
                    text: 'TIME',
                    sortable: false
                }
            ],
            loadingRfidTransactions: false,
            rfidKeyword: this.$route.query.rfidKeyword
        }
    },
    methods: {
        loadRfidTransactions() {
            this.loadingRfidTransactions = true;
            axios.get(`/api/daily-sales/${this.date}/rfid-transactions`, {
                params: {
                    keyword: this.rfidKeyword,
                    page: this.page
                }
            }).then((res, rej) => {
                console.log(res.data);
                this.rfidTransactions = res.data.rfidTransactions.data;
                this.totalPage = res.data.rfidTransactions.last_page;
            }).finally(() => {
                this.loadingRfidTransactions = false;
            });
        },
        navigate(page) {
            this.page = page;
            this.loadRfidTransactions();
        }
    },
    computed: {
        dateStr() {
            if(this.date) {
                return moment(this.date).format('MMMM DD, YYYY');
            }
        }
    },
    created() {
        this.loadRfidTransactions();
    }
}
</script>
