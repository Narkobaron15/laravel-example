import axios from "axios";
import APP_ENV from "./env/app_env";

/*
 * axios alias for requests, 
 * that will be taking only URI and send a request,
 * constructing full path, to the server (baseURL), 
 * with provided defaults (like headers)
 */
const http_common = axios.create({
    baseURL: APP_ENV.BASE_URL,
    headers: {
        "Content-Type": "application/json"
    }
});

export default http_common;