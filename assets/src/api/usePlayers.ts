import {ref} from "vue";
import {Player} from "@/types/Player";
import {client} from "@/api/client";
import {AxiosResponse} from "axios";

interface PlayerRefT {
    data: Player[],
    response?: AxiosResponse,
}

export const playersRef = ref<PlayerRefT>({
    data: [],
    response: null,
});
export const getPlayers = async (params) => {
    return await client.get("/players", {params})
        .then((response) => {
            playersRef.value.data = response.data.data;
        }).catch((error) => {
            playersRef.value.response = error.response;
        });
}

export const buyPlayerFromTeam = async ({playerId, teamId, amount}) => {
    return await client.post(`/team/${teamId}/buy-player`, {playerId, amount})
        .then((response) => {
            playersRef.value.response = response;
        }).catch((error) => {
            playersRef.value.response = error.response;
        });
}