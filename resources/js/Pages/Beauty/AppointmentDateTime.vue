<script setup>
import {DatePicker} from "v-calendar";
import {reactive, ref} from "vue";
import axios from "axios";
import AppointmentModalForm from "@/Pages/Beauty/AppointmentModalForm.vue";

defineProps({
    show: {
        type: Boolean,
        default: false,
    },

    salonAddress: {
        type: String
    },

    service: {
        type: Object
    },

    disabledDates: {
        type: Array,
        default: []
    },

    workingHours: {
        type: Array
    },

});

let formData = reactive({
    notAvailableHours: [],
    setNotAvailableHours(hours) {
        this.notAvailableHours = hours;
    },

    showWorkingHours: false,
    setShowWorkingHours(status) {
        this.showWorkingHours = status;
    },

    isHourBtnDisabled(hour) {
        return this.notAvailableHours.includes(hour);
    },


});

let date = ref(null);
let popover = ref({
    visibility: 'click',
    placement: 'bottom',
});

let updateDate = (date) => {
    formData.setNotAvailableHours([]);
    if (date) {
        axios.post('/beauty/getAppointments', {
            year: date.getFullYear(),
            month: date.getMonth() + 1,
            day: date.getDate()
        }).then(result => {
            let notAvailableHours = [];
            result.data.appointments.forEach(appointment => {
                notAvailableHours.push(appointment.date.substring(11, 16));
            });
            formData.setNotAvailableHours(notAvailableHours);
        });
        formData.setShowWorkingHours(true);
    }
}
</script>
<template>
    <form v-if="show">
        <label for="datePicker">
            Choose date:
        </label>
        <DatePicker id="datePicker"
                    v-model="date"
                    mode="date"
                    :popover="popover"
                    :disabled-dates="disabledDates"
                    @update:modelValue="updateDate"
        >
            <template #default="{ inputValue, inputEvents }">
                <input :value="inputValue" v-on="inputEvents" />
            </template>
        </DatePicker>
        <div v-if="formData.showWorkingHours">
            Choose time:
            <ul>
                <li v-for="hour in workingHours" :key="hour">
                    <AppointmentModalForm
                        :date="date"
                        :hour="hour"
                        :is-hour-btn-disabled="formData.isHourBtnDisabled(hour)"
                        :salon-address="salonAddress"
                        :service="service"
                    />
                </li>
            </ul>
        </div>
    </form>
</template>
