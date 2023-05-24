import axios, {AxiosInstance} from "axios";

export const client: AxiosInstance = axios.create({
    baseURL: "http://localhost:8000",
    headers: {
        "Content-Type": "application/json",
        "accept": "application/json",
    }
});