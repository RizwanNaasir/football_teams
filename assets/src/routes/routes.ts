import {
    createRouter,
    createWebHistory,
    NavigationGuardNext,
    RouteLocationNormalized,
    RouteLocationNormalizedLoaded,
    RouteRecordRaw
} from "vue-router";

const routes: RouteRecordRaw[] = [
    {
        name: "Home",
        path: "/",
        component: () => import(/* webpackChunkName: "Home" */ "@/views/pages/Home.vue"),
    },
    {
        name: "MarketPlace",
        path: "/marketplace",
        component: () => import(/* webpackChunkName: "Marketplace" */ "@/views/pages/MarketPlace.vue"),
    },
    {
        path: "/:catchAll(.*)",
        component: () => import(/* webpackChunkName: "Blank" */ "@/views/pages/404.vue"),
    },
];

const router = createRouter({
    history: createWebHistory('/'),
    linkExactActiveClass: "active",
    routes,
    scrollBehavior(to: RouteLocationNormalized, from: RouteLocationNormalizedLoaded, savedPosition) {
        // Reset the scroll position to the top of the page on navigation
        return {top: 0};
    },
});

router.beforeEach((to: RouteLocationNormalized, from: RouteLocationNormalized, next: NavigationGuardNext) => {
    next();
});

export default router;
