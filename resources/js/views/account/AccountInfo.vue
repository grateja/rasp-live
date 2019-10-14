<template>
    <v-container>
        <v-btn to="/account/change-password" class="white" active-class="primary">Change password</v-btn>
    </v-container>
</template>

<script>
export default {
    computed: {
        accountInfo() {
            return this.$store.getters.getCurrentUser;
        }
    }
}
</script>
