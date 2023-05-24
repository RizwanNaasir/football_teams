import {createRouter, createWebHistory, NavigationGuardNext, RouteLocationNormalized, RouteRecordRaw} from "vue-router";

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
});

router.beforeEach((_to: RouteLocationNormalized, _from: RouteLocationNormalized, next: NavigationGuardNext) => {
    next();
});

export {router};
