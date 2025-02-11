<template>
    <form>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="title" class="form-label">Title</label>
                    <input
                        type="text"
                        id="title"
                        placeholder="Title"
                        class="form-control"
                        v-model="title"
                        readonly
                    />
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="course" class="form-label">Course</label>
                    <input
                        type="text"
                        id="course"
                        placeholder="Course"
                        class="form-control"
                        v-model="course"
                        readonly
                    />
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="price" class="form-label">Price</label>
                    <input
                        type="number"
                        id="price"
                        placeholder="Price"
                        class="form-control"
                        v-model="price"
                        readonly
                    />
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="price" class="form-label">Discount Type</label>
                    <input
                        type="text"
                        id="discount_type"
                        placeholder="Discount Type"
                        class="form-control text-capitalize"
                        v-model="discount_type"
                        readonly
                    />
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="price" class="form-label">Discount</label>
                    <Tooltip
                        position="top"
                        message="If discount type is flat, it'll be the amount. If discount type is percentage, it'll be the percentage value."
                    />
                    <input
                        type="number"
                        id="discount"
                        placeholder="Discount"
                        class="form-control"
                        v-model="discount"
                        readonly
                    />
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="status" class="form-label">Status</label>
                    <input
                        type="text"
                        id="status"
                        placeholder="Status"
                        class="form-control text-capitalize"
                        v-model="status"
                        readonly
                    />
                </div>
            </div>
        </div>

        <hr />

        <div class="col-12">
            <div
                class="row align-items-end"
                v-for="(day, index) in days"
                :key="index"
                v-if="days.length > 0"
            >
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="day" class="form-label mb-0">Day</label>
                        <input
                            type="text"
                            id="day"
                            placeholder="Day"
                            class="form-control"
                            v-model="day.day"
                            readonly
                        />
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="teacher" class="form-label mb-0">Teacher</label>
                        <input
                            type="text"
                            id="teacher"
                            placeholder="Teacher"
                            class="form-control"
                            v-model="day.teacher"
                            readonly
                        />
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="start_time" class="form-label mb-0"
                            >Start Time</label
                        >
                        <input
                            type="time"
                            class="form-control"
                            id="start_time"
                            v-model="day.start_time"
                            readonly
                        />
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="end_time" class="form-label mb-0"
                            >End Time</label
                        >
                        <input
                            type="time"
                            class="form-control"
                            id="end_time"
                            v-model="day.end_time"
                            readonly
                        />
                    </div>
                </div>
            </div>

            <div class="alert alert-primary text-center" v-else>
                No days added. Click <a :href="props.edit_route">here</a> to
                add.
            </div>
        </div>
    </form>
</template>

<script setup>
import { ref, watch } from "vue";
import { statusList } from "./Common.js";

const props = defineProps(["batch", "edit_route"]);

const title = ref(props?.batch?.title);
const course = ref(props?.batch?.course?.title);
const price = ref(props?.batch?.price);
const discount_type = ref(props?.batch?.discount_type || "flat");
const discount = ref(props?.batch?.discount);

const status = ref(props?.batch?.status || 0);
status.value = statusList.find((item) => item.id == status.value).name;

const days = ref([]);
if (props?.batch?.batch_days?.length > 0) {
    props.batch.batch_days.forEach((day) => {
        days.value.push({
            day: day.day_name,
            start_time: day.start_time,
            end_time: day.end_time,
            teacher: day.teacher_name,
        });
    });
}
</script>
