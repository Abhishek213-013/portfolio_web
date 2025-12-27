<template>
    <AdminLayout>
        <div class="max-w-7xl mx-auto">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-900">Portfolio Management</h2>
                <button 
                    @click="showForm = !showForm" 
                    class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors"
                >
                    {{ showForm ? 'Cancel' : 'Add New Portfolio Item' }}
                </button>
            </div>

            <!-- Add/Edit Portfolio Form -->
            <div v-if="showForm" class="bg-white rounded-lg shadow p-6 mb-8">
                <form @submit.prevent="savePortfolioItem" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Title</label>
                            <input 
                                v-model="form.title" 
                                type="text" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                required
                            />
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                            <select 
                                v-model="form.category" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            >
                                <option value="app">App</option>
                                <option value="product">Product</option>
                                <option value="branding">Branding</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Image</label>
                        <div class="mt-1 flex items-center">
                            <input 
                                type="file" 
                                @change="handleImageUpload"
                                accept="image/*"
                                class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100"
                            />
                        </div>
                        <div v-if="form.image" class="mt-2">
                            <img :src="form.image instanceof File ? URL.createObjectURL(form.image) : form.image" 
                                 class="h-48 w-full object-cover rounded">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                        <textarea 
                            v-model="form.description" 
                            rows="3"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            required
                        ></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Details URL (Optional)</label>
                        <input 
                            v-model="form.details_url" 
                            type="url" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
                        />
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Order</label>
                            <input 
                                v-model="form.order" 
                                type="number" 
                                min="0"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            />
                        </div>

                        <div class="flex items-center">
                            <input 
                                v-model="form.is_active" 
                                type="checkbox" 
                                id="is_active"
                                class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                            />
                            <label for="is_active" class="ml-2 block text-sm text-gray-900">Active</label>
                        </div>
                    </div>

                    <div class="flex justify-end space-x-4">
                        <button 
                            type="button" 
                            @click="cancelEdit"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-md"
                        >
                            Cancel
                        </button>
                        <button 
                            type="submit" 
                            :disabled="loading"
                            class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-md disabled:opacity-50"
                        >
                            {{ loading ? 'Saving...' : (editingId ? 'Update Portfolio Item' : 'Add Portfolio Item') }}
                        </button>
                    </div>
                </form>
            </div>

            <!-- Portfolio Items Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div 
                    v-for="item in portfolioItems" 
                    :key="item.id"
                    class="bg-white rounded-lg shadow overflow-hidden hover:shadow-lg transition-shadow"
                >
                    <div class="relative">
                        <img 
                            :src="item.image" 
                            :alt="item.title" 
                            class="w-full h-48 object-cover"
                        />
                        <div class="absolute top-2 right-2 flex space-x-1">
                            <button 
                                @click="editPortfolioItem(item)"
                                class="p-1 bg-white rounded-full shadow"
                            >
                                <PencilIcon class="h-4 w-4 text-blue-600" />
                            </button>
                            <button 
                                @click="deletePortfolioItem(item.id)"
                                class="p-1 bg-white rounded-full shadow"
                            >
                                <TrashIcon class="h-4 w-4 text-red-600" />
                            </button>
                        </div>
                        <div class="absolute bottom-2 left-2">
                            <span class="px-2 py-1 text-xs font-medium bg-indigo-600 text-white rounded">
                                {{ item.category }}
                            </span>
                        </div>
                    </div>
                    <div class="p-4">
                        <h4 class="text-lg font-medium text-gray-900 mb-2">{{ item.title }}</h4>
                        <p class="text-gray-600 text-sm mb-4">{{ item.description }}</p>
                        <div class="flex justify-between items-center">
                            <span :class="[
                                'px-2 py-1 text-xs font-medium rounded',
                                item.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'
                            ]">
                                {{ item.is_active ? 'Active' : 'Inactive' }}
                            </span>
                            <span class="text-xs text-gray-500">Order: {{ item.order }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div v-if="portfolioItems.length === 0" class="text-center bg-white rounded-lg shadow p-12">
                <PhotoIcon class="h-16 w-16 text-gray-400 mx-auto mb-4" />
                <h3 class="text-lg font-medium text-gray-900 mb-2">No Portfolio Items</h3>
                <p class="text-gray-500 mb-4">Add your first portfolio item to showcase your work.</p>
                <button 
                    @click="showForm = true" 
                    class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors"
                >
                    Add Portfolio Item
                </button>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue';
import { router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { PencilIcon, TrashIcon, PhotoIcon } from '@heroicons/vue/24/outline';

const showForm = ref(false);
const loading = ref(false);
const editingId = ref(null);
const portfolioItems = ref([]);
const form = reactive({
    title: '',
    category: 'app',
    image: null,
    description: '',
    details_url: '',
    order: 0,
    is_active: true
});

onMounted(() => {
    fetchPortfolioItems();
});

const fetchPortfolioItems = async () => {
    try {
        const response = await axios.get('/api/portfolio-items');
        portfolioItems.value = response.data;
    } catch (error) {
        console.error('Error fetching portfolio items:', error);
    }
};

const handleImageUpload = (event) => {
    const file = event.target.files[0];
    if (file) {
        form.image = file;
    }
};

const editPortfolioItem = (item) => {
    editingId.value = item.id;
    Object.assign(form, {
        title: item.title,
        category: item.category,
        image: item.image,
        description: item.description,
        details_url: item.details_url,
        order: item.order,
        is_active: item.is_active
    });
    showForm.value = true;
};

const cancelEdit = () => {
    editingId.value = null;
    resetForm();
    showForm.value = false;
};

const resetForm = () => {
    Object.assign(form, {
        title: '',
        category: 'app',
        image: null,
        description: '',
        details_url: '',
        order: 0,
        is_active: true
    });
};

const savePortfolioItem = async () => {
    loading.value = true;
    
    try {
        const formData = new FormData();
        formData.append('title', form.title);
        formData.append('category', form.category);
        formData.append('description', form.description);
        formData.append('details_url', form.details_url);
        formData.append('order', form.order);
        formData.append('is_active', form.is_active);
        
        if (form.image instanceof File) {
            formData.append('image', form.image);
        } else if (form.image) {
            formData.append('image', form.image);
        }

        if (editingId.value) {
            await axios.post(`/api/portfolio-items/${editingId.value}`, formData, {
                headers: { 'Content-Type': 'multipart/form-data' }
            });
        } else {
            await axios.post('/api/portfolio-items', formData, {
                headers: { 'Content-Type': 'multipart/form-data' }
            });
        }
        
        await fetchPortfolioItems();
        cancelEdit();
        router.reload({ only: ['flash'] });
    } catch (error) {
        console.error('Error saving portfolio item:', error);
    } finally {
        loading.value = false;
    }
};

const deletePortfolioItem = async (id) => {
    if (!confirm('Are you sure you want to delete this portfolio item?')) return;
    
    try {
        await axios.delete(`/api/portfolio-items/${id}`);
        await fetchPortfolioItems();
        router.reload({ only: ['flash'] });
    } catch (error) {
        console.error('Error deleting portfolio item:', error);
    }
};
</script>