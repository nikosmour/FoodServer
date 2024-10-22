import {createRouter, createWebHashHistory} from 'vue-router'
import authGuard from "../guards/AuthGuard";
import {Enums} from "../plugins/enums.js";

const Unauthorized = () => import('../Pages/Errors/403.vue');
const NotFound = () => import('../Pages/Errors/404.vue')
const PurchaseCoupon = () => import('../Pages/PurchaseCoupon.vue');
const EntryChecking = () => import('../Pages/EntryChecking.vue');
const Login = () => import('../Pages/Auth/Login.vue');
const UserProfile = () => import("../Pages/UserProfile.vue");
const CardApplicationChecking = () => import("../Pages/CardApplicationChecking.vue");
const CardApplication = () => import("../Pages/CardApplicationEditForm.vue");
const CardApplicationCreate = () => import("../Pages/CardApplicationCreateForm.vue");
const Transactions = () => import("../Pages/Transactions.vue");
const TransferCoupons = () => import("../Pages/TransferCoupon.vue");
const CouponOwner = () => import("../Pages/CouponOwner.vue");
const CouponTransactions = () => import("../Components/CouponsTransactions.vue")
const CardApplicationCheckingSearch = () => import("../Components/CardApplicationCheckingSearch.vue")

const routes = [
    {
        path: '/purchase', name: 'purchase', component: PurchaseCoupon,
        meta: {requiresAbility: Enums.UserAbilityEnum.COUPON_SELL},
    },
    {
        path: '/entry', name: 'entryChecking', component: EntryChecking,
        meta: {requiresAbility: Enums.UserAbilityEnum.ENTRY_CHECK},
    },
    {
        path: '/login', name: 'login', component: Login
    },
    {
        path: '/myInfo', name: 'userProfile', component: UserProfile,
        meta: {requiresAuth: true},
    },
    {
        path: '/checking', component: CardApplicationChecking,
        meta: {requiresAbility: Enums.UserAbilityEnum.CARD_APPLICATION_CHECK},
        children: [
            {
                path: 'search', name: 'cardApplication.checking.search',
                component: CardApplicationCheckingSearch,
            },
            {
                path: ':category([A-z]+)', name: 'cardApplication.checking',
                beforeEnter: (to, from, next) => {
                    if (!Object.keys(Enums.CardStatusEnum).includes(to.params.category.toUpperCase())) {
                        // Handle invalid category (e.g., redirect to error page)
                        next({component: NotFound});
                    } else {
                        next();
                    }
                },
            },
        ]
    },
    {
        path: '/error/403', name: 'error.403', component: Unauthorized,
    },
    {
        path: '/card',
        meta: {requiresAbility: Enums.UserAbilityEnum.CARD_OWNERSHIP},
        children: [
            {
                path: 'transactions',
                name: 'card.History',
                component: Transactions,
                props: {
                    urlName: 'card.history',
                }
            },
            {
                path: 'application',
                name: 'card.application',
                component: CardApplication,
            },
            {
                path: 'application/create',
                name: 'card.application.create',
                component: CardApplicationCreate,
            }
        ]


    },
    {
        path: '/coupons',
        meta: {requiresAbility: Enums.UserAbilityEnum.COUPON_OWNERSHIP},
        component: CouponOwner,
        children: [
            {
                path: 'transactions',
                name: 'coupons.History',
                component: CouponTransactions
            },
            {
                path: 'transfer',
                name: 'coupons.transfer',
                component: TransferCoupons,
            },
        ]
    },
    {
        path: '/:pathMatch(.*)*', // Catch-all for unmatched routes (optional, see note below)
        component: NotFound,
    },
];
const router = createRouter({
    history: createWebHashHistory(),
    routes,
});
router.beforeEach(authGuard);

export default router;
