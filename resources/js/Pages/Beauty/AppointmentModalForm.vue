<script setup>
import Modal from "@/Components/Modal.vue";
import {reactive} from "vue";
import axios from "axios";

let props = defineProps({
    hour: {
        type: String
    },

    isHourBtnDisabled: {
        type: Boolean,
        default: false
    },

    date: {
        type: Date
    },

    salonAddress: {
        type: String
    },

    service: {
        type: Object
    },
});

let modalForm = reactive({
    activeModal: 0,
    name: '',
    phone: '',

    showForm: true,
    setShowForm(status) {
        this.showForm = status;
    },

    isMakeRecordBtnDisabled() {
        return !(this.name !== '' && this.phone !== '');
    },

    makeRecordBtnLabel: 'Record',
    setMakeRecordBtnLabel(label) {
        this.makeRecordBtnLabel = label;
    },
});

let makeRecord = () => {
    if (modalForm.showForm) {
        axios.post("/beauty/makeRecord", {
            name: modalForm.name,
            phone: modalForm.phone,
            service: props.service.id,
            year: props.date.getFullYear(),
            month: props.date.getMonth() + 1,
            day: props.date.getDate(),
            hour: modalForm.activeModal
        })
            .then(res => {
                if (res.data.success) {
                    modalForm.setMakeRecordBtnLabel('OK');
                    modalForm.setShowForm(false);
                }
            });
    } else {
        document.location.href = "/beauty";
    }
};

let toggleModal = (id) => {
    if(modalForm.activeModal !== 0) {
        modalForm.activeModal = 0;
        return false;
    }
    modalForm.activeModal = id;
};

let showModal = (id) => {
    return modalForm.activeModal === id;
};
</script>
<template>
    <div>
        <span class="align-middle">
            {{hour}}
        </span>
        <button type="button"
                class="btn btn-success float-right"
                :disabled="isHourBtnDisabled"
                @click.stop="toggleModal(hour)"
        >
            Make record!
        </button>
        <Modal :show="showModal(hour)" @close="toggleModal(hour)" >
            <div class="p-6">
                <div v-if="modalForm.showForm">
                    <h4>Make record for {{hour}}</h4>
                    <form>
                        <div class="mb-3">
                            <label for="name" class="form-label">
                                Name:
                            </label>
                            <input v-model="modalForm.name" type="text" id="name" class="form-control"/>
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label">
                                Phone:
                            </label>
                            <input v-model="modalForm.phone" type="text" id="phone" class="form-control" />
                        </div>
                    </form>
                </div>

                <div v-if="!modalForm.showForm">
                    <h4>Record booked!</h4>
                    <h5>Date: {{date.getDate() + '-' + (date.getMonth() + 1) + '-' + date.getFullYear() + ' ' + hour}}</h5>
                    <h5>Salon address: {{salonAddress}}</h5>
                    <h5>Service: {{service.name}}</h5>
                </div>

                <button class="btn btn-success"
                        :disabled="modalForm.isMakeRecordBtnDisabled()"
                        type="button"
                        @click="makeRecord"
                >
                    {{modalForm.makeRecordBtnLabel}}
                </button>

            </div>
        </Modal>
    </div>
</template>
