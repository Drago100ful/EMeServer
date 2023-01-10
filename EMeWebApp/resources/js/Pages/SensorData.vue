<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import DialogModal from "@/Components/DialogModal.vue";

import {ref} from "vue";
import {Inertia} from "@inertiajs/inertia";



const props = defineProps({
    SensorUnit: Object,
    SensorData: Object
});

const currentPage = props.SensorData.current_page;
const lastPage = props.SensorData.last_page;

function prevPage() {
    if(currentPage > 1) {
        Inertia.visit(route('sensors.data.index', {
            sensor: props.SensorUnit.id,
            _query: {
                page: currentPage-1
            }
        }));
    }
}

function nextPage() {
    if(currentPage < lastPage) {
        Inertia.visit(route('sensors.data.index', {
            sensor: props.SensorUnit.id,
            _query: {
                page: currentPage+1
            }
        }));
    }
}
</script>

<template>
    <AppLayout title="Dashboard">
        <template #header>
            <div class="flex flex-row items-center justify-between">
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    Sensor Data
                </h2>
            </div>
        </template>
        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">

                <h1 class="text-2xl font-bold mb-4" v-if="SensorData.data.length > 0">
                    Entries associated with {{SensorUnit.name}}
                </h1>

                <div class="overflow-hidden bg-white shadow-xl sm:rounded-lg">
                    <div v-if="SensorData.data.length > 0">
                        <div>
                            <div v-for="(data) in SensorData.data" class="border" :class="[parseInt(data.value) >= parseInt(SensorUnit.threshold) ? 'bg-red-200' : 'odd:bg-gray-50 even:bg-gray-100']">
                                <div class="flex flex-row items-center justify-between px-3 py-1.5">
                                    <p>{{data.measured_at}}</p>
                                    <p>{{data.created_at}}</p>
                                    <p>{{data.type}}</p>
                                    <p>{{data.value}}</p>
                                    <p></p>
                                </div>
                            </div>

                        </div>

                    </div>

                    <div v-else class="">
                        <h1 class="py-4 text-center text-3xl font-bold text-slate-700">There are no Data Entries
                            associated
                            with this Sensor unit</h1>

                    </div>

                </div>

                <div class="flex items-center justify-center mt-4" v-if="SensorData.data.length > 0">
                    <button @click="prevPage">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                        </svg>
                    </button>
                    <p class="text-xl">
                        {{currentPage}}
                    </p>
                    <button @click="nextPage">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                        </svg>

                    </button>
                </div>
            </div>
        </div>

    </AppLayout>
</template>
