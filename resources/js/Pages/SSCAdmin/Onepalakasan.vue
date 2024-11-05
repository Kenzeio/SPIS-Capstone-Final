<template>
    <Head title="College"/>
    <AppLayout>
        <template v-slot:default>

            <!-- Tabs Navigation -->
            <nav class="flex relative justify-between  items-center">
                <div class="bg-gray-100 flex gap-2 rounded-lg p-1.5">
                    <div class="bg-gray-100 flex gap-2 rounded-lg">
                        <button 
                            v-for="tab in ['details', 'lineups', 'leagues']"
                            :key="tab"
                            @click="setActiveTab(tab)"
                            :class="[
                            'px-5 py-1 text-sm',
                            activeTab === tab
                                ? 'text-gray-800 ring-1 ring-gray-300 bg-white rounded-md'
                                : 'text-gray-500 hover:text-gray-700 border-transparent'
                            ]"
                        >
                            {{ tab.charAt(0).toUpperCase() + tab.slice(1) }}
                        </button>
                        </div>
                </div>
                <div class="">
                    <button type="button" class=" text-white bg-blue-700 hover:bg-blue-700/90 text-sm focus:outline-none focus:ring-4 focus:ring-gray-300 rounded-lg px-4 py-2 dark:bg-gray-800 dark:hover:bg-gray-700 dark:focus:ring-gray-700 dark:border-gray-700">
                        <i class="fa-solid fa-box-archive mr-1.5"></i>
                        History
                    </button>
                </div>
            </nav>

            <!-- Tabs Content -->
            <div class="mt-4">
                <!-- Palakasans (Details) Tab Content -->
                <div v-if="activeTab === 'details'">
                    <div v-if="latestPalakasan">
                        <!--head-->
                        <div class="flex justify-between items-center">
                            <div>
                                <div class="flex gap-2 items-center">
                                    <h1  class="text-2xl font-bold text-gray-800">Palakasan {{ latestPalakasan.year }}</h1>
                                    <p :class="latestPalakasan.status ? 'text-sm py-1 px-2 bg-green-50 rounded-lg text-green-700' : 'text-sm py-1.5 px-2 bg-red-100 rounded-lg text-red-700'">
                                        {{ latestPalakasan.status ? 'Ongoing' : 'Completed' }}
                                    </p>
                                </div>
                                <p v-if="latestPalakasan" class="text-xs text-gray-500">
                                    {{ latestPalakasan.start_date }} - {{ latestPalakasan.end_date }}
                                </p>
                            </div>
                            <!--utility, history and creating-->
                            <div class="flex gap-2.5 items-center">

                                <button @click="openStatusModal" class="bg-white text-gray-800 py-1.5 px-2.5 text-sm rounded-md ring-1 ring-gray-300 shadow hover:bg-gray-100 transition-colors">
                                    <i class="fa-solid fa-rotate mr-1.5"></i> Status
                                </button>

                                <button 
                                    @click="openPalakasanModal" 
                                    :disabled="latestPalakasan.status"
                                    :class="[
                                        'py-1.5 px-2.5 text-sm rounded-md shadow transition-colors',
                                        latestPalakasan.status 
                                            ? 'bg-gray-300 text-gray-500 cursor-not-allowed' 
                                            : 'bg-blue-700 text-white hover:bg-blue-700/90'
                                    ]"
                                >
                                    <i class="fa-solid fa-file mr-1.5"></i> New
                                </button>
                            </div>
                        </div>

                        <!--content-->
                        <div class="mt-3 grid grid-cols-5 gap-4">
                            <div class="col-span-3">
                                <div>
                                    <p class="text-lg font-semibold text-gray-800">{{ latestPalakasan.theme }}</p>
                                    <p class="text-sm text-gray-700">{{ latestPalakasan.tagline }}</p>
                                    <p class="text-sm text-gray-700 mt-2">{{ latestPalakasan.description }}</p>
                                </div>

                                <div class="grid grid-cols-4 mt-4 gap-4">
                                    <div class="p-4 bg-blue-50 hover:bg-blue-100 cursor-pointer rounded-lg" @click="activeTab = 'teams'">
                                        <div>
                                            <h1 class="text-md font-semibold text-blue-700">Teams</h1>
                                        </div>
                                        <p class="mt-4 text-center text-3xl font-semibold text-blue-700 mb-3">{{ assignedTeams.length }}</p>
                                        <p class="text-xs text-blue-700 text-center mb-3">total number of teams</p>
                                    </div>

                                    <div class="p-4 bg-blue-50 hover:bg-blue-100 cursor-pointer rounded-lg" @click="activeTab = 'sports'">
                                        <div>
                                            <h1 class="text-md font-semibold text-blue-700">Sports</h1>
                                        </div>
                                        <p class="mt-4 text-center text-3xl font-semibold text-blue-700 mb-3">{{ assignedSports.length }}</p>
                                        <p class="text-xs text-blue-700 text-center mb-3">total number of sports</p>
                                    </div>
                                </div>
                            </div>
                            <div class="right-side col-span-2 bg-blue-50 p-4 rounded-lg">
                                <h1 class=" font-medium text-blue-700">Ranking</h1>
                                <div class="relative overflow-x-auto mt-3">
                                    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                        <thead class="text-xs text-blue-700 uppercase bg-blue-100">
                                            <tr>
                                                <th scope="col" class="px-6 py-3">
                                                    Rank
                                                </th>
                                                <th scope="col" class="px-6 py-3">
                                                    Team
                                                </th>
                                                <th scope="col" class="px-6 py-3">
                                                    Points
                                                </th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr class="border-b border-blue-200">
                                                <th scope="row" class="px-6 py-4 text-blue-700 whitespace-nowrap dark:text-white">
                                                    1st
                                                </th>
                                                <td class="px-6 py-4 font-medium text-blue-700">
                                                    Tartaros
                                                </td>
                                                <td class="px-6 py-4">
                                                    100 Points
                                                </td>
                                            </tr>
                                            <tr class="border-b border-blue-200">
                                                <th scope="row" class="px-6 py-4 text-blue-700 whitespace-nowrap dark:text-white">
                                                    1st
                                                </th>
                                                <td class="px-6 py-4 font-medium text-blue-700">
                                                    Tartaros
                                                </td>
                                                <td class="px-6 py-4">
                                                    100 Points
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div v-else class="text-center py-10 bg-blue-50 border-2 border-blue-500 rounded-lg border-dashed border-spacing-4">
                        <h1 class="text-2xl font-semibold text-blue-700">No Palakasan Created Yet</h1>
                        <p class="text-blue-600 mb-4">Please create a new Palakasan event.</p>
                        <button 
                            @click="openPalakasanModal" 
                            class="text-white py-1.5 px-2.5 text-sm rounded-md bg-blue-700 shadow hover:bg-blue-700/90 transition-colors">
                            <i class="fa-solid fa-file mr-1.5"></i> Create Palakasan
                        </button>
                    </div>
                </div>

                <!-- Teams Tab Content -->
                <div v-if="activeTab === 'lineups'">
                    <div class="flex mb-4 items-center justify-between gap-2">
                        <div class="flex items-center gap-2">
                            <!-- Search Input -->
                            <div class="flex items-center">
                                <input
                                    v-model="searchQueryTeams"
                                    type="text"
                                    placeholder="Search teams..."
                                    class=" w-64 border border-gray-300 text-sm shadow rounded-lg p-2"
                                />
                            </div>
                            <button @click="openTeamsModal" class="bg-blue-700 text-sm text-white px-4 py-2 shadow rounded-lg">
                                <i class="fa-solid fa-square-plus mr-1"></i>
                                Add Team
                            </button>
                        </div>
                        <!-- Sort Button -->
                        <div class="flex items-center justify-end border border-gray-300 px-2.5 shadow rounded-lg">
                            <label for="sort" class="text-sm font-semibold">Sort By:</label>
                            <select v-model="sortOrderTeams" id="sort" class=" text-sm rounded-lg  cursor-pointer p-2 outline-none">
                                <option value="default">Default</option>
                                <option value="az">A-Z</option>
                                <option value="za">Z-A</option>
                            </select>
                        </div>
                    </div>

                    <!-- Check if assignedTeams array is empty after search and sort -->
                    <div v-if="filteredAndSortedTeams.length === 0" class="flex flex-col items-center justify-center border-2 border-dashed border-blue-400 text-center p-6 bg-blue-50 rounded-lg">
                        <i class="fa-solid fa-people-group text-blue-700 text-6xl mb-4"></i>
                        <h3 class="text-xl font-semibold text-blue-700 mb-2">No team found</h3>
                        <p class="text-gray-500 text-sm mb-4">
                            We couldn't find any tea that match your search criteria. <br>
                            If the team you are looking for is not here you can add it <br>
                            by clicking the <span class="text-blue-500 font-semibold">Button</span> below.

                        </p>
                        <button @click="openTeamsModal" class="bg-blue-700 text-sm text-white px-4 py-2 shadow rounded-lg">
                            <i class="fa-solid fa-square-plus mr-1"></i>
                            Add Team
                        </button>
                    </div>

                    <!-- Display assigned teams as cards -->
                    <div v-else class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                        <div v-for="(team, index) in filteredAndSortedTeams" :key="index" class="bg-white ring-1 ring-gray-300 shadow rounded-lg hover:shadow-lg transition p-4">
                            <div class="flex items-center">
                                <div class="">
                                    <h3 class="text-xl font-semibold">{{ team.assigned_team_name }}</h3>
                                    <p class="text-sm text-gray-600">{{ team.college.name }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sports Tab Content -->
                <div v-if="activeTab === 'leagues'" class="mt-4">
                    <div class="flex mb-4 items-center justify-between gap-2">
                        <div class="flex items-center gap-2">
                            <!-- Search Input -->
                            <div class="flex items-center">
                                <input
                                    v-model="searchQuerySports"
                                    type="text"
                                    placeholder="Search sport..."
                                    class=" w-64 border border-gray-300 text-sm shadow rounded-lg p-2"
                                />
                            </div>
                            <button @click="openSportsModal" class="bg-blue-700 text-sm text-white px-4 py-2 rounded-lg">
                                <i class="fa-solid fa-square-plus mr-1"></i>
                                Select Sport
                            </button>
                        </div>
                        <!-- Sort Button -->
                        <div class="flex items-center justify-end border border-gray-300 px-2.5 shadow rounded-lg">
                            <label for="sort" class="text-sm font-semibold">Sort By:</label>
                            <select v-model="sortOrderSports" id="sort" class=" text-sm rounded-lg  cursor-pointer p-2 outline-none">
                                <option value="default">Default</option>
                                <option value="az">A-Z</option>
                                <option value="za">Z-A</option>
                            </select>
                        </div>
                    </div>

                    <!-- Check if filteredAndSortedSports is empty -->
                    <div v-if="filteredAndSortedSports.length === 0" class="flex flex-col items-center justify-center border-2 border-dashed border-blue-400 text-center p-6 bg-blue-50 rounded-lg">
                        <i class="fa-solid fa-basketball text-blue-700 text-6xl mb-4"></i>
                        <h3 class="text-xl font-semibold text-blue-700 mb-2">No sports found</h3>
                        <p class="text-gray-500 text-sm mb-4">
                            We couldn't find any sports that match your search criteria. <br>
                            If the sport you are looking for is not here you can add it <br>
                            by clicking the <span class="text-blue-500 font-semibold">Button</span> below.

                        </p>
                        <button @click="openSportsModal" class="bg-blue-700 text-sm text-white px-4 py-2 rounded-lg">
                            <i class="fa-solid fa-square-plus mr-1"></i>
                            Add Sport
                        </button>
                    </div>


                    <div v-else class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                        <div
                            v-for="(sport, index) in filteredAndSortedSports"
                            :key="index"
                            @click="viewSport(sport.id)"
                            class="bg-white ring-1 ring-gray-300 shadow cursor-pointer rounded-lg p-4 transition-all duration-300 ease-in-out hover:shadow-lg hover:ring-blue-300 group relative overflow-hidden"
                            >
                            <div class="flex items-center justify-between">
                                <div class="w-full">
                                    <div class="flex justify-between items-center">
                                        <h3 class="text-xl font-semibold">{{ sport.sport.name }} {{ sport.categories }}</h3>
                                        <i class="fas fa-arrow-right text-blue-500 transform translate-x-8 opacity-0 transition-all duration-300 ease-in-out group-hover:translate-x-0 group-hover:opacity-100"></i>
                                    </div>
                                    <div class="flex gap-2 mt-1.5">
                                        <p class="text-xs px-3 py-1 bg-blue-100 rounded-md text-blue-700">{{ sport.setup }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!--Modals for palakasan-->

            <!--add or create modals-->
            <!-- Palakasan Modal -->
            <div v-if="isPalakasanModalOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                    <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-lg">
                        <h2 class="text-lg font-semibold text-gray-800 mb-4">Create New Palakasan</h2>

                        <form @submit.prevent="submitPalakasan">
                            <!-- Theme -->
                            <div class="mb-4">
                                <label for="theme" class="block text-sm font-medium text-gray-700">Theme</label>
                                <input placeholder="Enter theme of the palakasan..." v-model="form.theme" id="theme" type="text" class="mt-2 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required />
                                <span v-if="form.errors.theme" class="text-red-600 text-sm">{{ form.errors.theme }}</span>
                            </div>

                            <!-- Tagline -->
                            <div class="mb-4">
                                <label for="tagline" class="block text-sm font-medium text-gray-700">Tagline</label>
                                <input placeholder="Enter tagline for the palakasan..." v-model="form.tagline" id="tagline" type="text" class="mt-2 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required />
                                <span v-if="form.errors.tagline" class="text-red-600 text-sm">{{ form.errors.tagline }}</span>
                            </div>

                            <!-- Description -->
                            <div class="mb-4">
                                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                                <textarea placeholder="Explain the palakasan briefly and the theme..." v-model="form.description" rows="6" id="description" class="mt-2 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required></textarea>
                                <span v-if="form.errors.description" class="text-red-600 text-sm">{{ form.errors.description }}</span>
                            </div>

                            <div class="grid grid-cols-2 gap-3">
                                <!-- Start Date -->
                                <div class="mb-4">
                                    <label for="start_date" class="block text-sm font-medium text-gray-700">Start Date</label>
                                    <input v-model="form.start_date" id="start_date" type="date" class="mt-2 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required />
                                    <span v-if="form.errors.start_date" class="text-red-600 text-sm">{{ form.errors.start_date }}</span>
                                </div>

                                <!-- End Date -->
                                <div class="mb-4">
                                    <label for="end_date" class="block text-sm font-medium text-gray-700">End Date</label>
                                    <input v-model="form.end_date" id="end_date" type="date" class="mt-2 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required />
                                    <span v-if="form.errors.end_date" class="text-red-600 text-sm">{{ form.errors.end_date }}</span>
                                </div>
                            </div>

                            <!-- Buttons -->
                            <div class="flex justify-end space-x-2">
                                <button @click="closePalakasanModal" type="button" class="bg-gray-100 text-gray-700 py-1.5 px-3 rounded-md hover:bg-gray-200">Cancel</button>
                                <button
                                    type="submit"
                                    :disabled="form.processing"
                                    class="bg-blue-700 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm transition relative"
                                >
                                    <span v-if="!form.processing">
                                        Create
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
            <!-- Modal for adding sport -->
            <div v-if="isSportsModalOpen" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex justify-center items-center">
                <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-lg">
                <h3 class="text-lg font-semibold mb-4">Add sport</h3>

                <form @submit.prevent="submitSport">
                    <!-- Hidden Palakasan ID input -->
                    <input type="text" v-model="form1.palakasan_sport_id" class="hidden" />
                    
                    <!-- Select Sport -->
                    <div class="mb-4">
                    <label for="sport" class="block text-sm font-medium">Select Sport</label>
                    <select 
                        v-model="form1.sport_id" 
                        id="sport" 
                        class="w-full border px-3 py-2 rounded-lg"
                        @change="updateAvailableCategories"
                    >
                        <option value="" disabled>Select a sport</option>
                        <option 
                        v-for="sport in availableSports" 
                        :key="sport.id" 
                        :value="sport.id"
                        :disabled="isDisabled(sport.id)"
                        >
                        {{ sport.name }}
                        </option>
                    </select>
                    <span v-if="form1.errors.sport_id" class="text-red-500">{{ form1.errors.sport_id }}</span>
                    </div>

                    <!-- Select Setup -->
                    <div class="mb-4">
                    <label for="setup" class="block text-sm font-medium">Select Setup</label>
                    <select 
                        v-model="form1.setup" 
                        id="setup" 
                        class="w-full border px-3 py-2 rounded-lg"
                        @change="updateCategoryForFreeForAll"
                    >
                        <option value="" disabled>Select a setup</option>
                        <option value="Double Elimination">Double Elimination</option>
                        <option value="Single Elimination">Single Elimination</option>
                        <option value="Free for All">Free for All</option>
                    </select>
                    <span v-if="form1.errors.setup" class="text-red-500">{{ form1.errors.setup }}</span>
                    </div>

                    <!-- Select Category -->
                    <div class="mb-4">
                    <label for="category" class="block text-sm font-medium">Select Category</label>
                    <select 
                        v-model="form1.categories" 
                        id="category" 
                        class="w-full border px-3 py-2 rounded-lg"
                    >
                        <option value="" disabled>Select a category</option>
                        <option 
                        v-for="category in availableCategories" 
                        :key="category" 
                        :value="category"
                        >
                        {{ category }}
                        </option>
                    </select>
                    <span v-if="form1.errors.categories" class="text-red-500">{{ form1.errors.categories }}</span>
                    </div>

                    <!-- Modal Buttons -->
                    <div class="flex justify-end">
                    <button type="button" @click="closeSportsModal" class="mr-2 px-4 py-2 text-sm bg-gray-100 hover:bg-gray-200 rounded-lg">Cancel</button>
                    <button
                        type="submit"
                        :disabled="form1.processing"
                        class="bg-blue-700 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm transition relative"
                    >
                        <span v-if="!form1.processing">
                        Confirm
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
            <!-- Modal for adding new team -->
            <div v-if="isTeamsModalOpen" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex justify-center items-center">
            <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-lg">
                <h3 class="text-lg font-semibold mb-4">Add New Team</h3>

                <form @submit.prevent="submitTeam">
                <!-- Select College -->
                <div class="mb-4">
                    <label for="college" class="block text-sm font-medium">Select College</label>
                    <select v-model="form2.college_id" id="college" class="w-full border px-3 py-2 rounded-lg">
                        <option value="" disabled>Select a college</option>
                        <option
                            v-for="college in availableColleges"
                            :key="college.id"
                            :value="college.id"
                        >
                            {{ college.shortName }}
                        </option>
                    </select>
                    <span v-if="form2.errors.college_id" class="text-red-500">{{ form2.errors.college_id }}</span>
                </div>

                <!-- Input for Team Name -->
                <div class="mb-4">
                    <label for="teamName" class="block text-sm font-medium">Team Name</label>
                    <input
                        v-model="form2.assigned_team_name"
                        id="teamName"
                        type="text"
                        placeholder="Enter the team name..."
                        class="w-full border px-3 py-2 rounded-lg"
                    />
                    <span v-if="form2.errors.assigned_team_name" class="text-red-500">{{ form2.errors.assigned_team_name }}</span>
                </div>

                <!-- Hidden Palakasan ID input -->
                <input type="hidden" v-model="form2.palakasan_id" />

                <!-- Modal Buttons -->
                <div class="flex justify-end">
                    <button type="button" @click="closeTeamsModal" class="mr-2 px-4 text-sm py-2 bg-gray-100 hover:bg-gray-200 rounded-lg">Cancel</button>
                    <button
                        type="submit"
                        :disabled="form2.processing"
                        class="bg-blue-700 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm transition relative"
                    >
                        <span v-if="!form2.processing">
                            Confirm
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
            <!-- Status Update Modal -->
            <div v-if="isStatusModalOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Update Palakasan Status</h2>

                    <form @submit.prevent="updateStatus">
                        <div class="mb-4">
                            <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                            <select v-model="statusForm.status" id="status" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                                <option :value="true">Activate</option>
                                <option :value="false">Deactivate</option>
                            </select>
                        </div>

                        <div class="flex justify-end space-x-2">
                            <button @click="closeStatusModal" type="button" class="bg-gray-100 text-gray-700 py-1.5 px-3 rounded-md hover:bg-gray-200">Cancel</button>
                            <button
                                type="submit"
                                :disabled="statusForm.processing"
                                class="bg-blue-700 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm transition relative"
                            >
                                <span v-if="!statusForm.processing">
                                    Update
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
        </template>
    </AppLayout>
</template>

<script setup>
    import { Head, Link, useForm, router } from '@inertiajs/vue3';
    import { ref, computed, onMounted, watch } from 'vue';
    import AppLayout from '@/Layout/DashboardLayout.vue';
    import { route } from 'ziggy-js';

    const props = defineProps({
        colleges: Array,
        sports: Array,
        palakasans: Array,      // Palakasans data array
        assignedTeams: Array,   // Teams data array
        assignedSports: Array,  
        latestPalakasan: Object, // Sports data array
        initialActiveTab: {
            type: String,
            default: 'details'
        }
    });

    const isPalakasanModalOpen = ref(false);
    const isSportsModalOpen = ref(false);
    const isTeamsModalOpen = ref(false);
    const isStatusModalOpen = ref(false);

    //this is for viewing the sport
    const viewSport = (sportId) => {
        router.get(route('sportview.index', { sport: sportId }));
    };

    //tabs funtcions
    const activeTab = ref(props.initialActiveTab);

    const setActiveTab = (tab) => {
    activeTab.value = tab;
    updateQueryString(tab);
    };

    const updateQueryString = (tab) => {
    const url = new URL(window.location);
    url.searchParams.set('tab', tab);
    window.history.pushState({}, '', url);
    };

    onMounted(() => {
    const urlParams = new URLSearchParams(window.location.search);
    const tabFromQuery = urlParams.get('tab');
    if (tabFromQuery && ['details', 'teams', 'sports'].includes(tabFromQuery)) {
        activeTab.value = tabFromQuery;
    }
    });

    watch(() => route().params.tab, (newTab) => {
    if (newTab && ['details', 'teams', 'sports'].includes(newTab)) {
        activeTab.value = newTab;
    }
    });


    // Initialize palakasan useForm with default values
    const form = useForm({
        theme: '',
        tagline: '',
        description: '',
        start_date: '',
        end_date: '',
        status: false, // Deactivated by default
    });

    // sport form
    const form1 = useForm({
        palakasan_sport_id: props.latestPalakasan?.id || '',
        sport_id: '',
        categories: '',
        setup: '',
    });

    // team form
    const form2 = useForm({
        assigned_team_name: '',
        college_id: '',
        palakasan_id: props.latestPalakasan?.id || '', // Use optional chaining and provide a default
    });
    //status
    const statusForm = useForm({
        status: props.latestPalakasan?.status || false,
    });

    const availableSports = ref(props.sports);
    const availableColleges = ref(props.colleges);

    const openPalakasanModal = () => {
        isPalakasanModalOpen.value = true;
    };

    // Function to close the Palakasan modal
    const closePalakasanModal = () => {
        isPalakasanModalOpen.value = false;
        form.reset(); // Reset form when modal is closed
    };

    const submitPalakasan = () => {
        form.post(route('palakasan.store'), {
            onSuccess: () => closePalakasanModal(),
        });
    };

    //---------------------------------------------------------

    // Open Sports modal
    const openSportsModal = () => {
        isSportsModalOpen.value = true;
    };
  
    // Close Sports modal and reset form
    const closeSportsModal = () => {
        isSportsModalOpen.value = false;
        form1.reset();
    };
  
    // Submit form for adding a new sports
    const submitSport = () => {
        form1.post(route('palakasanSport.store'), {
        onSuccess: () => {
            closeSportsModal();
            },
            onError: () => {
                console.error('Form submission error');
            },
        });
    };

    
    const availableCategories = ref(['Men', 'Women', 'Mixed']);

    const isDisabled = (sportId) => {
    return props.assignedSports.some(assignedSport => 
        assignedSport.sport_id === sportId && 
        assignedSport.categories === form1.categories
    );
    };

    const updateAvailableCategories = () => {
    const selectedSport = props.assignedSports.filter(sport => sport.sport_id === form1.sport_id);
    availableCategories.value = ['Men', 'Women', 'Mixed'].filter(category => 
        !selectedSport.some(sport => sport.categories === category)
    );
    form1.categories = ''; // Reset the category when sport changes
    };
    //---------------------------------------------
    // Open Teams modal
    const openTeamsModal = () => {
        isTeamsModalOpen.value = true;
    };

    // Close Teams modal and reset form
    const closeTeamsModal = () => {
        isTeamsModalOpen.value = false;
        form2.reset();
    };

    // Submit form for adding a new team
    const submitTeam = () => {
        form2.post(route('team.store'), {
        onSuccess: () => {
            closeTeamsModal();
            },
            onError: () => {
                console.error('Form submission error');
            },
        });
    };
    //status
    const openStatusModal = () => {
        isStatusModalOpen.value = true;
    };

    const closeStatusModal = () => {
        isStatusModalOpen.value = false;
        statusForm.reset();
    };

    const updateStatus = () => {
        statusForm.put(route('palakasan.updateStatus', { id: props.latestPalakasan.id }), {
            preserveState: true,
            preserveScroll: true,
            onSuccess: () => {
                closeStatusModal();
                // Reload the entire page after success
                window.location.reload();
            },
        });
    };


    //Search and sort functionalities
    // State variables for search and sort (for both teams and sports)
    const searchQueryTeams = ref('');
    const searchQuerySports = ref('');
    const sortOrderTeams = ref('default');
    const sortOrderSports = ref('default');

    // Computed property for filtering and sorting assigned teams from props
    const filteredAndSortedTeams = computed(() => {
        let filteredTeams = props.assignedTeams;

        // Filter teams based on search query
        if (searchQueryTeams.value) {
            filteredTeams = filteredTeams.filter(team =>
                team.assigned_team_name.toLowerCase().includes(searchQueryTeams.value.toLowerCase()) ||
                team.college.name.toLowerCase().includes(searchQueryTeams.value.toLowerCase())
            );
        }

        // Sort teams based on sort order
        if (sortOrderTeams.value === 'az') {
            return filteredTeams.sort((a, b) =>
                a.assigned_team_name.localeCompare(b.assigned_team_name)
            );
        } else if (sortOrderTeams.value === 'za') {
            return filteredTeams.sort((a, b) =>
                b.assigned_team_name.localeCompare(a.assigned_team_name)
            );
        }

        // Default sort order (as per the original assignedTeams array)
        return filteredTeams;
    });

    // Computed property for filtering and sorting assigned sports from props
    const filteredAndSortedSports = computed(() => {
        let filteredSports = props.assignedSports;

        // Filter sports based on search query
        if (searchQuerySports.value) {
            filteredSports = filteredSports.filter(sport =>
                sport.sport.name.toLowerCase().includes(searchQuerySports.value.toLowerCase()) ||
                sport.description.toLowerCase().includes(searchQuerySports.value.toLowerCase())
            );
        }

        // Sort sports based on sort order
        if (sortOrderSports.value === 'az') {
            return filteredSports.sort((a, b) =>
                a.sport.name.localeCompare(b.sport.name)
            );
        } else if (sortOrderSports.value === 'za') {
            return filteredSports.sort((a, b) =>
                b.sport.name.localeCompare(a.sport.name)
            );
        }

        // Default sort order (as per the original assignedSports array)
        return filteredSports;
    });

    

</script>

<style scoped>

</style>
