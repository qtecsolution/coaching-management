<template>
    <form>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="title" class="form-label"
                        >Title<sup class="text-danger">*</sup></label
                    >
                    <input
                        type="text"
                        id="title"
                        placeholder="Title"
                        class="form-control"
                        v-model="title"
                        required
                    />
                    <small class="text-danger" v-if="errors && errors.title">{{
                        errors.title[0]
                    }}</small>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="name" class="form-label"
                        >Course<sup class="text-danger">*</sup></label
                    >
                    <multiselect
                        v-model="course"
                        :options="courses"
                        :multiple="false"
                        :close-on-select="true"
                        :clear-on-select="false"
                        :preserve-search="false"
                        placeholder="Select Course"
                        label="title"
                        track-by="id"
                        :preselect-first="false"
                    >
                        <template #selection="{ values, search, isOpen }">
                            <span
                                class="multiselect__single"
                                v-if="values.length"
                                v-show="!isOpen"
                            >
                                {{ values.length }} options selected
                            </span>
                        </template>
                    </multiselect>
                    <small class="text-danger" v-if="errors && errors.course">{{
                        errors.course[0]
                    }}</small>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="price" class="form-label"
                        >Price<sup class="text-danger">*</sup></label
                    >
                    <input
                        type="number"
                        id="price"
                        placeholder="Price"
                        class="form-control"
                        v-model="price"
                        required
                    />
                    <small class="text-danger" v-if="errors && errors.price">{{
                        errors.price[0]
                    }}</small>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="price" class="form-label"
                        >Discount Type<sup class="text-danger">*</sup></label
                    >
                    <select
                        v-model="discount_type"
                        id="discount_type"
                        class="form-control form-select"
                    >
                        <option value="flat">Flat</option>
                        <option value="percentage">Percentage</option>
                    </select>
                    <small
                        class="text-danger"
                        v-if="errors && errors.discount_type"
                        >{{ errors.discount_type[0] }}</small
                    >
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="price" class="form-label"
                        >Discount<sup class="text-danger">*</sup></label
                    >
                    <Tooltip
                        position="top"
                        message="If discount type is flat, enter the amount. If discount type is percentage, enter the percentage value."
                    />
                    <input
                        type="number"
                        id="discount"
                        placeholder="Discount"
                        class="form-control"
                        v-model="discount"
                        required
                    />
                    <small
                        class="text-danger"
                        v-if="errors && errors.discount"
                        >{{ errors.discount[0] }}</small
                    >
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="price" class="form-label">Total Price</label>
                    <input
                        type="number"
                        id="total_price"
                        placeholder="Total Price"
                        class="form-control bg-light"
                        v-model="total_price"
                        readonly
                    />
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

            <fieldset
                class="border rounded p-3 my-3"
                v-for="(day, index) in days"
                :key="index"
            >
                <legend>
                    <div class="form-group">
                        <button
                            type="button"
                            class="btn btn-sm btn-danger"
                            @click="removeDay(index)"
                        >
                            <i class="bi bi-trash3"></i>
                        </button>
                    </div>
                </legend>
                <div class="row align-items-end">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="day" class="form-label"
                                >Day<sup class="text-danger">*</sup></label
                            >
                            <multiselect
                                v-model="day.day"
                                :options="dayNames"
                                :multiple="false"
                                :close-on-select="true"
                                :clear-on-select="false"
                                :preserve-search="false"
                                placeholder="Select Day"
                                label="label"
                                track-by="id"
                                :preselect-first="false"
                            >
                                <template
                                    #selection="{ values, search, isOpen }"
                                >
                                    <span
                                        class="multiselect__single"
                                        v-if="values.length"
                                        v-show="!isOpen"
                                    >
                                        {{ values.length }} options selected
                                    </span>
                                </template>
                            </multiselect>
                            <small
                                class="text-danger"
                                v-if="errors && errors.days"
                                >{{ errors.days[0] }}</small
                            >
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="teacher" class="form-label"
                                >Teacher</label
                            >
                            <multiselect
                                v-model="day.teacher"
                                :options="teachers"
                                :multiple="false"
                                :close-on-select="true"
                                :clear-on-select="false"
                                :preserve-search="false"
                                placeholder="Select Teacher"
                                label="label"
                                track-by="id"
                                :preselect-first="false"
                            >
                                <template
                                    #selection="{ values, search, isOpen }"
                                >
                                    <span
                                        class="multiselect__single"
                                        v-if="values.length"
                                        v-show="!isOpen"
                                    >
                                        {{ values.length }} options selected
                                    </span>
                                </template>
                            </multiselect>
                            <small
                                class="text-danger"
                                v-if="errors && errors.teacher"
                                >{{ errors.teacher[0] }}</small
                            >
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="start_time" class="form-label"
                                >Start Time<sup class="text-danger"
                                    >*</sup
                                ></label
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
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="end_time" class="form-label"
                                >End Time<sup class="text-danger"
                                    >*</sup
                                ></label
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
                </div>
            </fieldset>

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
import Tooltip from "../Tooltip.vue";
import { statusList, dayNames, toaster } from "./Common.js";

import Multiselect from "vue-multiselect";
import "vue-multiselect/dist/vue-multiselect.min.css";

const props = defineProps(["route", "teachers", "courses"]);

const title = ref("");
const course = ref("");
const price = ref("");
const discount_type = ref("flat");
const discount = ref(0);
const total_price = ref(0);
const errors = ref("");

const days = ref([
    {
        day: "",
        start_time: "",
        end_time: "",
        teacher: "",
    },
]);

const addDay = () => {
    days.value.push({
        day: "",
        start_time: "",
        end_time: "",
        teacher: "",
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

watch(
    [price, discount_type, discount],
    ([newPrice, newDiscountType, newDiscount]) => {
        if (newDiscountType === "percentage") {
            total_price.value = newPrice - (newPrice * newDiscount) / 100;
        } else {
            total_price.value = newPrice - newDiscount;
        }
    },
);

const save = async () => {
    if (
        !title.value ||
        !course.value ||
        !price.value ||
        !discount_type.value ||
        !discount.value ||
        days.value.length < 1
    ) {
        toaster("warning", "Please fill in all the required fields.");
        return false;
    }

    const form = new FormData();
    form.append("title", title.value);
    form.append("course", course.value.id);
    form.append("price", price.value);
    form.append("discount_type", discount_type.value);
    form.append("discount", discount.value);
    form.append("total_price", total_price.value);

    if (
        days.value.length > 1 ||
        (days.value[0]?.day &&
            days.value[0]?.start_time &&
            days.value[0]?.end_time &&
            days.value[0]?.teacher)
    ) {
        const finalDays = days.value.map((day) => {
            return {
                day: day.day.id,
                start_time: day.start_time,
                end_time: day.end_time,
                teacher: day.teacher.id,
            };
        });

        form.append("days", JSON.stringify(finalDays));
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
            // console.table(error.response.data.errors);
            toaster("error", "Something went wrong. Please try again.");
        });
};
</script>

<style>
.multiselect__tags {
    padding-top: 6px;
    min-height: auto !important;
    max-height: 38px !important;
}

legend {
    display: flex;
    justify-content: flex-end;
    text-align: right;
}
</style>
