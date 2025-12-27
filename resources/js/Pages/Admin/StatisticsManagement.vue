<template>
    <AdminLayout>
        <div class="max-w-7xl mx-auto">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-900">Statistics Management</h2>
                <button 
                    @click="showForm = !showForm" 
                    class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors"
                >
                    {{ showForm ? 'Cancel' : 'Add New Statistic' }}
                </button>
            </div>

            <!-- Add/Edit Statistic Form -->
            <div v-if="showForm" class="bg-white rounded-lg shadow p-6 mb-8">
                <form @submit.prevent="saveStatistic" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Icon Class</label>
                            <input 
                                v-model="form.icon" 
                                type="text" 
                                placeholder="e.g., bi bi-emoji-smile"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                required
                            />
                            <p class="mt-1 text-sm text-gray-500">Use Bootstrap Icons class names</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Label</label>
                            <input 
                                v-model="form.label" 
                                type="text" 
                                placeholder="e.g., Happy Clients"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                required
                            />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Value</label>
                            <input 
                                v-model="form.value" 
                                type="number" 
                                min="0"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                required
                            />
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Color</label>
                            <select 
                                v-model="form.color" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            >
                                <option value="">Default</option>
                                <option value="blue">Blue</option>
                                <option value="green">Green</option>
                                <option value="yellow">Yellow</option>
                                <option value="red">Red</option>
                                <option value="purple">Purple</option>
                                <option value="indigo">Indigo</option>
                            </select>
                        </div>
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
                            {{ loading ? 'Saving...' : (editingId ? 'Update Statistic' : 'Add Statistic') }}
                        </button>
                    </div>
                </form>
            </div>

            <!-- Statistics Grid -->
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Statistics List</h3>
                </div>
                <div class="p-6">
                    <draggable 
                        v-model="statisticsList" 
                        @end="updateOrder"
                        item-key="id"
                        class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6"
                    >
                        <template #item="{ element: stat }">
                            <div class="border rounded-lg p-6 text-center hover:shadow-lg transition-shadow">
                                <div class="flex justify-between items-start mb-4">
                                    <div :class="`text-3xl mb-2 text-${stat.color || 'gray'}-600`">
                                        <i :class="stat.icon"></i>
                                    </div>
                                    <div class="flex space-x-1">
                                        <button 
                                            @click="editStatistic(stat)"
                                            class="p-1 text-blue-600 hover:text-blue-900"
                                        >
                                            <PencilIcon class="h-4 w-4" />
                                        </button>
                                        <button 
                                            @click="deleteStatistic(stat.id)"
                                            class="p-1 text-red-600 hover:text-red-900"
                                        >
                                            <TrashIcon class="h-4 w-4" />
                                        </button>
                                    </div>
                                </div>
                                <div class="text-4xl font-bold text-gray-900 mb-2">{{ stat.value }}</div>
                                <div class="text-gray-600">{{ stat.label }}</div>
                                <div class="mt-4 flex justify-between items-center text-xs text-gray-500">
                                    <span :class="[
                                        'px-2 py-1 rounded-full',
                                        stat.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'
                                    ]">
                                        {{ stat.is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                    <span>Order: {{ stat.order }}</span>
                                </div>
                            </div>
                        </template>
                    </draggable>
                    <div v-if="statisticsList.length === 0" class="text-center text-gray-500 py-8">
                        No statistics added yet
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { ref, reactive, computed, onMounted } from 'vue';
import { router } from '@inertiajs/vue3';
import { VueDraggableNext as draggable } from 'vuedraggable';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { PencilIcon, TrashIcon } from '@heroicons/vue/24/outline';

const showForm = ref(false);
const loading = ref(false);
const editingId = ref(null);
const statisticsList = ref([]);
const form = reactive({
    icon: '',
    label: '',
    value: 0,
    color: '',
    order: 0,
    is_active: true
});

onMounted(() => {
    fetchStatistics();
});

const fetchStatistics = async () => {
    try {
        const response = await axios.get('/api/statistics');
        statisticsList.value = response.data;
    } catch (error) {
        console.error('Error fetching statistics:', error);
    }
};

const editStatistic = (stat) => {
    editingId.value = stat.id;
    Object.assign(form, {
        icon: stat.icon,
        label: stat.label,
        value: stat.value,
        color: stat.color,
        order: stat.order,
        is_active: stat.is_active
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
        icon: '',
        label: '',
        value: 0,
        color: '',
        order: 0,
        is_active: true
    });
};

const saveStatistic = async () => {
    loading.value = true;
    
    try {
        if (editingId.value) {
            await axios.put(`/api/statistics/${editingId.value}`, form);
        } else {
            await axios.post('/api/statistics', form);
        }
        
        await fetchStatistics();
        cancelEdit();
        router.reload({ only: ['flash'] });
    } catch (error) {
        console.error('Error saving statistic:', error);
    } finally {
        loading.value = false;
    }
};

const deleteStatistic = async (id) => {
    if (!confirm('Are you sure you want to delete this statistic?')) return;
    
    try {
        await axios.delete(`/api/statistics/${id}`);
        await fetchStatistics();
        router.reload({ only: ['flash'] });
    } catch (error) {
        console.error('Error deleting statistic:', error);
    }
};

const updateOrder = async () => {
    const statsWithOrder = statisticsList.value.map((stat, index) => ({
        id: stat.id,
        order: index
    }));
    
    try {
        await axios.post('/api/statistics/order', { statistics: statsWithOrder });
        await fetchStatistics();
    } catch (error) {
        console.error('Error updating order:', error);
    }
};
</script>