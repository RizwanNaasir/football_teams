import {client} from "./client";
import {reactive} from "vue";

export type TeamsRefT = {
    teams: {
        data: [],
        pagination: {
            total: number,
            page: number,
            per_page: number,
            page_count: number
        }
    },
    loading: boolean,
    error: any,
}
export const teamsRef: TeamsRefT = reactive({
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
})

export type ParamsT = {
    page: number,
    filter: {
        name: string|null,
        family: string|null,
    }
}
export const getFruits = async (params: ParamsT) => {
    return await client.get("/teams", {params: params})
        .then((response) => {
            teamsRef.teams = response.data;
            teamsRef.loading = false;
        }).catch((error) => {
            teamsRef.error = error;
            teamsRef.loading = false;
        }).finally(() => {
            teamsRef.loading = false;
        });
};