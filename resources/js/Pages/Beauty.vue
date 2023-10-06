<script setup>
import { Head } from '@inertiajs/vue3';
import axios from "axios";
import { reactive, ref } from 'vue';
import 'v-calendar/style.css';
import 'bootstrap/dist/css/bootstrap.css';
import AppointmentDateTime from "@/Pages/Beauty/AppointmentDateTime.vue";
import Loader from "@/Components/Loader.vue";

const formData = reactive({
    salons: [],
    setSalons(data) {
        this.salons = data;
    },

    selectedSalon: '',

    selectedService: '',
    setSelectedService(service) {
        this.selectedService = service;
    },

    services: [],
    setServices(data) {
        this.services = data;
    },

    loading: true,
    setLoading(status) {
        this.loading = status;
    },

    showDateForm: false,
    setShowDateForm(show) {
        this.showDateForm = show;
    },

    isButtonDisabled() {
        return !(this.selectedSalon.id && this.selectedService);
    },

    workingHours: [],
    setWorkingHours(hours) {
      this.workingHours = hours;
    },

});

axios.post("/beauty/getSalons")
    .then(result => {
        if (result.data.success) {
            formData.setSalons(result.data.salons);
            formData.setLoading(false);
        }
    })

let date = ref(null);
let weekdays = [1, 2, 3, 4, 5, 6, 7];
let disabledDates = ref([]);

let formUpdate = () => {
    formData.setLoading(true);
    formData.setSelectedService('');
    if (formData.selectedSalon.id && typeof formData.selectedService !== 'undefined') {
        axios.post("/beauty/getServices", {
            salonId: formData.selectedSalon.id
        })
            .then(result => {
                if (result.data.success) {
                    formData.setServices(result.data.services);
                    formData.setLoading(false);
                }
            });
    }
};

let nextStep = () => {
    formData.setShowDateForm(true);
    let workingDays = formData.selectedService.working_days.split(',').map(el => parseInt(el));
    let difference = weekdays.filter(el => !workingDays.includes(el));
    formData.setWorkingHours(formData.selectedService.working_hours.split(','));
    let disabledDays = [];
    let currentDate = new Date();
    let lastWorkingHour = formData.workingHours[formData.workingHours.length - 1].substring(0, 2);

    if (currentDate.getDay() > 1) {
        let currentDay = currentDate.getDay();
        if (currentDate.getHours() >= lastWorkingHour) {
            currentDay++;
        }

        for (let i = 1; i <= currentDay; i++) {
            disabledDays.push(new Date(currentDate.getFullYear(), currentDate.getMonth(), i))
        }
    }

    disabledDates.value = [
        ...disabledDays,
        {
            repeat: {
                weekdays: difference
            },
        }
    ];
}

</script>

<template>
    <Head title="Beauty" />
    <div class="row">
        <div class="col"></div>
        <div class="col">
            <h3>Booking on beauty services</h3>

            <form v-if="!formData.showDateForm">
                <div class="mb-3">
                    <label for="salonSelect" class="form-label">Choose salon: </label>
                    <select id="salonSelect" class="form-select" v-model="formData.selectedSalon" @change="formUpdate">
                        <option v-if="formData.loading">
                            <Loader :show="formData.loading" />
                        </option>
                        <option v-for="(salon) in formData.salons" :value="salon">
                            {{salon.name + ', ' + salon.address}}
                        </option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="serviceSelect" class="form-label">Choose service: </label>
                    <select id="serviceSelect" class="form-select" v-model="formData.selectedService">
                        <option v-if="formData.loading">
                            <Loader :show="formData.loading" />
                        </option>
                        <option v-for="(service) in formData.services" :value="service">
                            {{service.name}}
                        </option>
                    </select>
                </div>

                <button type="button"
                        class="btn btn-success"
                        :disabled="formData.isButtonDisabled()"
                        @click="nextStep"
                >
                    Next
                </button>
            </form>

            <AppointmentDateTime
                :show="formData.showDateForm"
                :salon-address="formData.selectedSalon.address"
                :service="formData.selectedService"
                :disabled-dates="disabledDates"
                :working-hours="formData.workingHours"
            />
        </div>
        <div class="col"></div>
    </div>

</template>
