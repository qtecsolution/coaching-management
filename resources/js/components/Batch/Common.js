
import { ref } from "vue";

import { useToast } from "vue-toast-notification";
import "vue-toast-notification/dist/theme-sugar.css";

export const statusList = [
    { id: 0, name: "Upcoming" },
    { id: 1, name: "Running" },
    { id: 2, name: "Completed" },
];

export const dayNames = ref([
    { id: 1, label: "Saturday" },
    { id: 2, label: "Sunday" },
    { id: 3, label: "Monday" },
    { id: 4, label: "Tuesday" },
    { id: 5, label: "Wednesday" },
    { id: 6, label: "Thursday" },
    { id: 7, label: "Friday" },
]);

export const toaster = (type = "info", message = "Test notification.") => {
    const $toast = useToast();

    $toast.open({
        message: message,
        type: type,
        position: "top-right",
        duration: 4000,
        dismissible: true,
        pauseOnHover: true,
    });
};