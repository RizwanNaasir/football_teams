import {Player} from "@/types/Player";

export interface Team {
    name: string
    country: string
    moneyBalance: number
    players: Player[]
}