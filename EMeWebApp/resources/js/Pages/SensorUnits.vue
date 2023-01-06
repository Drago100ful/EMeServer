<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import DialogModal from "@/Components/DialogModal.vue";

import {ref} from "vue";
import {useForm, Link} from "@inertiajs/inertia-vue3";

const props = defineProps({
    SensorUnits: Array
});


const form = useForm({
    name: null,
    type: null,
    description: null,
    long: null,
    lat: null,
    auth_key: null,
})

const editForm = useForm({
    name: null,
    type: null,
    description: null,
    long: null,
    lat: null,
    auth_key: null,

})

let showModal = ref(false);

let editModal = ref(false);

let deleteModal = ref(false);
let deleteModalSensor = ref(null);

function showDeleteModal(unit) {
    deleteModalSensor = unit;
    deleteModal.value = true;
}

function showEditModal(unit) {
    editForm.name = unit.name;
    editForm.type = unit.type;
    editForm.description = unit.description;
    editForm.long = unit.long;
    editForm.lat  = unit.lat;
    editForm.auth_key = unit.auth_key;
    editModal.value = true;
}

function closeEditModal() {
    editForm.reset();
    editModal.value = false;
}

function getKey() {
    // Simple GET request using fetch
    fetch(route('sensors.generateKey'))
        .then(response => response.json())
        .then(data => (editForm.auth_key = data.auth_key));
}

function closeModal() {
    form.reset();
    showModal.value = false;
}

function closeDeleteModal() {
    deleteModalSensor = null;
    deleteModal.value = false;
}

function submitCreateForm() {
    form.post(route("sensors.store"));
    closeModal();
}

</script>

<template>
    <AppLayout title="Dashboard">
        <template #header>
            <div class="flex flex-row items-center justify-between">
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    Sensor Units
                </h2>
                <button class="bg-purple-500 px-2 py-1.5 rounded-md text-white font-bold flex items-center "
                        @click="showModal = true">
                    <svg class="mr-1 h-5 w-5" fill="currentColor" viewBox="0 0 20 20"
                         xmlns="http://www.w3.org/2000/svg">
                        <path clip-rule="evenodd"
                              d="M10 18a8 8 0 100-16 8 8 0 000 16zm.75-11.25a.75.75 0 00-1.5 0v2.5h-2.5a.75.75 0 000 1.5h2.5v2.5a.75.75 0 001.5 0v-2.5h2.5a.75.75 0 000-1.5h-2.5v-2.5z"
                              fill-rule="evenodd"/>
                    </svg>
                    Create
                </button>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <h1 v-if="SensorUnits.length > 0" class="mb-2 text-2xl font-bold">Your Sensor Units</h1>

                <div class="overflow-hidden bg-white shadow-xl sm:rounded-lg">
                    <div v-if="SensorUnits.length > 0">
                        <div>
                            <div v-for="(unit) in SensorUnits" class="border">
                                <div :class="[unit.active === true ? 'border-b' : '' ]"
                                     class="flex flex-row items-center justify-between bg-slate-50 px-3 py-1">
                                    <p class="font-bold">{{ unit.name }}</p>
                                    <div class="flex flex-row items-center gap-0.5">
                                        <button @click="showEditModal(unit)">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4 text-gray-500 transition hover:text-gray-900">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                            </svg>

                                        </button>
                                        <button @click="showDeleteModal(unit)">
                                            <svg class="h-4 w-4 text-gray-500 transition hover:text-gray-900"
                                                 fill="none" stroke="currentColor"
                                                 stroke-width="1.5" viewBox="0 0 24 24"
                                                 xmlns="http://www.w3.org/2000/svg"
                                            >
                                                <path
                                                    d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"/>
                                            </svg>
                                        </button>
                                        <button :class="[unit.active === true ? '-rotate-90' : '' ]" class="transition"
                                                @click="unit.active = !unit.active">
                                            <svg class="h-5 w-5" fill="currentColor"
                                                 viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                <path clip-rule="evenodd"
                                                      d="M12.79 5.23a.75.75 0 01-.02 1.06L8.832 10l3.938 3.71a.75.75 0 11-1.04 1.08l-4.5-4.25a.75.75 0 010-1.08l4.5-4.25a.75.75 0 011.06.02z"
                                                      fill-rule="evenodd"/>
                                            </svg>

                                        </button>
                                    </div>
                                </div>
                                <div v-if="unit.active" class="w-full">
                                    <div class="flex justify-between">
                                        <div class="w-full">
                                            <div class="flex flex-row px-3 py-1">
                                                <div class="mr-5">
                                                    <p>Type</p>
                                                    <p>Lat</p>
                                                    <p>Long</p>
                                                    <p>Description</p>
                                                    <p>Last packet</p>
                                                </div>
                                                <div>
                                                    <p>{{ unit.type }}</p>
                                                    <p>{{ unit.lat }}</p>
                                                    <p>{{ unit.long }}</p>
                                                    <p>{{ unit.description }}</p>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="w-full"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div v-else class="">
                        <h1 class="py-4 text-center text-3xl font-bold text-slate-700">There are no Sensor Units
                            associated
                            with your account</h1>
                        <button class="bg-purple-500 px-2 py-1.5 rounded-md text-white font-bold flex items-center "
                                @click="showModal = true">
                            <svg class="mr-1 h-5 w-5" fill="currentColor" viewBox="0 0 20 20"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path clip-rule="evenodd"
                                      d="M10 18a8 8 0 100-16 8 8 0 000 16zm.75-11.25a.75.75 0 00-1.5 0v2.5h-2.5a.75.75 0 000 1.5h2.5v2.5a.75.75 0 001.5 0v-2.5h2.5a.75.75 0 000-1.5h-2.5v-2.5z"
                                      fill-rule="evenodd"/>
                            </svg>
                            Create
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <DialogModal :show="showModal" @close="closeModal">
            <template #title class="">
                <div class="flex items-center justify-between">
                    <h1 class="text-2xl font-bold">Create a Sensor Unit</h1>
                    <button @click="closeModal">
                        <svg class="h-6 w-6 text-slate-600 transition hover:text-slate-900" fill="none"
                             stroke="currentColor" stroke-width="1.5"
                             viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path d="M6 18L18 6M6 6l12 12" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>

                    </button>
                </div>

            </template>
            <template #content>
                <div class="">
                    <form id="SensorCreateForm" class="flex flex-col" @submit.prevent="submitCreateForm">
                        <label class="mb-1 text-lg font-semibold" for="SensorUnitName">Name</label>
                        <input id="SensorUnitName" v-model="form.name"
                               class="mb-2 appearance-none rounded transition focus:border-purple-500 focus:ring-0"
                               maxlength="32"
                               required
                               type="text">

                        <label class="mb-1 text-lg font-semibold" for="SensorUnitType">Type</label>
                        <input id="SensorUnitType" v-model="form.type"
                               class="mb-2 appearance-none rounded transition focus:border-purple-500 focus:ring-0"
                               required
                               type="text">

                        <label class="mb-1 text-lg font-semibold" for="SensorUnitDescription">Description</label>
                        <textarea id="SensorUnitDescription" v-model="form.description"
                                  class="mb-2 resize-none appearance-none rounded transition focus:border-purple-500 focus:ring-0"
                                  maxlength="256"
                                  rows="4"></textarea>

                        <label class="mb-1 text-lg font-semibold" for="SensorUnitLong">Longitude</label>
                        <input id="SensorUnitLong" v-model="form.long"
                               class="mb-2 appearance-none rounded transition focus:border-purple-500 focus:ring-0"
                               required
                               step="0.0000001"
                               type="number">

                        <label class="mb-1 text-lg font-semibold" for="SensorUnitLat">Latitude</label>
                        <input id="SensorUnitLat" v-model="form.lat"
                               class="mb-2 appearance-none rounded transition focus:border-purple-500 focus:ring-0"
                               required
                               step="0.0000001"
                               type="number">

                    </form>
                </div>
            </template>
            <template #footer>
                <input class="rounded-md bg-purple-500 px-3 py-1 font-bold text-white transition hover:bg-purple-600"
                       form="SensorCreateForm" type="submit"
                       value="Create">
            </template>

        </DialogModal>

        <DialogModal :show="editModal" @close="closeEditModal">
            <template #title class="">
                <div class="flex items-center justify-between">
                    <h1 class="text-2xl font-bold">Create a Sensor Unit</h1>
                    <button @click="closeEditModal">
                        <svg class="h-6 w-6 text-slate-600 transition hover:text-slate-900" fill="none"
                             stroke="currentColor" stroke-width="1.5"
                             viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path d="M6 18L18 6M6 6l12 12" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>

                    </button>
                </div>

            </template>
            <template #content>
                <div class="">
                    <form id="SensorUpdateForm" class="flex flex-col" @submit.prevent="">
                        <label class="mb-1 text-lg font-semibold" for="SensorUnitName">Name</label>
                        <input id="SensorUnitName" v-model="editForm.name"
                               class="mb-2 appearance-none rounded transition focus:border-purple-500 focus:ring-0"
                               maxlength="32"
                               required
                               type="text">

                        <label class="mb-1 text-lg font-semibold" for="SensorUnitType">Type</label>
                        <input id="SensorUnitType" v-model="editForm.type"
                               class="mb-2 appearance-none rounded transition focus:border-purple-500 focus:ring-0"
                               required
                               type="text">

                        <label class="mb-1 text-lg font-semibold" for="SensorUnitDescription">Description</label>
                        <textarea id="SensorUnitDescription" v-model="editForm.description"
                                  class="mb-2 resize-none appearance-none rounded transition focus:border-purple-500 focus:ring-0"
                                  maxlength="256"
                                  rows="4"></textarea>

                        <label class="mb-1 text-lg font-semibold" for="SensorUnitLong">Longitude</label>
                        <input id="SensorUnitLong" v-model="editForm.long"
                               class="mb-2 appearance-none rounded transition focus:border-purple-500 focus:ring-0"
                               required
                               step="0.0000001"
                               type="number">

                        <label class="mb-1 text-lg font-semibold" for="SensorUnitLat">Latitude</label>
                        <input id="SensorUnitLat" v-model="editForm.lat"
                               class="mb-2 appearance-none rounded transition focus:border-purple-500 focus:ring-0"
                               required
                               step="0.0000001"
                               type="number">
                            <label class="mb-1 text-lg font-semibold" for="SensorUnitAuthKey">Authentication Key</label>
                            <div class="flex flex-row gap-2 items-center">
                                <input id="SensorUnitAuthKey" v-model="editForm.auth_key"
                                       class=" appearance-none rounded transition focus:border-purple-500 focus:ring-0 w-full"
                                       required
                                       readonly
                                       type="text">
                                <button @click="getKey" class="rounded-md bg-purple-500 px-3 py-2 font-bold text-white transition hover:bg-purple-600 inline-flex items-center gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                                        <path fill-rule="evenodd" d="M15.312 11.424a5.5 5.5 0 01-9.201 2.466l-.312-.311h2.433a.75.75 0 000-1.5H3.989a.75.75 0 00-.75.75v4.242a.75.75 0 001.5 0v-2.43l.31.31a7 7 0 0011.712-3.138.75.75 0 00-1.449-.39zm1.23-3.723a.75.75 0 00.219-.53V2.929a.75.75 0 00-1.5 0V5.36l-.31-.31A7 7 0 003.239 8.188a.75.75 0 101.448.389A5.5 5.5 0 0113.89 6.11l.311.31h-2.432a.75.75 0 000 1.5h4.243a.75.75 0 00.53-.219z" clip-rule="evenodd" />
                                    </svg>


                                    Generate
                                </button>
                            </div>
                    </form>
                </div>
            </template>
            <template #footer>
                <input class="rounded-md bg-purple-500 px-3 py-1 font-bold text-white transition hover:bg-purple-600"
                       form="SensorUpdateForm" type="submit"
                       value="Update">
            </template>

        </DialogModal>

        <DialogModal :show="deleteModal" @close="closeDeleteModal">
            <template #title>
                <h1 class="text-2xl font-bold">Delete Sensor Unit</h1>
            </template>

            <template #content>
                <p>You are about to delete <span class="font-bold">{{ deleteModalSensor.name }}</span>.
                    This will also delete <span class="font-bold">all records</span> attached to <span
                        class="font-bold">{{ deleteModalSensor.name }}</span>.</p><br>
                <p>Are you sure you want to proceed?</p>
            </template>

            <template #footer>
                <div class="flex justify-end gap-2">
                    <button @click="closeDeleteModal" class="rounded-md border bg-gray-50 px-3 py-1 shadow transition hover:bg-gray-100">Cancel
                    </button>
                    <Link
                        as="button" type="button" method="delete" :href="route('sensors.destroy', deleteModalSensor.id)"
                        class="rounded-md bg-red-600 px-3 py-1 font-bold text-white shadow transition hover:bg-red-500 ">
                        Delete
                    </Link>


                </div>
            </template>
        </DialogModal>
    </AppLayout>
</template>