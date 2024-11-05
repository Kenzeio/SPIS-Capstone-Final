<template>
    <Head title="Student Playerss"/>
    <AppLayout>
        <template v-slot:default>
            <!--Head-->
            <div>
                <h1 class="text-3xl font-semibold text-gray-800">Player</h1>
                <h1 class="text-sm font-normal text-gray-700">List of Players</h1>
            </div>

            <!--Content-->
            <div class="mt-3 space-y-3">
                <!--Utility-->
                <div class="utility">
                    <div class="flex justify-between items-center">
                        <div class="search flex space-x-2 items-center">
                            <!-- Search Input -->
                            <div class="relative">
                                <!-- Search Icon (Left) -->
                                <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">
                                    <i class="fas fa-search"></i>
                                </span>

                                <!-- Input Field -->
                                <input
                                    v-model="searchQuery"
                                    type="text"
                                    placeholder="Search accounts..."
                                    class="w-64 pl-10 pr-10 py-2 bg-white shadow-sm border border-gray-300 focus:border-blue-500 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-300"
                                >

                                <!-- Clear Button (Right) -->
                                <button
                                    v-if="searchQuery"
                                    @click="clearSearch"
                                    class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors duration-200"
                                >
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                        <!--Buttons-->
                        <div class="flex items-center space-x-2.5">
                            <button @click="openModal(true)" class="bg-blue-800 text-white py-2 px-3 rounded-lg text-sm font-medium shadow hover:bg-blue-800/90 transition-colors">
                                <i class="fa-solid fa-square-plus mr-1"></i>   
                                Account                      
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Main Content, List of Accounts -->
                <div>

                </div>
            </div>

            <!-- Add Player Modal -->
            <div v-if="isModalOpen" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex justify-center items-center">
                <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-lg">
                    <h3 class="text-lg font-semibold mb-4">Add Player</h3>

                    <form @submit.prevent="submitForm">
                        <!-- Selected Students Display -->
                        <div class="mb-4">
                            <label class="text-gray-800 text-sm font-medium">
                                Selected Students ({{ selectedStudentIds.length }})
                            </label>
                            <div class="mt-1.5 grid grid-cols-4 items-center gap-3">
                                <div class="col-span-3 py-2 px-4 bg-gray-50 ring-1 ring-gray-300 rounded-lg text-sm">
                                    <span class="text-gray-700">
                                        {{ selectedStudentNames.length ? selectedStudentNames.join(', ') : 'No students selected' }}
                                    </span>
                                </div>
                                <button 
                                    type="button" 
                                    @click="openStudentModal" 
                                    class="col-span-1 bg-blue-700 hover:bg-blue-700/90 text-white py-2 px-3 rounded-lg text-sm font-medium shadow transition-colors"
                                >
                                    <i class="fa-solid fa-file mr-1"></i>   
                                    Select Students                          
                                </button>
                            </div>
                            <span v-if="form.errors.student_ids" class="text-red-500 text-sm">{{ form.errors.student_ids }}</span>
                        </div>

                        <!-- Note -->
                        <div class="p-2 mb-4 text-xs bg-blue-50 text-blue-700 rounded-lg">
                            <h1 class="font-bold mb-1">Note</h1>
                            <p>
                                To select students, click the <span class="font-semibold">Select Students</span> button and choose the students you want to assign to this sport.
                            </p>
                        </div>

                        <!-- Sport Selection -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium">Select Sport</label>
                            <select 
                                v-model="form.student_assigned_sport_id" 
                                class="w-full border px-3 py-2 rounded-lg"
                            >
                                <option value="" disabled>Select a sport</option>
                                <option 
                                    v-for="sport in availableSports" 
                                    :key="sport.id" 
                                    :value="sport.id"
                                >
                                    {{ sport.sport.name }}
                                </option>
                            </select>
                            <span v-if="form.errors.student_assigned_sport_id" class="text-red-500 text-sm">
                                {{ form.errors.student_assigned_sport_id }}
                            </span>
                        </div>

                        <!-- Form Buttons -->
                        <div class="flex justify-end text-sm">
                            <button 
                                type="button" 
                                @click="closeModal" 
                                class="mr-2 px-4 py-2 bg-gray-50 hover:bg-gray-100 rounded-lg"
                            >
                                Cancel
                            </button>
                            <button
                                type="submit"
                                :disabled="form.processing || !selectedStudentIds.length"
                                class="bg-blue-700 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm transition relative"
                            >
                                <span v-if="!form.processing">
                                    Create Players
                                </span>
                                <span v-else>
                                    <svg class="animate-spin h-4 w-4 mr-3 border-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.969 7.969 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Processing...
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Student Selection Modal -->
            <div v-if="isStudentModalOpen" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex justify-center items-center">
                <div class="bg-white p-6 rounded-lg shadow-lg h-[28rem] w-full max-w-md relative">
                    <h3 class="text-lg font-semibold mb-4">Select Students</h3>
                    
                    <!-- Search and Counter -->
                    <div class="grid grid-cols-5 items-center gap-3 mb-3">
                        <div class="col-span-3 relative">
                            <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">
                                <i class="fas fa-search"></i>
                            </span>
                            <input
                                v-model="studentSearchQuery"
                                type="text"
                                placeholder="Search students..."
                                class="w-full pl-10 pr-4 py-2 bg-white border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:outline-none"
                            >
                        </div>
                        <div class="col-span-2">
                            <span class="text-sm w-full py-2.5 rounded-lg text-blue-700 px-4 bg-blue-50">
                                Selected: <span class="font-bold">{{ selectedStudentIds.length }}</span>
                            </span>
                        </div>
                    </div>

                    <!-- Student List -->
                    <ul class="max-h-60 overflow-y-auto mb-4">
                        <li
                            v-for="student in filteredStudents"
                            :key="student.id"
                            @click="toggleStudentSelection(student)"
                            class="py-2 px-4 cursor-pointer hover:bg-gray-100 transition-colors flex items-center"
                            :class="{ 'bg-blue-100 hover:bg-blue-200': isStudentSelected(student.id) }"
                        >
                            <input
                                type="checkbox"
                                :checked="isStudentSelected(student.id)"
                                class="mr-2"
                                @click.stop
                            >
                            {{ student.first_name }}
                        </li>
                    </ul>

                    <!-- Modal Buttons -->
                    <div class="absolute bottom-6 right-6 flex justify-end">
                        <button 
                            type="button" 
                            @click="closeStudentModal" 
                            class="px-4 py-2 text-sm bg-gray-50 hover:bg-gray-100 rounded-lg"
                        >
                            Cancel
                        </button>
                        <button
                            type="button"
                            @click="confirmStudentSelection"
                            class="ml-2 bg-blue-700 hover:bg-blue-700/90 text-white text-sm px-4 py-2 rounded-lg"
                            :disabled="selectedStudentIds.length === 0"
                        >
                            Select ({{ selectedStudentIds.length }})
                        </button>
                    </div>
                </div>
            </div>
        </template>
    </AppLayout>
</template>

<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import AppLayout from '@/Layout/DashboardLayout.vue';

const props = defineProps({
    sports: Array,
    students: Array,
    assignedSports: Array,
    errors: Object
});

// Form and data management
const form = useForm({
    student_ids: [],
    student_assigned_sport_id: '',
});

// Available data
const availableStudents = ref(props.students || []);
const availableSports = ref(props.assignedSports);

// Modal states
const isModalOpen = ref(false);
const isStudentModalOpen = ref(false);

// Search and selection states
const searchQuery = ref('');
const studentSearchQuery = ref('');
const selectedStudentIds = ref([]);
const selectedStudentNames = ref([]);

// Computed properties
const filteredStudents = computed(() => {
    const query = studentSearchQuery.value.toLowerCase();
    return availableStudents.value.filter(student =>
        student.first_name.toLowerCase().includes(query)
    );
});

// Modal handlers
const openModal = () => {
    isModalOpen.value = true;
};

const closeModal = () => {
    isModalOpen.value = false;
    form.reset();
    selectedStudentIds.value = [];
    selectedStudentNames.value = [];
};

const openStudentModal = () => {
    isStudentModalOpen.value = true;
};

const closeStudentModal = () => {
    isStudentModalOpen.value = false;
};

// Student selection handlers
const toggleStudentSelection = (student) => {
    const idIndex = selectedStudentIds.value.indexOf(student.id);
    const nameIndex = selectedStudentNames.value.indexOf(student.first_name);
    
    if (idIndex === -1) {
        selectedStudentIds.value.push(student.id);
        selectedStudentNames.value.push(student.first_name);
    } else {
        selectedStudentIds.value.splice(idIndex, 1);
        selectedStudentNames.value.splice(nameIndex, 1);
    }
};

const isStudentSelected = (studentId) => {
    return selectedStudentIds.value.includes(studentId);
};

const confirmStudentSelection = () => {
    form.student_ids = selectedStudentIds.value;
    closeStudentModal();
};

const submitForm = () => {
    // Set the student_ids in the form before submission
    form.student_ids = selectedStudentIds.value;
    
    form.post(route('studentplayer.store'), {
        preserveScroll: true,
        onSuccess: () => {
            closeModal();
            selectedStudentIds.value = [];
            selectedStudentNames.value = [];
            // Show success message
            if (form.hasOwnProperty('flash') && form.flash.success) {
                alert(form.flash.success);
            }
        },
        onError: (errors) => {
            console.error('Form submission errors:', errors);
            // Show error message
            if (Object.keys(errors).length > 0) {
                alert('Error: ' + Object.values(errors)[0]);
            }
        },
    });
};
</script>

<style scoped>
/* Modal-specific styles */
</style>
