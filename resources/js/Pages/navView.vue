<template>
    <nav aria-label="Main Navigation" class="navbar navbar-expand-md navbar-light bg-white shadow-sm" role="navigation">
        <div class="container">
            <!-- Brand/logo -->
            <router-link class="nav-link router-link-exact-active" to="">
                {{ appName }}
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
                        <router-link :to="{ name: 'purchase' }" class="nav-link router-link-exact-active">
                            {{ $t('purchase.value') }}
                        </router-link>
                    </li>
                    <!-- Display 'Entry Checking' link if user has entry checking ability -->
                    <li v-if="hasAbility($enums.UserAbilityEnum.ENTRY_CHECK)" class="nav-item">
                        <router-link :to="{ name: 'entryChecking' }" class="nav-link router-link-exact-active">
                            {{ $t('entry_check') }}
                        </router-link>
                    </li>
                    <!-- Display links for card application checking based on user's abilities -->
                    <template v-if="hasAbility($enums.UserAbilityEnum.CARD_APPLICATION_CHECK)">
                        <li v-for="category in Object.keys($enums.CardStatusEnum)" :key="category" class="nav-item">
                            <router-link
                                :to="{ name: 'cardApplication.checking', params: { category: category.toLowerCase() } }"
                                class="nav-link router-link-exact-active">
                                {{ $t('status.category.' + category.toLowerCase(), 2) }}
                            </router-link>
                        </li>
                    </template>
                    <li v-if="hasAbility($enums.UserAbilityEnum.CARD_OWNERSHIP)" class="nav-item dropdown">
                        <a id="navbarDropdown"
                           aria-expanded="false" aria-haspopup="true" class="nav-link dropdown-toggle"
                           data-bs-toggle="dropdown"
                           href="#" role="button">
                            {{ $t('card.value') }}
                            <span class="caret"></span>
                        </a>
                        <div aria-labelledby="navbarDropdown" class="dropdown-menu dropdown-menu-left">
                            <router-link :to="{ name: 'card.History' }"
                                         class="nav-link router-link-exact-active dropdown-item">
                                {{ $t('history') }}
                            </router-link>
                            <router-link :to="{ name: 'card.application' }"
                                         class="nav-link router-link-exact-active dropdown-item">
                                {{ $t('application') }}
                            </router-link>
                        </div>
                    </li>

                    <!-- Dropdown menu for coupon ownership -->
                    <li v-if="hasAbility($enums.UserAbilityEnum.COUPON_OWNERSHIP)"
                        class="nav-item dropdown">
                        <a id="navbarDropdown"
                           aria-expanded="false" aria-haspopup="true" class="nav-link dropdown-toggle"
                           data-bs-toggle="dropdown"
                           href="#" role="button">
                            {{ $t('coupon.value', 2) }}<span class="caret"></span>
                        </a>
                        <!-- Dropdown items -->
                        <div aria-labelledby="navbarDropdown" class="dropdown-menu dropdown-menu-left">
                            <router-link :to="{ name: 'coupons.History' }"
                                         class="nav-link router-link-exact-active dropdown-item">
                                {{ $t('history') }}
                            </router-link>
                            <router-link :to="{ name: 'coupons.transfer' }"
                                         class="nav-link router-link-exact-active dropdown-item">
                                {{ $t('transfer.value') }}
                            </router-link>
                        </div>
                    </li>
                </ul>

                <!-- Right side of navbar - for authentication links -->
                <ul class="navbar-nav ms-auto">
                    <li v-if="!isAuthenticated" class="nav-item">
                        <router-link :to="{ name: 'login' }" class="nav-link router-link-exact-active">
                            {{ $t('login') }}
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
                            <router-link :to="{ name: 'userProfile' }" class="nav-link router-link-exact-active">
                                {{ $t('my info') }}
                            </router-link>
                            <button class="nav-link"
                                    @click="logout()">
                                {{ $t('logout') }}
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
    name: "NavView",
    computed: {
        ...mapGetters('auth', [
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
            return this.$i18n.t('company.appName'); // Using translation for app name
        },
    },
    methods: {
        // Mapping actions from Vuex store
        ...mapActions('auth', [
            'logout',
        ]),
    },
    watch: {
        '$route.name'(newValue) {
            document.title = newValue ? `${newValue} | ${this.appName}` : this.appName;
        }
    }
}
</script>

<style scoped>
.router-link-active:focus, .router-link-exact-active:focus {
    outline: 2px solid #005fcc;
}

.nav-link, .dropdown-item {
    padding: 10px;
    display: inline-block;
}
</style>
