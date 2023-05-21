import { __awaiter, __generator } from "tslib";
import { client } from "./client";
import { reactive } from "vue";
export var teamsRef = reactive({
    teams: {
        data: [],
        pagination: {
            total: 0,
            page: 0,
            per_page: 0,
            page_count: 0
        }
    },
    loading: true,
    error: null,
});
export var getFruits = function (params) { return __awaiter(void 0, void 0, void 0, function () {
    return __generator(this, function (_a) {
        switch (_a.label) {
            case 0: return [4 /*yield*/, client.get("/teams", { params: params })
                    .then(function (response) {
                    teamsRef.teams = response.data;
                    teamsRef.loading = false;
                }).catch(function (error) {
                    teamsRef.error = error;
                    teamsRef.loading = false;
                }).finally(function () {
                    teamsRef.loading = false;
                })];
            case 1: return [2 /*return*/, _a.sent()];
        }
    });
}); };
//# sourceMappingURL=useTeams.js.map