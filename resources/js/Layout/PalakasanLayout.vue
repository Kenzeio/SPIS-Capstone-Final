<template>
    <Head title="Palakasan" />
    <AppLayout>
        <!-- Page Title -->
        <h1 class="text-3xl font-semibold text-gray-800">Palakasan</h1>
        <p class="text-sm font-normal text-gray-700">Palakasan details and overview</p>

        <div class="flex">
            <nav class="mt-4 bg-gray-100 rounded-lg py-2 px-1.5">
                <ul class="flex space-x-2">
                    <li>
                        <Link 
                            href="/palakasan/details" 
                            class="items-center py-1.5 px-3 text-sm rounded-md"
                            :class="{
                                'bg-blue-700 text-white': currentRoute.startsWith('/palakasan/details'), 
                                'text-gray-600 hover:bg-gray-200 hover:text-gray-800': !currentRoute.startsWith('/palakasan/details')
                            }"
                        >
                            <i class="fa-solid fa-file-invoice"></i>
                            <span class="ms-2">Details</span>
                        </Link>
                    </li>
                    <li>
                        <Link 
                            href="/palakasan/team" 
                            class="items-center py-1.5 px-3 text-sm rounded-md"
                            :class="{
                                'bg-blue-700 text-white': currentRoute.startsWith('/palakasan/team'), 
                                'text-gray-600 hover:bg-gray-200 hover:text-gray-800': !currentRoute.startsWith('/palakasan/team')
                            }"
                        >
                            <i class="fa-solid fa-user-group"></i>
                            <span class="ms-2">Teams</span>
                        </Link>
                    </li>
                    <li>
                        <Link 
                            href="/palakasan/sportselection" 
                            class="items-center py-1.5 px-3 text-sm rounded-md"
                            :class="{
                                'bg-blue-700 text-white': currentRoute.startsWith('/palakasan/sportselection'), 
                                'text-gray-600 hover:bg-gray-200 hover:text-gray-800': !currentRoute.startsWith('/palakasan/sportselection')
                            }"
   
                        >
                            <i class="fa-solid fa-basketball"></i>
                            <span class="ms-2">Sports</span>
                        </Link>
                    </li>

                </ul>
            </nav>
        </div>

        <!-- Slot for Details Content (show by default when on /palakasan or /palakasandetails) -->
        <div class="mt-3">
            <slot></slot>
        </div>
    </AppLayout>
</template>

<script setup>
import { Head, Link, usePage } from '@inertiajs/vue3';
import { defineProps } from 'vue';
import AppLayout from '@/Layout/DashboardLayout.vue';

    const { url: currentRoute } = usePage(); // current URL is stored in currentRoute
</script>

<style scoped>

</style>
