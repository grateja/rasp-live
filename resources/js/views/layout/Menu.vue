<template>
    <div>
        <flash-message />
        <v-toolbar flat app class="white">
            <!-- <v-toolbar-side-icon v-if="!!user" @click="drawer = !drawer"></v-toolbar-side-icon> -->

            <v-toolbar-title v-if="!!user">
                <!-- Title for the shop -->
                <img @click="navigate"  alt="" height="50px" src="/img/sunrys logo.jpg">
                <!-- <v-btn :to="isAdmin ? '/daily-sales' : '/'" flat class="font-weight-light">
                    RFIDJ
                </v-btn> -->
            </v-toolbar-title>

            <v-spacer></v-spacer>

            <!-- <template v-if="!!user">

                <v-btn flat to="/account">{{user.roles[0] | uppercase}}</v-btn>
                <v-btn flat fab small @click="logout" :loading="isLoggingOut">
                    <v-icon>exit_to_app</v-icon>
                </v-btn>
            </template> -->
<!--
            <template v-else>
                <v-btn flat router to="/login">Login</v-btn>
            </template> -->

        </v-toolbar>

        <!-- <v-navigation-drawer app v-model="drawer" :stateless="!needsToHide" fixed v-if="!!user" dark>
            <v-card v-if="!!user" flat class="pa-1 my-2 text-xs-center"  router to="/account">
                <v-responsive>
                    <v-avatar size="40" class="grey">
                        <img src="/img/Abstract.jpg" alt="">
                    </v-avatar>
                </v-responsive>

                <v-card-text>
                    <div class="text-xs-center">{{user.fullname}}</div>
                </v-card-text>

            </v-card>
            <v-divider></v-divider>

            <v-list dark>
            <template v-for="(link, i) in filterLinks">
                <v-list-group :key="i" v-if="link.childrenFiltered">
                    <v-list-tile slot="activator" :to="link.to" active-class="white--text darken-4" class="">
                        <v-list-tile-action><v-icon>{{link.icon}}</v-icon></v-list-tile-action>
                        <v-list-tile-content>
                            <v-list-tile-title>{{link.text}}</v-list-tile-title>
                        </v-list-tile-content>
                        <v-list-tile-action v-if="link.btn">
                            <v-tooltip right>
                                <v-btn fab flat slot="activator">
                                    <router-link :to="link.btn.to" tag="v-icon" v-text="link.btn.icon"></router-link>
                                </v-btn>
                                <span>{{link.btn.text}}</span>
                            </v-tooltip>
                        </v-list-tile-action>
                    </v-list-tile>

                    <v-list-tile v-for="(child_link, i) in link.childrenFiltered" :key="i" class="grey lighten-4 pl-4" color="#888" active-class="black--text grey lighten-4" :to="child_link.to">
                        <v-list-tile-action>
                            <v-icon small color="#555">{{child_link.icon}}</v-icon>
                        </v-list-tile-action>
                        <v-list-tile-content>
                            <v-list-tile-title>{{child_link.text}}</v-list-tile-title>
                        </v-list-tile-content>
                        <v-list-tile-action v-if="child_link.btn">
                            <v-tooltip right>
                                <v-btn fab flat small slot="activator">
                                    <router-link :to="child_link.btn.to" tag="v-icon" v-text="child_link.btn.icon"></router-link>
                                </v-btn>
                                <span>{{child_link.btn.text}}</span>
                            </v-tooltip>
                        </v-list-tile-action>

                    </v-list-tile>
                </v-list-group>

                <v-list-tile :key="i" v-else router :to="link.to" active-class="grey--text darken-1">
                    <v-list-tile-action><v-icon>{{link.icon}}</v-icon></v-list-tile-action>
                    <v-list-tile-content>
                        <v-list-tile-title>{{link.text}}</v-list-tile-title>
                    </v-list-tile-content>
                    <v-list-tile-action v-if="link.btn">
                        <v-tooltip right>
                            <v-btn fab flat small slot="activator">
                                <router-link :to="link.btn.to" tag="v-icon" v-text="link.btn.icon"></router-link>
                            </v-btn>
                            <span>{{link.btn.text}}</span>
                        </v-tooltip>
                    </v-list-tile-action>
                </v-list-tile>
            </template>
        </v-list>

        </v-navigation-drawer> -->
    </div>
</template>

<script>
import FlashMessage from './FlashMessage.vue';
export default {
    components: {
        FlashMessage
    },
    data() {
        return {
            drawer: true,
            mini: false,
            needsToHide: true,
            links: [
                {
                    to: '/daily-sales',
                    icon: '',
                    text: 'Daily sales',
                    roles: ['admin']
                },
                {
                    to: '/products',
                    icon: '',
                    text: 'Products',
                    roles: ['admin']
                // },
                // {
                //     to: '/services',
                //     icon: '',
                //     text: 'Services',
                //     roles: ['admin']
                }
            ]
        }
    },
    computed: {
        isAdmin() {
            return this.user && this.user.roles.some(r => r == 'admin')
        },
        user() {
            return this.$store.getters.getCurrentUser;
        },
        filterLinks() {
            let user = this.user;
            if(user && this.links.length) {
                let links = this.links.filter(link =>

                    // get only the links with specific roles
                    link.roles.some(role => role == user.roles[0] || role == '*') &&

                    // remove unnecessary roles
                    !link.roles.some(role => role == `!${user.roles[0]}`)).map(link => {

                        // only for links with sub tab
                        if(link.children){
                            link.childrenFiltered = link.children.filter(l => l.roles.some(role => role == user.roles[0] || role == '*'));
                        }
                        return link;
                    });

                return links;
            }
            return [];
        },
        isLoggingOut() {
            return this.$store.getters['auth/getLoggingOut'];
        }//,
        // branchName() {
        //     let activeBranch = this.$store.getters['getActiveBranch'];
        //     if(activeBranch != null) {
        //         return activeBranch.name;
        //     } else if(!this.loadingBranch) {
        //         return 'Branch not set';
        //     }
        // },
        // loadingBranch() {
        //     return this.$store.getters['branch/isSelectingBranch'];
        // }
    },
    methods: {
        onResize () {
            this.needsToHide = window.innerWidth < 800
        },
        logout() {
            this.$store.dispatch('auth/logout').then((res, rej) => {
                this.$router.push('/login');
            });
        },
        navigate() {
            let link = this.isAdmin ? '/daily-sales' : '/';
            this.$router.push(link);
        }
    },
    beforeDestroy () {
        if (typeof window !== 'undefined') {
            window.removeEventListener('resize', this.onResize, { passive: true })
        }
    },
    mounted () {
        if(window.innerWidth <= 800) {
            this.drawer = false;
        }
        this.onResize()
        window.addEventListener('resize', this.onResize, { passive: true });
    }
}
</script>
