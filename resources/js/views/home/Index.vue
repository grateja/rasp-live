<template>
    <v-container>
        <h3 class="title grey--text">Daily sales</h3>
        <v-divider class="my-4"></v-divider>

        <form @submit.prevent="navigate">
            <v-text-field label="Year" v-model="year"></v-text-field>
        </form>

        <v-card flat color="transparent">
            <v-card-title class="ml-0 pa-0">
                <v-btn class="mx-0" flat v-if="monthIndex > 1" @click="prev"><v-icon>chevron_left</v-icon>{{moment(months[monthIndex - 2]).format('MMM')}}</v-btn>
                <v-spacer></v-spacer>
                <div class="title grey--text">{{moment(months[monthIndex - 1]).format('MMMM')}}</div>
                <v-spacer></v-spacer>
                <v-btn class="mx-0" flat v-if="monthIndex < 12" @click="next">{{moment(months[monthIndex]).format('MMM')}}<v-icon>chevron_right</v-icon></v-btn>
            </v-card-title>
        </v-card>
        <daily-sales :month="monthIndex" :year="year"></daily-sales>
    </v-container>
</template>

<script>
import DailySales from './DailySales.vue';
export default {
    components: {
        DailySales
    },
    data() {
        return {
            monthIndex: this.$route.params.month || moment().format('M'),
            year: this.$route.params.year || moment().format('Y'),
        }
    },
    methods: {
        prev() {
            this.monthIndex--;
            this.navigate();
        },
        next() {
            this.monthIndex++;
            this.navigate();
        },
        navigate() {
            this.$router.push(`/daily-sales/${this.year}/${this.monthIndex}`);
        }
    },
    computed: {
        months() {
            const months = [];
            for(var i = 1; i <= 12; i++) {
                months.push(moment(`${this.$route.params.year || moment().format('Y')}-${i}-1`));
            }
            return months;
        }
    }
}
</script>
