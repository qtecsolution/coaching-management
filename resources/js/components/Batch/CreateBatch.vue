<template>
    <form>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="name" class="form-label"
                        >Name<sup class="text-danger">*</sup></label
                    >
                    <input
                        type="text"
                        name="name"
                        id="name"
                        placeholder="Name"
                        class="form-control"
                        v-model="name"
                        required
                    />
                </div>
                <small class="text-danger"></small>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="subject" class="form-label"
                        >Subject<sup class="text-danger">*</sup></label
                    >
                    <input
                        type="text"
                        name="subject"
                        id="subject"
                        placeholder="Subject"
                        class="form-control"
                        v-model="subject"
                        required
                    />
                </div>
                <small class="text-danger"></small>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="class" class="form-label">Class</label>
                    <input
                        type="class"
                        name="class"
                        id="class"
                        placeholder="Class"
                        class="form-control"
                        v-model="classNo"
                    />
                </div>
                <small class="text-danger"></small>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="teacher" class="form-label">Teacher</label>
                    <select
                        name="teacher"
                        id="teacher"
                        class="form-control form-select"
                        v-model="teacher"
                    >
                        <option value="" disabled>Select Teacher</option>
                        <option value="1">Teacher 1</option>
                        <option value="2">Teacher 2</option>
                        <option value="3">Teacher 3</option>
                    </select>
                </div>
                <small class="text-danger"></small>
            </div>
        </div>

        <hr />

        <div class="col-12 text-end">
            <button
                type="button"
                class="btn btn-sm btn-secondary"
                @click="addDay"
            >
                <i class="bi bi-plus"></i> Add Day
            </button>
        </div>

        <div
            class="row align-items-end"
            v-for="(day, index) in days"
            :key="index"
        >
            <div class="col-md-4">
                <div class="form-group">
                    <label for="day" class="form-label"
                        >Day<sup class="text-danger">*</sup></label
                    >
                    <select
                        name="day"
                        id="day"
                        class="form-control form-select"
                        v-model="day.day"
                    >
                        <option value="1">Saturday</option>
                        <option value="2">Sunday</option>
                        <option value="3">Monday</option>
                        <option value="4">Tuesday</option>
                        <option value="5">Wednesday</option>
                        <option value="6">Thursday</option>
                        <option value="7">Friday</option>
                    </select>
                    <small class="text-danger"></small>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="start_time" class="form-label"
                        >Start Time<sup class="text-danger">*</sup></label
                    >
                    <input
                        type="time"
                        name="start_time"
                        class="form-control"
                        id="start_time"
                        v-model="day.start_time"
                    />
                </div>
                <small class="text-danger"></small>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="end_time" class="form-label"
                        >End Time<sup class="text-danger">*</sup></label
                    >
                    <input
                        type="time"
                        name="end_time"
                        class="form-control"
                        id="end_time"
                        v-model="day.end_time"
                    />
                </div>
                <small class="text-danger"></small>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <button
                        type="button"
                        class="btn btn-sm btn-danger"
                        @click="removeDay(index)"
                    >
                        <i class="bi bi-dash"></i>
                    </button>
                </div>
            </div>
        </div>

        <div class="col-12 text-end mt-2">
            <button type="button" class="btn btn-primary" @click="save">
                Save
            </button>
        </div>
    </form>
</template>

<script setup>
import axios from "axios";
import { ref, watch } from "vue";
import { useToast } from "vue-toast-notification";
import "vue-toast-notification/dist/theme-sugar.css";

const toaster = (type = "info", message = "Test notification.") => {
    const $toast = useToast();

    $toast.open({
        message: message,
        type: type,
        position: "top-right",
        duration: 4000,
        dismissible: true,
        pauseOnHover: true
    });
};

const name = ref("");
const subject = ref("");
const classNo = ref("");
const teacher = ref("");

const days = ref([
    {
        day: "",
        start_time: "",
        end_time: "",
    },
]);

const addDay = () => {
    days.value.push({
        day: "",
        start_time: "",
        end_time: "",
    });
};

const removeDay = (key) => {
    if (days.value.length <= 1) {
        return;
    }

    days.value.splice(key, 1);
};

const checkTimeConflict = () => {
    for (let i = 0; i < days.value.length; i++) {
        const { day: day1, start_time: start1, end_time: end1 } = days.value[i];

        for (let j = i + 1; j < days.value.length; j++) {
            const {
                day: day2,
                start_time: start2,
                end_time: end2,
            } = days.value[j];

            // Only compare if the days are the same
            if (day1 === day2) {
                // Check if times overlap
                const startTime1 = new Date(`1970-01-01T${start1}:00`);
                const endTime1 = new Date(`1970-01-01T${end1}:00`);
                const startTime2 = new Date(`1970-01-01T${start2}:00`);
                const endTime2 = new Date(`1970-01-01T${end2}:00`);

                if (startTime1 < endTime2 && startTime2 < endTime1) {
                    return true; // Conflict found
                }
            }
        }
    }
    return false; // No conflicts
};

watch(
    days,
    (newVal) => {
        if (checkTimeConflict()) {
            // alert("Time conflict found");
            toaster("warning", "You cannot have two classes at the same time.");

            // remove the last added day
            days.value.pop();

            return;
        }
    },
    { deep: true }, // Deep watch because `days` is an array of objects
);

const save = async () => {
    console.table({
        name: name.value,
        subject: subject.value,
        class_no: classNo.value,
        teacher: teacher.value,
    });

    console.table(days.value);

    await axios
        .post(route("batches.store"), {
            name: name.value,
            subject: subject.value,
            class_no: classNo.value,
            teacher: teacher.value,
            days: days.value,
        })
        .then(() => {
            window.location.href = '/admin/batches?success=true';
        })
        .catch((error) => {
            console.error(error);
        });
};
</script>
