import {Player} from "@/types/Player";

export interface Team {
    id: number
    name: string
    country: string
    moneyBalance: number
    players: Player[]
}