import { toast } from "react-toastify";

export function callErrorToast(error: Error) {
    toast.error(error.message);
    console.warn("Server error: " + error.message);
    // a logger should be there (logging errors in range 500-599)
}