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
                        readonly
                    />
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="class" class="form-label">Class</label>
                    <input
                        type="class"
                        id="class"
                        placeholder="Class"
                        class="form-control"
                        v-model="level"
                        readonly
                    />
                </div>
            </div>
        </div>

        <hr />

        <div class="col-12">
            <div
                class="row align-items-end my-4"
                v-for="(day, index) in days"
                :key="index"
                v-if="days.length > 0"
            >
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="day" class="form-label"
                            >Day<sup class="text-danger">*</sup></label
                        >
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
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="subject" class="form-label">Subject</label>
                        <input
                            type="text"
                            id="subject"
                            placeholder="Subject"
                            class="form-control"
                            v-model="day.subject"
                            readonly
                        />
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="teacher" class="form-label">Teacher</label>
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
                            readonly
                        />
                    </div>
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
                            readonly
                        />
                    </div>
                </div>
            </div>

            <div class="alert alert-primary text-center" v-else>
                No days added. Click <a :href="props.edit_route">here</a> to add.
            </div>
        </div>
    </form>
</template>

<script setup>
import axios from "axios";
import { ref, watch } from "vue";

const props = defineProps(["batch", "edit_route"]);

const name = ref(props?.batch?.name);
const level = ref(props?.batch?.level?.name);
const days = ref([]);

if (props?.batch?.batch_days?.length > 0) {
    props.batch.batch_days.forEach((day) => {
        days.value.push({
            day: day.day_name,
            start_time: day.start_time,
            end_time: day.end_time,
            teacher: day.teacher_name,
            subject: day.subject_name,
        });
    });
}
</script>
