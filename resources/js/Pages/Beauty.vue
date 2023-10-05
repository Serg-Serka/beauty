<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import axios from "axios";
import { reactive, ref } from 'vue';
import { DatePicker } from 'v-calendar';
import 'v-calendar/style.css';
import 'bootstrap/dist/css/bootstrap.css';
import Modal from "@/Components/Modal.vue";

const formData = reactive({
    salons: [],
    setSalons(data) {
        this.salons = data;
    },

    selectedSalon: '',
    // setSelectedSalon(salon) {
    //     this.selectedSalon = salon;
    // },

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

    showWorkingHours: false,
    setShowWorkingHours(status) {
        this.showWorkingHours = status;
    },

    notAvailableHours: [],
    setNotAvailableHours(hours) {
        this.notAvailableHours = hours;
    },


    isHourButtonDisabled(hour) {
        return this.notAvailableHours.includes(hour);
    },

    activeModal: 0,
    setActiveModal(id) {
        this.activeModal = id;
    },
});

const modalFormData = reactive({
    name: '',
    phone: '',

    isMakeRecordBtnDisabled() {
        return !(this.name !== '' && this.phone !== '');
    },

    makeRecordBtnLabel: 'Make record',
    setMakeRecordBtnLabel(label) {
        this.makeRecordBtnLabel = label;
    },

    showForm: true,
    setShowForm(status) {
        this.showForm = status;
    },

    // makeRecordBtnAction: this.makeRecord,
    // setMakeRecordBtnAction(action) {
    //     this.makeRecordBtnAction = action;
    // },

    makeRecord() {
        if (this.showForm) {
            axios.post("/beauty/makeRecord", {
                name: this.name,
                phone: this.phone,
                service: formData.selectedService.id,
                year: date.value.getFullYear(),
                month: date.value.getMonth() + 1,
                day: date.value.getDate(),
                hour: formData.activeModal
            })
                .then(res => {
                    if (res.data.success) {
                        console.log(res.data);
                        this.setMakeRecordBtnLabel('OK');
                        this.setShowForm(false);
                    }
                });
        } else {
            document.location.href = "/beauty";
        }
    },
});

let date = ref(null);
let timeAccuracy = ref(2);
let rules = ref({
    minutes: {interval: 30}
});
let popover = ref({
    visibility: 'click',
    placement: 'bottom',
});

let weekdays = [1, 2, 3, 4, 5, 6, 7];

let disabledDates = ref([]);

let showModal = (id) => {
    return formData.activeModal === id;
};

let toggleModal = (id) => {
    if(formData.activeModal !== 0) {
        formData.activeModal = 0;
        return false;
    }
    formData.activeModal = id;
}

let formUpdate = () => {
    // console.log(formData);
    formData.setLoading(true);
    formData.setSelectedService('');
    if (formData.selectedSalon.id && typeof formData.selectedService !== 'undefined') {
        axios.post("/beauty/getServices", {
            salonId: formData.selectedSalon.id
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

let nextStep = () => {
    formData.setShowDateForm(true);
    let workingDays = formData.selectedService.working_days.split(',').map(el => parseInt(el));
    let difference = weekdays.filter(el => !workingDays.includes(el));
    formData.setWorkingHours(formData.selectedService.working_hours.split(','));
    let disabledDays = [];
    let currentDate = new Date();
    // console.log(formData.workingHours);
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
    <Head title="Beauty" />
    <h1>Beauty form goes here!</h1>

    <form v-if="!formData.showDateForm">
        <select v-model="formData.selectedSalon" @change="formUpdate">
            <option v-for="(salon) in formData.salons" :value="salon">
                {{salon.name + ', ' + salon.address}}
            </option>
        </select>

        <select v-model="formData.selectedService">
            <option v-for="(service) in formData.services" :value="service">
                {{service.name}}
            </option>
        </select>

        <button type="button"
                class="btn btn-dark"
                :disabled="formData.isButtonDisabled()"
                @click="nextStep"
        >
            Next ->
        </button>
    </form>

    <form v-if="formData.showDateForm">
        <label for="datePicker">
            Choose date:
        </label>
        <DatePicker id="datePicker"
                    v-model="date"
                    mode="date"
                    :time-accuracy="timeAccuracy"
                    :rules="rules"
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
                <li v-for="hour in formData.workingHours" :key="hour">
                    <div>
                        {{hour}}
                        <button type="button"
                                class="btn btn-success"
                                :disabled="formData.isHourButtonDisabled(hour)"
                                @click.stop="toggleModal(hour)"
                        >
                            Make record!
                        </button>
                        <Modal :show="showModal(hour)" @close="toggleModal(hour)" >
                            <div class="p-6">
                                <div v-if="modalFormData.showForm">
                                    <h1>Modal for {{hour}}!!!</h1>
                                    <form>
                                        <label for="name">
                                            Name:
                                        </label>
                                        <input v-model="modalFormData.name" type="text" id="name" />
                                        <label for="phone">
                                            Phone:
                                        </label>
                                        <input v-model="modalFormData.phone" type="text" id="phone" />
                                    </form>
                                </div>

                                <div v-if="!modalFormData.showForm">
                                    <h1>Record booked!</h1>
                                    <h3>{{date}}</h3>
                                    <br/>
                                    <h3>{{formData.selectedSalon.address}}</h3>
                                    <br/>
                                    <h3>{{formData.selectedService.name}}</h3>
                                </div>

                                <button class="btn btn-success"
                                        :disabled="modalFormData.isMakeRecordBtnDisabled()"
                                        type="button"
                                        @click="modalFormData.makeRecord"
                                >
                                    {{modalFormData.makeRecordBtnLabel}}
                                </button>

                            </div>
                        </Modal>
                    </div>
                </li>
            </ul>
        </div>
    </form>
</template>
