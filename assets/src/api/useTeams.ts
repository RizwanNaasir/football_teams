import {client} from "./client";
import {reactive, ref} from "vue";
import {Team} from "@/types/Team";

export type TeamsRefT = {
    teams: {
        data: Team[],
        pagination: {
            total: number,
            pages: number,
            page: number,
            perPage: number
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
            pages: 0,
            page: 0,
            perPage: 0
        }
    },
    loading: true,
    error: null,
})
export const pageRef = ref<number>(1);

type Params = {
    page: number,
}
export const getTeams = async (params: Params) => {
    return await client.get("/team", {params})
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

export const addNewTeam = async (data: Team) => {
    return await client.post("/team/add", data)
        .then((response) => {
            teamsRef.loading = false;
            return response.data;
        }).catch((error) => {
            teamsRef.error = error;
            teamsRef.loading = false;
        }).finally(() => {
            teamsRef.loading = false;
        });
}