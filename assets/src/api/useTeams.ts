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
    response: any,
    search(query: string): void;
    find(id: number|string): Promise<{data: Team}>;
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
    response: null,
    search(query: string) {
        teamsRef.loading = true;
        getTeams({page: 1, query: {search: query}})
            .finally(() => {
                teamsRef.loading = false;
            });
    },
    async find(id: number) {
        teamsRef.loading = true;
        try {
            return await findTeam(id);
        } finally {
            teamsRef.loading = false;
        }
    }
})
export const pageRef = ref<number>(1);

type Params = {
    page: number,
    query?: {
        search?: string,
    }
}
export const getTeams = async (params: Params) => {
    return await client.get("/team", {params})
        .then((response) => {
            teamsRef.teams = response.data;
            teamsRef.loading = false;
        }).catch((error) => {
            teamsRef.response = error.response;
            teamsRef.loading = false;
        }).finally(() => {
            teamsRef.loading = false;
        });
};
export const findTeam = async (id: number) => {
    return await client.get("/team/" + id)
        .then((response) => {
            return response.data;
        }).catch((error) => {
            teamsRef.response = error.response;
            teamsRef.loading = false;
        }).finally(() => {
            teamsRef.loading = false;
        })
}
export const addNewTeam = async (data: Team) => {
    return await client.post("/team/add", data)
        .then((response) => {
            teamsRef.loading = false;
            return response.data;
        }).catch((error) => {
            teamsRef.response = error.response
            teamsRef.loading = false;
        }).finally(() => {
            teamsRef.loading = false;
        });
}

