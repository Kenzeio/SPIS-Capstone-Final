<template>
    <DashboardLayout>
        <div class="mt-4 mx-6">
            <h1 class="p-5 text-4xl font-bold">Product List</h1>
            <button @click="openModal(false)" class="p-3 rounded-full bg-gray-200 hover:bg-gray-300 transition">Add Product</button>

            <table class="min-w-full mt-4 bg-white border border-gray-300">
                <thead>
                    <tr>
                        <th class="py-2 px-4 border-b text-left">Name</th>
                        <th class="py-2 px-4 border-b text-left">Price</th>
                        <th class="py-2 px-4 border-b text-left">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="product in products" :key="product.id" class="hover:bg-gray-100">
                        <td class="py-2 px-4 border-b">{{ product.name }}</td>
                        <td class="py-2 px-4 border-b">{{ product.price }}</td>
                        <td class="py-2 px-4 border-b">
                            <button @click="openModal(true, product)" class="bg-yellow-300 hover:bg-yellow-400 px-2 py-1 rounded transition">Edit</button>
                            <button @click="confirmDelete(product.id)" class="ml-2 bg-red-300 hover:bg-red-400 px-2 py-1 rounded transition">Delete</button>
                        </td>
                    </tr>
                </tbody>
            </table>

            <!-- Modal for Add/Edit -->
            <div v-if="isModalOpen" class="fixed inset-0 flex items-center justify-center z-40">
                <div class="fixed inset-0 bg-black bg-opacity-50" @click="closeModal"></div>
                <div class="bg-white p-6 rounded-lg shadow-lg z-50 max-w-md w-full">
                    <h2 class="text-xl font-bold mb-4">{{ isEditing ? 'Edit Product' : 'Add Product' }}</h2>
                    <form @submit.prevent="submitProduct">
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700">Product Name:</label>
                            <input type="text" id="name" v-model="form.name" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-blue-300" required />
                        </div>
                        <div class="mb-4">
                            <label for="price" class="block text-sm font-medium text-gray-700">Price:</label>
                            <input type="number" id="price" v-model="form.price" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-blue-300" required />
                        </div>
                        <div class="flex justify-end">
                            <button type="button" @click="closeModal" class="mr-2 bg-gray-300 hover:bg-gray-400 px-4 py-2 rounded-md transition">Cancel</button>
                            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md transition">{{ isEditing ? 'Update Product' : 'Add Product' }}</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Modal for Delete Confirmation -->
            <div v-if="isDeleteModalOpen" class="fixed inset-0 flex items-center justify-center z-40">
                <div class="fixed inset-0 bg-black bg-opacity-50" @click="closeDeleteModal"></div>
                <div class="bg-white p-6 rounded-lg shadow-lg z-50 max-w-md w-full">
                    <h2 class="text-xl font-bold mb-4">Confirm Delete</h2>
                    <p>Are you sure you want to delete this product?</p>
                    <div class="flex justify-end mt-4">
                        <button type="button" @click="closeDeleteModal" class="mr-2 bg-gray-300 hover:bg-gray-400 px-4 py-2 rounded-md transition">Cancel</button>
                        <button @click="deleteProduct" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-md transition">Delete</button>
                    </div>
                </div>
            </div>
        </div>
    </DashboardLayout>
</template>

<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { router } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import DashboardLayout from '@/Layout/DashboardLayout.vue';

const props = defineProps({
    products: Array,
});

const isModalOpen = ref(false);
const isDeleteModalOpen = ref(false);
const isEditing = ref(false);
const form = useForm({
    id: null,
    name: '',
    price: '',
});

const productIdToDelete = ref(null);

const openModal = (editMode = false, product = null) => {
    isModalOpen.value = true;
    isEditing.value = editMode;

    if (editMode && product) {
        form.id = product.id;
        form.name = product.name;
        form.price = product.price;
    } else {
        form.reset();
    }
};

const closeModal = () => {
    isModalOpen.value = false;
    form.reset();
};

const submitProduct = () => {
    if (isEditing.value) {
        form.put(route('product.update', form.id), {
            preserveState: false,
            preserveScroll: true,
            onSuccess: () => {
                closeModal();
            },
        });
    } else {
        form.post(route('product.store'), {
            preserveState: false,
            preserveScroll: true,
            onSuccess: () => {
                closeModal();
            },
        });
    }
};

const confirmDelete = (productId) => {
    productIdToDelete.value = productId;
    isDeleteModalOpen.value = true;
};

const closeDeleteModal = () => {
    isDeleteModalOpen.value = false;
    productIdToDelete.value = null;
};

const deleteProduct = () => {
    router.delete(route('product.destroy', productIdToDelete.value), {
        preserveState: false,
        preserveScroll: true,
    });
    closeDeleteModal();
};

</script>

<style scoped>
/* No extra custom CSS, only Tailwind classes are used */
</style>
