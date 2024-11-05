<template>
    <Head title="Students"/>
    <AppLayout>
        <template v-slot:default>
            <!-- Head -->
            <div>
                <h1 class="text-3xl font-semibold text-gray-800">Students</h1>
                <h1 class="text-sm font-normal text-gray-700">List of Students</h1>
            </div>
            <!--Alert-->
            <transition name="slide-down">
                <div
                    v-if="showSuccessAlert"
                    class="fixed top-2 right-2 w-1/2 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg z-50 flex justify-between items-center shadow-lg"
                    role="alert"
                >
                    <div>
                        <strong class="font-bold">Success!</strong>
                        <span class="block sm:inline"> Students data has been successfully imported.</span>
                    </div>
                    <span @click="showSuccessAlert = false" class="cursor-pointer">
                        <svg class="fill-current h-6 w-6 text-green-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                            <title>Close</title>
                            <path
                                d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"
                            />
                        </svg>
                    </span>
                </div>
            </transition>

            <!-- Content -->
            <div class="mt-3 space-y-3">
                <!-- Utility -->
                <div class="utility">
                    <div class="flex justify-between items-center">
                        <!-- Search -->
                        <div class="search flex space-x-2 items-center">
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">
                                    <i class="fas fa-search"></i>
                                </span>
                                <input
                                    v-model="searchQuery"
                                    type="text"
                                    placeholder="Search student..."
                                    class="w-64 pl-10 pr-10 py-2 bg-white shadow border border-gray-300 focus:border-blue-500 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-300"
                                >
                                <button
                                    v-if="searchQuery"
                                    @click="clearSearch"
                                    class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors duration-200"
                                >
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Buttons -->
                        <div class="flex items-center space-x-2.5">
                            <!-- Import Button to open the modal -->
                            <button @click="showModal = true" class="bg-blue-800 text-white py-2 px-3 rounded-lg text-sm font-medium shadow hover:bg-blue-800/90 transition-colors">
                                <i class="fa-solid fa-file-import mr-1"></i> 
                                Import                             
                            </button>
  
                            <button class="tooltip-btn bg-white text-gray-800 p-2 text-sm rounded-lg ring-1 ring-gray-300 shadow hover:bg-gray-100 transition-colors" data-tooltip="Logs">
                                <i class="fa-solid fa-envelopes-bulk"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Updated table with pagination -->
                <div>
                    <div class="border rounded-lg border-gray-300 shadow">
                    <table class="min-w-full">
                        <thead>
                            <tr class=" text-blue-700">                                    
                                <th class="py-2 px-4 text-left border-b rounded-t-lg">ID</th>
                                <th class="py-2 px-4 text-left border-b">Name</th>
                                <th class="py-2 px-4 text-left border-b">Student ID</th>
                                <th class="py-2 px-4 text-left border-b">University Email</th>    
                                <th class="py-2 px-4 text-left border-b">College</th>   
                                <th class="py-2 px-4 text-left border-b">Contact</th>                                    
                                <th class="py-2 px-4 text-left border-b  rounded-t-lg">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-if="paginatedStudents.length === 0">
                                <td colspan="7" class="py-4 px-4 text-center text-gray-600">No student found.</td>
                            </tr>
                            <tr v-else v-for="student in paginatedStudents" :key="student.id" class="bg-white hover:bg-gray-50">
                                <td class="py-2 px-4 text-sm text-gray-600 border-b">{{ student.id }}</td>
                                <td class="py-2 px-4 text-sm text-gray-800  font-semibold border-b">{{student.first_name}} {{ student.last_name }}</td>
                                <td class="py-2 px-4 text-sm text-gray-600 border-b">{{ student.id_number }}</td>
                                <td class="py-2 px-4 text-sm text-gray-600 border-b">{{ student.univ_email }}</td>
                                <td class="py-2 px-4 text-sm text-gray-600 border-b">{{ student.college }}</td>
                                <td class="py-2 px-4 text-sm text-gray-600 border-b">{{ student.contact }}</td>

                                <td class="py-2 px-4 text-sm text-gray-600 border-b">
                                    <Menu as="div" class="relative inline-block text-left">
                                        <MenuButton class="tooltip-btn inline-flex items-center justify-center w-full rounded-lg px-2.5 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50" data-tooltip="Sort">
                                            <i class="fa-solid fa-ellipsis"></i>                                    
                                        </MenuButton>
                                        <transition
                                        enter-active-class="transition ease-out duration-100"
                                        enter-from-class="transform opacity-0 scale-95"
                                        enter-to-class="transform opacity-100 scale-100"
                                        leave-active-class="transition ease-in duration-75"
                                        leave-from-class="transform opacity-100 scale-100"
                                        leave-to-class="transform opacity-0 scale-95"
                                        >
                                        <MenuItems class="origin-top-right absolute right-0 z-10 mt-2 w-36 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none">
                                            <div class="py-1">
                                            <MenuItem v-slot="{ active }">
                                                <a href="#" :class="[active ? 'bg-gray-100 text-gray-900' : 'text-gray-700', 'block px-4 py-2 text-sm']">
                                                Default
                                                </a>
                                            </MenuItem>
                                            <MenuItem v-slot="{ active }">
                                                <a href="#" :class="[active ? 'bg-gray-100 text-gray-900' : 'text-gray-700', 'block px-4 py-2 text-sm']">
                                                A-Z
                                                </a>
                                            </MenuItem>
                                            <MenuItem v-slot="{ active }">
                                                <a href="#" :class="[active ? 'bg-gray-100 text-gray-900' : 'text-gray-700', 'block px-4 py-2 text-sm']">
                                                Z-A
                                                </a>
                                            </MenuItem>
                                            </div>
                                        </MenuItems>
                                        </transition>
                                    </Menu>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="p-4 flex justify-between items-center">
                        <p class="text-sm text-gray-700 font-medium">
                            Total number of students: <span class="font-bold">{{ filteredStudents.length }}</span>
                        </p>
                        <div class="space-x-2 flex items-center">
                        <button 
                            @click="prevPage"
                            :disabled="currentPage === 1"
                            :class="{'opacity-50 cursor-not-allowed': currentPage === 1}"
                            type="button" 
                            class="py-2 px-5 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700"
                        >
                            <i class="fa-solid fa-chevron-left mr-1"></i>
                            Prev
                        </button>
                        
                        <div class="text-sm font-medium text-gray-700">
                            Page {{ currentPage }} of {{ totalPages }}
                        </div>
                        
                        <button 
                            @click="nextPage"
                            :disabled="currentPage === totalPages"
                            :class="{'opacity-50 cursor-not-allowed': currentPage === totalPages}"
                            type="button" 
                            class="py-2 px-5 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg  hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700"
                        >
                            Next
                            <i class="fa-solid fa-chevron-right ml-1"></i>
                        </button>
                    </div>
                    </div>
                </div>

                </div>
            </div>

            <!-- Modal for File Import -->
            <div v-if="showModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                <div class="bg-white p-6 rounded-lg shadow-lg w-1/3">
                    <h2 class="text-xl font-semibold mb-4">Import Students</h2>
                    <form @submit.prevent="importFile">

                        <div class="flex items-center justify-center w-full">
                            <label for="dropzone-file" class="flex flex-col w-full p-4 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition">
                                <div class="flex flex-col ">

                                    <div v-if="!selectedFile" class="flex gap-2">
                                        <i class="fa-solid fa-file-arrow-up text-xl py-2 px-3 rounded-md text-gray-600 bg-gray-200"></i>
                                        <p class="text-sm text-gray-700 font-normal dark:text-gray-400">Excel files that are accepted are only in this format : <br> 
                                        <span class="font-semibold">xlsx or xls</span></p>
                                    </div>
                                    <!-- Display selected file with an icon and a remove button -->
                                    <div v-if="selectedFile" class="flex items-center justify-between w-full bg-white border border-gray-300 rounded-lg">
                                        <span class="flex items-center text-sm p-2 text-gray-800">
                                            <i class="fa-solid bg-blue-700 py-1.5 px-2.5 text-white rounded text-sm fa-file-excel mr-2"></i> 
                                            <span class="font-semibold">{{ selectedFile }}</span>
                                        </span>
                                        <button type="button" @click="clearFile" class="text-red-500 hover:text-red-700 px-4 py-1.5 focus:outline-none">
                                            <i class="fa-solid fa-xmark"></i>
                                        </button>
                                    </div>
                                </div>
                                <!-- Accept only xlsx and xls file formats -->
                                <input id="dropzone-file" @change="handleFileUpload" type="file" required accept=".xlsx,.xls" class="hidden" />
                            </label>
                        </div> 
                        <div class="note mt-2 bg-blue-50 p-4 rounded-lg text-xs text-blue-700">
                            <div class="flex justify-between items-center mb-2">
                                <p class="text-sm font-semibold">Info</p>
                                <i class="fa-solid fa-circle-info"></i>
                            </div>
                            <h1>When importing an excel file make sure it follows a specific format so that there would be no problem that will happen in the later time, the format should be as followed: <span class="font-semibold">Name, E-mail, and contact number</span>. </h1>
                        </div>

                        <div class="flex justify-end mt-4 space-x-2">
                            <button type="button" @click="showModal = false" class="bg-gray-100 text-gray-800 py-2 px-4 rounded-lg text-sm hover:bg-gray-200 transition-colors">Cancel</button>
                            <button 
                                type="submit"
                                :disabled="form.processing"
                                class="bg-blue-800 text-white py-2 px-4 rounded-lg hover:bg-blue-700 text-sm transition-colors">
                                <span v-if="form.processing">importing...</span>
                                <span v-else>Import</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </template>
    </AppLayout>
</template>

<script setup>

    import { ref, computed, onMounted } from 'vue';
    import { Head, router, useForm } from '@inertiajs/vue3';
    import { route } from 'ziggy-js';
    import { Menu, MenuButton, MenuItem, MenuItems } from '@headlessui/vue'
    import { ChevronDownIcon } from '@heroicons/vue/20/solid'
    import AppLayout from '@/Layout/DashboardLayout.vue';

    const props = defineProps({
        students: Array,
        errors: Object,
        flash: Object
    });

    const searchQuery = ref('');
    const showModal = ref(false);
    const selectedFile = ref('');
    const currentPage = ref(1);
    const itemsPerPage = 25;
    const showSuccessAlert = ref(false);

    // Define the form object using useForm
    const form = useForm({
        file: null,
    });

    const handleFileUpload = (event) => {
        const file = event.target.files[0];
        if (file) {
            selectedFile.value = file.name;
            form.file = file;
        }
    };

    const clearFile = () => {
        selectedFile.value = '';
        document.getElementById('dropzone-file').value = '';
        form.file = null;
    };

    const importFile = () => {
        if (form.file) {
            form.post(route('student.import'), {
                preserveState: true,
                preserveScroll: true,
                onSuccess: () => {
                    showModal.value = false;
                    selectedFile.value = '';
                    showSuccessAlert.value = true;
                    router.reload({ only: ['students'] });
                    setTimeout(() => {
                        showSuccessAlert.value = false;
                    }, 4000); // Hide the alert after 5 seconds
                },
                onError: (errors) => {
                    console.error(errors);
                    alert('There was an error importing the file. Please try again.');
                }
            });
        } else {
            alert('Please select a file to import.');
        }
    };

    // Check for flash message on component mount
    onMounted(() => {
        if (props.flash && props.flash.message) {
            showSuccessAlert.value = true;
            setTimeout(() => {
                showSuccessAlert.value = false;
            }, 4000);
        }
    });
    // Computed property to filter students based on the search query
    const filteredStudents = computed(() => {
        if (!searchQuery.value) return props.students;
        return props.students.filter(student =>
            student.fullname.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
            student.email.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
            student.contact.toLowerCase().includes(searchQuery.value.toLowerCase())
        );
    });

    // Computed property to get paginated students
    const paginatedStudents = computed(() => {
        const start = (currentPage.value - 1) * itemsPerPage;
        const end = start + itemsPerPage;
        return filteredStudents.value.slice(start, end);
    });

    // Computed property to get total number of pages
    const totalPages = computed(() => Math.ceil(filteredStudents.value.length / itemsPerPage));

    // Function to go to next page
    const nextPage = () => {
        if (currentPage.value < totalPages.value) {
            currentPage.value++;
        }
    };

    // Function to go to previous page
    const prevPage = () => {
        if (currentPage.value > 1) {
            currentPage.value--;
        }
    };

    const clearSearch = () => {
        searchQuery.value = '';
        currentPage.value = 1; // Reset to first page when clearing search
    };
</script>

<style scoped>
    /* Slide-down transition */
    .slide-down-enter-active, .slide-down-leave-active {
    transition: all 0.5s ease;
    }
    .slide-down-enter-from {
    transform: translateY(-50px);
    opacity: 0;
    }
    .slide-down-enter-to {
    transform: translateY(0);
    opacity: 1;
    }
    .slide-down-leave-from {
    transform: translateY(0);
    opacity: 1;
    }
    .slide-down-leave-to {
    transform: translateY(-50px);
    opacity: 0;
    }
</style>
