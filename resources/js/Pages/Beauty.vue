<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import axios from "axios";
import { reactive } from 'vue';

const formData = reactive({
    salons: [],
    setSalons(data) {
        this.salons = data;
    },

    selectedSalon: '',
    setSelectedSalon(salon) {
        this.selectedSalon = salon;
    },

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

    nextStep: false
});

// const form = useForm({
//     salon: '',
//     service: ''
// });

let formUpdate = () => {
    formData.setLoading(true);
    if (formData.selectedSalon && typeof formData.selectedService !== 'undefined') {
        axios.post("/beauty/getServices", {
            salonId: formData.selectedSalon
        })
            .then(result => {
                // console.log(result.data);
                if (result.data.success) {
                    formData.setServices(result.data.services);
                    formData.setLoading(false);
                }
            });
    }
};

axios.post("/beauty/getSalons")
    .then(result => {
        if (result.data.success) {
            formData.setSalons(result.data.salons);
            formData.setLoading(false);
        }
    })

</script>

<template>
    <Head title="Beauty" />
    <h1>Beauty form goes here!</h1>

    <form>
        <select v-model="formData.selectedSalon" @change="formUpdate">
            <option v-for="(salon) in formData.salons" :value="salon.id">
                {{salon.name + ', ' + salon.address}}
            </option>
        </select>

        <select v-model="formData.selectedService">
            <option v-for="(service) in formData.services" :value="service.id">
                {{service.name}}
            </option>
        </select>

        <button type="button">Next -></button>
    </form>

    <form v-if="formData.nextStep">


    </form>
</template>



