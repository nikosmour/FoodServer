<template>
    <!-- Navigation bar component -->
    <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm" role="navigation">
        <div class="container">
            <!-- Brand/logo -->
            <router-link class="nav-link router-link-exact-active" to="">
                {{ appName }} <!-- Display the application name -->
            </router-link>
            <!-- Toggle button for mobile navigation -->
            <button aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"
                    class="navbar-toggler" data-bs-target="#navbarSupportedContent"
                    data-bs-toggle="collapse" type="button">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div id="navbarSupportedContent" class="collapse navbar-collapse">
                <!-- Left side of navbar - for authenticated users -->
                <ul v-if="isAuthenticated" class="navbar-nav me-auto">
                    <!-- Display 'Purchase' link if user has coupon selling ability -->
                    <li v-if="hasAbility($enums.UserAbilityEnum.COUPON_SELL)" class="nav-item">
                        <router-link :to="{ name: 'purchase' }" class="nav-link router-link-exact-active"> Purchase
                        </router-link>
                    </li>
                    <!-- Display 'Entry Checking' link if user has entry checking ability -->
                    <li v-if="hasAbility($enums.UserAbilityEnum.ENTRY_CHECK)" class="nav-item">
                        <router-link :to="{ name: 'entryChecking' }" class="nav-link router-link-exact-active">
                            Entry Checking
                        </router-link>
                    </li>
                    <!-- Display links for card application checking based on user's abilities -->
                    <template v-if="hasAbility($enums.UserAbilityEnum.CARD_APPLICATION_CHECK)">
                        <li v-for="category in Object.keys($enums.CardStatusEnum)" :key="category" class="nav-item">
                            <router-link
                                :to="{ name: 'cardApplication.checking', params: { category: category.toLowerCase() } }"
                                class="nav-link router-link-exact-active">
                                {{ category.toLowerCase() }}
                            </router-link>
                        </li>
                    </template>
                    <!-- Dropdown menu for card ownership -->
                    <li v-if="hasAbility($enums.UserAbilityEnum.CARD_OWNERSHIP)" class="nav-item dropdown">
                        <a v-pre id="navbarDropdown"
                           aria-expanded="false" aria-haspopup="true" class="nav-link dropdown-toggle"
                           data-bs-toggle="dropdown"
                           href="#" role="button">
                            Student Menu <span class="caret"></span>
                        </a>
                        <!-- Dropdown items -->
                        <div aria-labelledby="navbarDropdown" class="dropdown-menu dropdown-menu-left">
                            <router-link :to="{ name: 'card.History' }"
                                         class="nav-link router-link-exact-active dropdown-item">
                                Card History
                            </router-link>
                            <router-link :to="{ name: 'card.application' }"
                                         class="nav-link router-link-exact-active dropdown-item">
                                Card Application
                            </router-link>
                        </div>
                    </li>

                    <!-- Dropdown menu for coupon ownership -->
                    <li v-if="hasAbility($enums.UserAbilityEnum.COUPON_OWNERSHIP)"
                        class="nav-item dropdown">
                        <a v-pre id="navbarDropdown"
                           aria-expanded="false" aria-haspopup="true" class="nav-link dropdown-toggle"
                           data-bs-toggle="dropdown"
                           href="#" role="button">
                            Coupons <span class="caret"></span>
                        </a>
                        <!-- Dropdown items -->
                        <div aria-labelledby="navbarDropdown" class="dropdown-menu dropdown-menu-left">
                            <router-link :to="{ name: 'coupons.History' }"
                                         class="nav-link router-link-exact-active dropdown-item">
                                Coupons History
                            </router-link>
                            <router-link :to="{ name: 'coupons.transfer' }"
                                         class="nav-link router-link-exact-active dropdown-item">
                                Coupons Transfer
                            </router-link>
                        </div>
                    </li>
                </ul>

                <!-- Right side of navbar - for authentication links -->
                <ul class="navbar-nav ms-auto">
                    <!-- Display 'Login' link if user is not authenticated -->
                    <li v-if="!isAuthenticated" class="nav-item">
                        <router-link :to="{ name: 'login' }" class="nav-link router-link-exact-active"> Login
                        </router-link>
                    </li>

                    <!-- Display user dropdown menu if user is authenticated -->
                    <li v-else class="nav-item dropdown">
                        <a id="navbarDropdown" aria-expanded="false" aria-haspopup="true"
                           class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button">
                            {{ currentUser.name }} <span class="caret"></span>
                        </a>

                        <!-- Dropdown items -->
                        <div aria-labelledby="navbarDropdown" class="dropdown-menu dropdown-menu-end">
                            <router-link :to="{ name: 'userProfile' }" class="nav-link router-link-exact-active"> My
                                Info
                            </router-link>
                            <!-- Logout button -->
                            <button class="nav-link"
                                    @click="logout()">
                                Logout
                            </button>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

</template>

<script>
import {mapActions, mapGetters} from "vuex";

export default {
    computed: {
        // Mapping getters from Vuex store
        ...mapGetters([
            'isAuthenticated',
            'currentUser',
            'hasAbility',
        ]),
        // Compute route title
        routeTitle() {
            return this.$route.name ? `${this.$route.name} | ${this.appName}` : this.appName;
        },
        // Get application name from environment variables
        appName() {
            return process.env.MIX_APP_NAME;
        }
    },
    methods: {
        // Mapping actions from Vuex store
        ...mapActions({
            logout: 'logout',
        }),
    },
    watch: {
        // Watch for changes in route title and update document title
        routeTitle(newValue) {
            document.title = newValue;
        }
    }
}
</script>

<style scoped>
.router-link-active:focus {
    outline: 2px solid #005fcc;
}

.router-link-active {
    padding: 10px;
    display: inline-block;
}
</style>
