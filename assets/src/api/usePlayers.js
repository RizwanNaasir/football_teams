import { __awaiter } from "tslib";
import { ref } from "vue";
import { client } from "@/api/client";
export const playersRef = ref({
    data: [],
    response: null,
});
export const getPlayers = (params) => __awaiter(void 0, void 0, void 0, function* () {
    return yield client.get("/players", { params })
        .then((response) => {
        playersRef.value.data = response.data.data;
    }).catch((error) => {
        playersRef.value.response = error.response;
    });
});
export const buyPlayerFromTeam = ({ playerId, teamId, amount }) => __awaiter(void 0, void 0, void 0, function* () {
    return yield client.post(`/team/${teamId}/buy-player`, { playerId, amount })
        .then((response) => {
        playersRef.value.response = response;
    }).catch((error) => {
        playersRef.value.response = error.response;
    });
});
//# sourceMappingURL=usePlayers.js.map