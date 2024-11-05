<template>
    <div>
        <!-- Navbar -->
        <nav class="bg-blue-700 fixed top-0 left-0 right-0 z-50">
            <div class="px-6">
                <div class="flex justify-between h-16">
                    <div class="flex-shrink-0 flex space-x-3 items-center">
                        <img class="w-10 h-10 rounded-full object-cover cursor-pointer" src='/resources/assets/logoMSU.png' alt="User dropdown">
                        <span class="text-xl font-semibold text-white">SPIS</span>
                    </div>
                    <div class="ml-3 relative flex items-center gap-3">
                        <div>
                            <p class="text-white text-sm font-semibold ">Supreme Student Council</p>
                            <p class="text-white text-xs text-right ">Admin</p>
                        </div>
                        <Menu as="div" class="relative inline-block text-left">
                                    <MenuButton class="mt-1.5 inline-flex items-center justify-center w-full rounded-lg  text-sm font-medium text-gray-700 ">
                                        <img id="avatarButton" type="button" data-dropdown-toggle="userDropdown" class="w-10 h-10 rounded-full object-cover ring-2 ring-blue-400 cursor-pointer" src='/resources/assets/user.png' alt="User dropdown">

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
                                            Option 1
                                            </a>
                                        </MenuItem>
                                        <MenuItem v-slot="{ active }">
                                            <a href="#" :class="[active ? 'bg-gray-100 text-gray-900' : 'text-gray-700', 'block px-4 py-2 text-sm']">
                                            Option 2
                                            </a>
                                        </MenuItem>
                                        <MenuItem v-slot="{ active }">
                                            <a href="#" :class="[active ? 'bg-gray-100 text-gray-900' : 'text-gray-700', 'block px-4 py-2 text-sm']">
                                            Option 3
                                            </a>
                                        </MenuItem>
                                        </div>
                                    </MenuItems>
                                    </transition>
                        </Menu>
                    </div>
                </div>
            </div>
        </nav>

        <div class="flex pt-16">
            <!-- Sidebar -->
            <aside class="fixed top-0 left-0 z-40 w-64 h-screen bg-white border-e border-gray-300 shadow pt-16">
                <div class="h-full px-5 py-4 overflow-y-auto flex flex-col justify-between space-y-2">
                    <div>
                        <div class="text-xs mb-2 mt-1 text-gray-800 font-medium">Dashboard</div>
                        <ul class="space-y-2" style="margin-bottom: 6px;">
                            <li>
                                <Link 
                                    href="/" 
                                    class="flex items-center py-2 px-3 text-md rounded-lg transition-all"
                                    :class="{'bg-blue-700 text-white': currentRoute === '/', 'text-gray-700 hover:bg-blue-100 hover:text-blue-600': currentRoute !== '/'}"
                                >
                                    <i class="fa-solid fa-square-poll-horizontal"></i>
                                    <span class="ms-3">Dashboard</span>
                                </Link>
                            </li>
                        </ul>
                        <div class="text-xs mb-2 mt-1 text-gray-800 font-medium">Setup</div>
                        <ul class="space-y-2">
                            <li>
                                <Link 
                                    href="/sport" 
                                    class="flex items-center py-2 px-3 text-md rounded-lg mb-2"
                                    :class="{'bg-blue-700 text-white': currentRoute === '/sport', 'text-gray-700 hover:bg-blue-100 hover:text-blue-600': currentRoute !== '/sport'}"
                                >
                                    <i class="fa-solid fa-basketball"></i>
                                    <span class="ms-3">Sports</span>
                                </Link>
                                <Link 
                                    href="/points" 
                                    class="flex items-center py-2 px-3 text-md rounded-lg mb-2"
                                    :class="{'bg-blue-700 text-white': currentRoute === '/points', 'text-gray-700 hover:bg-blue-100 hover:text-blue-600': currentRoute !== '/points'}"
                                >
                                    <i class="fa-solid fa-flag"></i>
                                    <span class="ms-3">Points</span>
                                </Link>
                                <Link 
                                    href="/venue" 
                                    class="flex items-center py-2 px-3 text-md rounded-lg mb-2"
                                    :class="{'bg-blue-700 text-white': currentRoute === '/venue', 'text-gray-700 hover:bg-blue-100 hover:text-blue-600': currentRoute !== '/venue'}"
                                >
                                    <i class="fa-solid fa-map-location-dot"></i>
                                    <span class="ms-3">Venues</span>
                                </Link>
                                <Link 
                                    href="/college" 
                                    class="flex items-center py-2 px-3 text-md rounded-lg mb-2"
                                    :class="{'bg-blue-700 text-white': currentRoute === '/college', 'text-gray-700 hover:bg-blue-100 hover:text-blue-600': currentRoute !== '/college'}"
                                >
                                    <i class="fa-solid fa-museum"></i>
                                    <span class="ms-3">Colleges</span>
                                </Link>
                                <Link 
                                    href="/student" 
                                    class="flex items-center py-2 px-3 text-md rounded-lg mb-2"
                                    :class="{'bg-blue-700 text-white': currentRoute === '/student', 'text-gray-700 hover:bg-blue-100 hover:text-blue-600': currentRoute !== '/student'}"
                                >
                                    <i class="fa-solid fa-graduation-cap"></i>
                                    <span class="ms-3">Students</span>
                                </Link>
                            </li>
                        </ul>
                        <div class="text-xs mb-2 mt-1 text-gray-800 font-medium">Main</div>
                        <ul class="space-y-2" style="margin-bottom: 6px;">
                            <li>
                                <Link 
                                    href="/details" 
                                    class="flex items-center py-2 px-3 text-md rounded-lg transition-all"
                                    :class="{
                                    'bg-blue-700 text-white': currentRoute.startsWith('/details'), 
                                    'text-gray-700 hover:bg-blue-100 hover:text-blue-600': !currentRoute.startsWith('/details')
                                    }"
                                >
                                    <i class="fa-solid fa-hand-fist"></i>                                
                                    <span class="ms-3">Palakasan</span>
                                </Link>
                            </li>
                            <li>
                                <Link 
                                    href="/account" 
                                    class="flex items-center py-2 px-3 text-md rounded-lg transition-all"
                                    :class="{'bg-blue-700 text-white': currentRoute === '/account', 'text-gray-700 hover:bg-blue-100 hover:text-blue-600': currentRoute !== '/account'}"
                                >
                                    <i class="fa-solid fa-user-group"></i>
                                    <span class="ms-3">Accounts</span>
                                </Link>
                            </li>
                        </ul>
                    </div>
                    <div>
                        <ul class="space-y-2" style="margin-bottom: 6px;">
                            <li>
                                <Link 
                                    href="/logout" 
                                    class="flex items-center py-2 px-3 text-md rounded-lg transition-all"
                                    :class="{'bg-blue-700 text-white': currentRoute === '/logout', 'text-gray-700 hover:bg-blue-100 hover:text-blue-600': currentRoute !== '/logout'}"
                                >
                                <i class="fa-solid fa-right-from-bracket"></i>                                    
                                <span class="ms-3">Log out</span>
                                </Link>
                            </li>
                        </ul>
                    </div>
                </div>

            </aside>

            <!-- Main content -->
            <div class="flex-1 p-5 main-content">
                <slot />
            </div>
        </div>
    </div>
</template>

<script setup>
    import { Link, usePage } from '@inertiajs/vue3';
    import { Menu, MenuButton, MenuItem, MenuItems } from '@headlessui/vue'
    import { ChevronDownIcon } from '@heroicons/vue/20/solid'

    const { url: currentRoute } = usePage();
</script>

<style scoped>
.main-content {
    margin-left: 256px;
}

</style>
