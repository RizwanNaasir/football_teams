import {Team} from "@/types/Team";
import {Ref} from "@vue/reactivity";

export interface Player {
    id?: number
    name: string
    surName: string
    team?: Team,
    price?: Ref<number>|any
}