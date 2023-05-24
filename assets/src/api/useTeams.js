import { __awaiter } from "tslib";
import { client } from "./client";
import { reactive, ref } from "vue";
export const teamsRef = reactive({
    teams: {
        data: [],
        pagination: {
            total: 0,
            pages: 0,
            page: 0,
            perPage: 0
        }
    },
    loading: true,
    response: null,
    search(query) {
        teamsRef.loading = true;
        getTeams({ page: 1, query: { search: query } })
            .finally(() => {
            teamsRef.loading = false;
        });
    },
    find(id) {
        return __awaiter(this, void 0, void 0, function* () {
            teamsRef.loading = true;
            try {
                return yield findTeam(id);
            }
            finally {
                teamsRef.loading = false;
            }
        });
    }
});
export const pageRef = ref(1);
export const getTeams = (params) => __awaiter(void 0, void 0, void 0, function* () {
    return yield client.get("/team", { params })
        .then((response) => {
        teamsRef.teams = response.data;
        teamsRef.loading = false;
    }).catch((error) => {
        teamsRef.response = error.response;
        teamsRef.loading = false;
    }).finally(() => {
        teamsRef.loading = false;
    });
});
export const findTeam = (id) => __awaiter(void 0, void 0, void 0, function* () {
    return yield client.get("/team/" + id)
        .then((response) => {
        return response.data;
    }).catch((error) => {
        teamsRef.response = error.response;
        teamsRef.loading = false;
    }).finally(() => {
        teamsRef.loading = false;
    });
});
export const addNewTeam = (data) => __awaiter(void 0, void 0, void 0, function* () {
    return yield client.post("/team/add", data)
        .then((response) => {
        teamsRef.loading = false;
        return response.data;
    }).catch((error) => {
        teamsRef.response = error.response;
        teamsRef.loading = false;
    }).finally(() => {
        teamsRef.loading = false;
    });
});
//# sourceMappingURL=useTeams.js.map