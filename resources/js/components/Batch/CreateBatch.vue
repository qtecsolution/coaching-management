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
                        value=""
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
                        value=""
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
                        value=""
                    />
                </div>
                <small class="text-danger"></small>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="teacher" class="form-label">Teacher</label>
                    <select name="teacher" id="teacher" class="form-control form-select">
                        <option value="" disabled>Select Teacher</option>
                        <option value="1">Teacher 1</option>
                        <option value="2">Teacher 2</option>
                        <option value="3">Teacher 3</option>
                    </select>
                </div>
                <small class="text-danger"></small>
            </div>
        </div>

        <hr>

        <div class="col-12 text-end">
            <button type="button" class="btn btn-sm btn-secondary">
                <i class="bi bi-plus"></i> Add Day
            </button>
        </div>

        <div class="row" v-for="day in totalDay" :key="day">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="day" class="form-label">Day<sup class="text-danger">*</sup></label>
                    <select name="day" id="day" class="form-control form-select">
                        <option value="1">Saturday</option>
                        <option value="2">Sunday</option>
                        <option value="3">Monday</option>
                        <option value="4">Tuesday</option>
                        <option value="5">Wednesday</option>
                        <option value="6">Thursday</option>
                        <option value="7">Friday</option>
                    </select>
                </div>
                <small class="text-danger"></small>
            </div>
            <div class="col-md-4">
                <label for="start_time" class="form-label">Start Time<sup class="text-danger">*</sup></label>
                <input type="time" name="start_time" class="form-control" id="start_time">
            </div>
            <div class="col-md-4">
                <label for="end_time" class="form-label">End Time<sup class="text-danger">*</sup></label>
                <input type="time" name="end_time" class="form-control" id="end_time">
            </div>
        </div>

        <div class="col-12 text-end mt-2">
            <button type="submit" class="btn btn-primary">Save</button>
        </div>
    </form>
</template>

<script>
import { ref } from "vue";

const totalDay = ref(10);
</script>