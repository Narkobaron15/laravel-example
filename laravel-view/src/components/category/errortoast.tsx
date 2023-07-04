import { AxiosError } from "axios";
import { toast } from "react-toastify";

export function callErrorToast(error: AxiosError) {
    toast.error(error.message);
    console.warn("Server error: " + error.message);
    // console.log(error);
}