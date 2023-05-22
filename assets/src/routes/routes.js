import { createRouter, createWebHistory } from "vue-router";
var routes = [
    {
        name: "Home",
        path: "/",
        component: function () { return import(/* webpackChunkName: "Home" */ "@/views/pages/Home.vue"); },
    },
    {
        name: "MarketPlace",
        path: "/marketplace",
        component: function () { return import(/* webpackChunkName: "Marketplace" */ "@/views/pages/MarketPlace.vue"); },
    },
    {
        path: "/:catchAll(.*)",
        component: function () { return import(/* webpackChunkName: "Blank" */ "@/views/pages/404.vue"); },
    },
];
var router = createRouter({
    history: createWebHistory('/'),
    linkExactActiveClass: "active",
    routes: routes,
    scrollBehavior: function (to, from, savedPosition) {
        // Reset the scroll position to the top of the page on navigation
        return { top: 0 };
    },
});
router.beforeEach(function (to, from, next) {
    next();
});
export default router;
//# sourceMappingURL=routes.js.map