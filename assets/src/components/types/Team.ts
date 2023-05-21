import {Player} from "@/components/types/Player";

export interface Team {
    id: number
    name: string
    country: number
    moneyBalance: string
    players: Player[]
}