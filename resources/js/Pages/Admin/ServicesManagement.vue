<template>
    <AdminLayout>
        <div class="max-w-7xl mx-auto">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-900">Services Management</h2>
                <button 
                    @click="showForm = !showForm" 
                    class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors"
                >
                    {{ showForm ? 'Cancel' : 'Add New Service' }}
                </button>
            </div>

            <!-- Add/Edit Service Form -->
            <div v-if="showForm" class="bg-white rounded-lg shadow p-6 mb-8">
                <form @submit.prevent="saveService" class="space-y-6">
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
                            <label class="block text-sm font-medium text-gray-700 mb-2">Icon Class</label>
                            <input 
                                v-model="form.icon" 
                                type="text" 
                                placeholder="e.g., bi bi-activity"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                required
                            />
                            <p class="mt-1 text-sm text-gray-500">Use Bootstrap Icons class names</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Color</label>
                            <select 
                                v-model="form.color" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                required
                            >
                                <option value="cyan">Cyan</option>
                                <option value="orange">Orange</option>
                                <option value="teal">Teal</option>
                                <option value="red">Red</option>
                                <option value="indigo">Indigo</option>
                                <option value="pink">Pink</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Order</label>
                            <input 
                                v-model="form.order" 
                                type="number" 
                                min="0"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            />
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                        <textarea 
                            v-model="form.description" 
                            rows="4"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            required
                        ></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">SVG Path (Optional)</label>
                        <textarea 
                            v-model="form.svg_path" 
                            rows="3"
                            placeholder="SVG path data for background"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
                        ></textarea>
                        <p class="mt-1 text-sm text-gray-500">Leave empty for default background</p>
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
                            {{ loading ? 'Saving...' : (editingId ? 'Update Service' : 'Add Service') }}
                        </button>
                    </div>
                </form>
            </div>

            <!-- Services Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div 
                    v-for="service in sortedServices" 
                    :key="service.id"
                    class="bg-white rounded-lg shadow overflow-hidden hover:shadow-lg transition-shadow"
                >
                    <div :class="`relative overflow-hidden bg-${service.color}-50 p-8`">
                        <div class="absolute top-4 right-4 flex space-x-1">
                            <button 
                                @click="editService(service)"
                                class="p-1 bg-white rounded-full shadow"
                            >
                                <PencilIcon class="h-4 w-4 text-blue-600" />
                            </button>
                            <button 
                                @click="deleteService(service.id)"
                                class="p-1 bg-white rounded-full shadow"
                            >
                                <TrashIcon class="h-4 w-4 text-red-600" />
                            </button>
                        </div>
                        
                        <div class="relative z-10">
                            <div :class="`text-4xl mb-4 text-${service.color}-600`">
                                <i :class="service.icon"></i>
                            </div>
                            <h4 class="text-xl font-bold text-gray-900 mb-2">{{ service.title }}</h4>
                            <p class="text-gray-700">{{ service.description }}</p>
                        </div>
                        
                        <!-- SVG Background -->
                        <div v-if="service.svg_path" class="absolute inset-0 opacity-10">
                            <svg width="100%" height="100%" viewBox="0 0 600 600" xmlns="http://www.w3.org/2000/svg">
                                <path :d="service.svg_path" :fill="getColorHex(service.color)" />
                            </svg>
                        </div>
                    </div>
                    
                    <div class="p-4 border-t border-gray-100">
                        <div class="flex justify-between items-center">
                            <div class="flex items-center space-x-2">
                                <span :class="[
                                    'px-2 py-1 text-xs font-medium rounded-full',
                                    service.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'
                                ]">
                                    {{ service.is_active ? 'Active' : 'Inactive' }}
                                </span>
                                <span :class="`px-2 py-1 text-xs font-medium text-${service.color}-800 bg-${service.color}-100 rounded-full`">
                                    {{ service.color }}
                                </span>
                            </div>
                            <span class="text-sm text-gray-500">Order: {{ service.order }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div v-if="sortedServices.length === 0" class="text-center bg-white rounded-lg shadow p-12">
                <CogIcon class="h-16 w-16 text-gray-400 mx-auto mb-4" />
                <h3 class="text-lg font-medium text-gray-900 mb-2">No Services</h3>
                <p class="text-gray-500 mb-4">Add services you offer to showcase your expertise.</p>
                <button 
                    @click="showForm = true" 
                    class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors"
                >
                    Add Service
                </button>
            </div>

            <!-- Color Legend -->
            <div class="mt-8 bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Color Legend</h3>
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                    <div v-for="color in colors" :key="color" class="flex items-center space-x-2">
                        <div :class="`h-6 w-6 rounded bg-${color}-500`"></div>
                        <span class="text-sm text-gray-700 capitalize">{{ color }}</span>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { ref, reactive, computed, onMounted } from 'vue';
import { router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { PencilIcon, TrashIcon, CogIcon } from '@heroicons/vue/24/outline';

const showForm = ref(false);
const loading = ref(false);
const editingId = ref(null);
const services = ref([]);
const form = reactive({
    title: '',
    icon: '',
    color: 'cyan',
    description: '',
    svg_path: '',
    order: 0,
    is_active: true
});

const colors = ['cyan', 'orange', 'teal', 'red', 'indigo', 'pink'];

const sortedServices = computed(() => 
    services.value.slice().sort((a, b) => a.order - b.order)
);

onMounted(() => {
    fetchServices();
});

const fetchServices = async () => {
    try {
        const response = await axios.get('/api/services');
        services.value = response.data;
    } catch (error) {
        console.error('Error fetching services:', error);
    }
};

const getColorHex = (color) => {
    const colorMap = {
        cyan: '#22d3ee',
        orange: '#fb923c',
        teal: '#2dd4bf',
        red: '#f87171',
        indigo: '#818cf8',
        pink: '#f472b6'
    };
    return colorMap[color] || '#6b7280';
};

const editService = (service) => {
    editingId.value = service.id;
    Object.assign(form, {
        title: service.title,
        icon: service.icon,
        color: service.color,
        description: service.description,
        svg_path: service.svg_path,
        order: service.order,
        is_active: service.is_active
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
        icon: '',
        color: 'cyan',
        description: '',
        svg_path: '',
        order: 0,
        is_active: true
    });
};

const saveService = async () => {
    loading.value = true;
    
    try {
        if (editingId.value) {
            await axios.put(`/api/services/${editingId.value}`, form);
        } else {
            await axios.post('/api/services', form);
        }
        
        await fetchServices();
        cancelEdit();
        router.reload({ only: ['flash'] });
    } catch (error) {
        console.error('Error saving service:', error);
    } finally {
        loading.value = false;
    }
};

const deleteService = async (id) => {
    if (!confirm('Are you sure you want to delete this service?')) return;
    
    try {
        await axios.delete(`/api/services/${id}`);
        await fetchServices();
        router.reload({ only: ['flash'] });
    } catch (error) {
        console.error('Error deleting service:', error);
    }
};
</script>