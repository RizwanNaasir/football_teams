import { createRouter, createWebHistory } from "vue-router";
const routes = [
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
router.beforeEach((_to, _from, next) => {
    next();
});
export { router };
//# sourceMappingURL=routes.js.map