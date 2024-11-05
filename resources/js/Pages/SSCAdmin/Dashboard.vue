<template>
    <Head title="College"/>
    <AppLayout>
        <template v-slot:default>
            <!--Head-->
            <div>
                <h1 class="text-3xl font-semibold text-gray-800">Dashboard</h1>
            </div>
            <!--Content-->
            <div class="mt-3 space-y-3">
    
  
            </div>
        </template>
    </AppLayout>
 </template>
 
 <script setup>
    import { Menu, MenuButton, MenuItem, MenuItems } from '@headlessui/vue'
    import { ChevronDownIcon } from '@heroicons/vue/20/solid'
    import { Head, useForm, router } from '@inertiajs/vue3';
    import { ref, onMounted, computed } from 'vue';
    import { route } from 'ziggy-js';
    import AppLayout from '@/Layout/DashboardLayout.vue';

    //--------------------------------------------------------------------------

 </script>
 
 <style scoped>
    /* Home page specific styles */
 </style>
 