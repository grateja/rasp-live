<template>
    <v-container>
        <h3 class="title grey--text">Products</h3>
        <v-divider class="my-4"></v-divider>

        <v-card>
            <v-list>
                <v-list-tile v-for="item in items" :key="item.key">
                    <v-list-tile-content>
                        <v-list-tile-title>{{item.name}}</v-list-tile-title>
                        <div>P {{item.price.toFixed(2)}}</div>
                    </v-list-tile-content>
                    <v-list-tile-action>{{item.stock}}</v-list-tile-action>
                </v-list-tile>
            </v-list>
        </v-card>
    </v-container>
</template>


<script>
export default {
    data() {
        return {
            items: []
        }
    },
    methods: {
        load() {
            axios.get('/api/search/products').then((res, rej) => {
                this.items = res.data.result;
            });
        }
    },
    created() {
        this.load();
    }
}
</script>
