<template>
    <div class="bottom-menu">
        <v-layout row wrap>
            <template v-if="user">
                <v-flex xs4>
                    <v-card to="/daily-sales">
                        <v-card-text class="text-xs-center">
                            <v-icon large>event</v-icon>
                            <div>
                                Daily sales
                            </div>
                        </v-card-text>
                    </v-card>
                </v-flex>
                <v-flex xs4>
                    <v-card to="/products">
                        <v-card-text class="text-xs-center">
                            <v-icon large>list</v-icon>
                            <div>
                                Inventory
                            </div>
                        </v-card-text>
                    </v-card>
                </v-flex>
                <v-flex xs4>
                    <v-card to="/account">
                        <v-card-text class="text-xs-center">
                            <v-icon large>people</v-icon>
                            <div>
                                Account
                            </div>
                        </v-card-text>
                    </v-card>
                </v-flex>
            </template>
            <v-flex v-else>
                <v-card to="/login">
                    <v-card-text class="text-xs-center">
                        <v-icon large>lock</v-icon>
                        <div>
                            Login
                        </div>
                    </v-card-text>
                </v-card>
            </v-flex>
        </v-layout>
        <v-card>
            <v-card-text></v-card-text>
        </v-card>
    </div>
</template>

<script>
export default {
    computed: {
        user() {
            return this.$store.getters.getCurrentUser;
        }
    }
}
</script>

<style lang="css">
    .bottom-menu {
        position: fixed;
        bottom: 0px;
        width: 100%;
    }
</style>
