// process.env returns environment constants from the .env file
const BASE_URL: string = process.env.REACT_APP_BASE_URL as string;

const APP_ENV = {
    BASE_URL: BASE_URL,
}
export default APP_ENV;