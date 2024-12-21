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
                        id="name"
                        placeholder="Name"
                        class="form-control"
                        v-model="name"
                        required
                    />
                    <small class="text-danger" v-if="errors && errors.name">{{
                        errors.name[0]
                    }}</small>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="name" class="form-label"
                        >Course<sup class="text-danger">*</sup></label
                    >
                    <select
                        v-model="course"
                        id="course"
                        class="form-control form-select"
                    >
                        <option value="" selected disabled>
                            Select Course
                        </option>
                        <option
                            v-for="course in courses"
                            :key="course.id"
                            :value="course.id"
                            :selected="course.id == course"
                        >
                            {{ course.title }}
                        </option>
                    </select>
                    <small
                        class="text-danger"
                        v-if="errors && errors.tuition_fee"
                        >{{ errors.tuition_fee[0] }}</small
                    >
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="class" class="form-label">Status</label>
                    <select
                        id="class"
                        class="form-control form-select"
                        v-model="status"
                    >
                        <option value="" selected disabled>Select Status</option>
                        <option
                            v-for="status in statusList"
                            :key="status.id"
                            :value="status.id"
                        >
                            {{ status.name }}
                        </option>
                    </select>
                    <small class="text-danger" v-if="errors && errors.status">{{
                        errors.status[0]
                    }}</small>
                </div>
            </div>
        </div>

        <hr />

        <div class="col-12">
            <div class="text-end">
                <button
                    type="button"
                    class="btn btn-sm btn-secondary"
                    @click="addDay"
                >
                    <i class="bi bi-plus"></i> Add Day
                </button>
            </div>

            <div
                class="row align-items-end my-4"
                v-for="(day, index) in days"
                :key="index"
            >
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="day" class="form-label"
                            >Day<sup class="text-danger">*</sup></label
                        >
                        <select
                            id="day"
                            class="form-control form-select"
                            v-model="day.day"
                        >
                            <option value="" selected disabled>
                                Select Day
                            </option>
                            <option value="1">Saturday</option>
                            <option value="2">Sunday</option>
                            <option value="3">Monday</option>
                            <option value="4">Tuesday</option>
                            <option value="5">Wednesday</option>
                            <option value="6">Thursday</option>
                            <option value="7">Friday</option>
                        </select>
                        <small
                            class="text-danger"
                            v-if="errors && errors.days"
                            >{{ errors.days[0] }}</small
                        >
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="teacher" class="form-label">Teacher</label>
                        <select
                            id="teacher"
                            class="form-control form-select choices"
                            v-model="day.teacher"
                        >
                            <option value="" selected disabled>
                                Select Teacher
                            </option>
                            <option
                                v-for="teacher in teachers"
                                :key="teacher.id"
                                :value="teacher.id"
                            >
                                {{ teacher.name }} ({{ teacher.phone }})
                            </option>
                        </select>
                        <small
                            class="text-danger"
                            v-if="errors && errors.teacher"
                            >{{ errors.teacher[0] }}</small
                        >
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="start_time" class="form-label"
                            >Start Time<sup class="text-danger">*</sup></label
                        >
                        <input
                            type="time"
                            class="form-control"
                            id="start_time"
                            v-model="day.start_time"
                        />
                    </div>
                    <small class="text-danger"></small>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="end_time" class="form-label"
                            >End Time<sup class="text-danger">*</sup></label
                        >
                        <input
                            type="time"
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
        pauseOnHover: true,
    });
};

const props = defineProps(["route", "teachers", "batch", "courses"]);

const statusList = [
    { id: 0, name: "Upcoming" },
    { id: 1, name: "Running" },
    { id: 2, name: "Completed" },
];

const name = ref(props?.batch?.name);
const course = ref(props?.batch?.course_id);
const status = ref(props?.batch?.status || 0);
const days = ref([]);
const errors = ref("");

if (props?.batch?.batch_days?.length > 0) {
    props.batch.batch_days.forEach((day) => {
        days.value.push({
            day: day.day,
            start_time: day.start_time,
            end_time: day.end_time,
            teacher: day.user_id,
            id: day.id
        });
    });
} else {
    days.value = [
        {
            day: "",
            start_time: "",
            end_time: "",
            teacher: ""
        },
    ];
}

const addDay = () => {
    days.value.push({
        day: "",
        start_time: "",
        end_time: "",
        teacher: ""
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
    if (!name.value || days.value.length < 1) {
        toaster("warning", "Please fill in all the required fields.");
        return false;
    }

    const form = new FormData();
    form.append("_method", "PUT");
    form.append("name", name.value);
    form.append("course", course.value);
    form.append("status", status.value);

    if (
        days.value.length > 1 ||
        (days.value[0]?.day &&
            days.value[0]?.start_time &&
            days.value[0]?.end_time &&
            days.value[0]?.teacher)
    ) {
        form.append("days", JSON.stringify(days.value));
    } else {
        form.append("days", JSON.stringify([]));
    }

    await axios
        .post(props.route, form)
        .then((response) => {
            // console.log(response.data);
            toaster("success", response.data.message);

            setTimeout(() => {
                window.location.href = "/admin/batches";
            }, 1000);
        })
        .catch((error) => {
            errors.value = error.response.data.errors;
            console.table(errors.value);

            toaster("error", "Something went wrong. Please try again.");
        });
};
</script>
