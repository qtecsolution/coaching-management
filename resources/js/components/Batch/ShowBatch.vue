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
                    <small class="text-danger" v-if="errors && errors.name">{{
                        errors.name[0]
                    }}</small>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="subject" class="form-label"
                        >Subject<sup class="text-danger">*</sup></label
                    >
                    <input
                        type="text"
                        id="subject"
                        placeholder="Subject"
                        class="form-control"
                        v-model="subject"
                        readonly
                    />
                    <small
                        class="text-danger"
                        v-if="errors && errors.subject"
                        >{{ errors.subject[0] }}</small
                    >
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
                        v-model="classNo"
                        readonly
                    />
                    <small class="text-danger" v-if="errors && errors.class">{{
                        errors.class[0]
                    }}</small>
                </div>
            </div>
        </div>

        <hr />

        <div class="col-12">
            <div
                class="row align-items-end my-4"
                v-for="(day, index) in days"
                :key="index"
            >
                <div class="col-md-3">
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
                        <small
                            class="text-danger"
                            v-if="errors && errors.days"
                            >{{ errors.days[0] }}</small
                        >
                    </div>
                </div>
                <div class="col-md-3">
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
                        <small
                            class="text-danger"
                            v-if="errors && errors.teacher"
                            >{{ errors.teacher[0] }}</small
                        >
                    </div>
                </div>
                <div class="col-md-3">
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
                    <small class="text-danger"></small>
                </div>
                <div class="col-md-3">
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
                    <small class="text-danger"></small>
                </div>
            </div>
        </div>
    </form>
</template>

<script setup>
import axios from "axios";
import { ref, watch } from "vue";

const props = defineProps(["batch"]);

const name = ref(props?.batch?.name);
const subject = ref(props?.batch?.subject);
const classNo = ref(props?.batch?.class);
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
} else {
    days.value = [
        {
            day: "",
            start_time: "",
            end_time: "",
            teacher: "",
        },
    ];
}
</script>
