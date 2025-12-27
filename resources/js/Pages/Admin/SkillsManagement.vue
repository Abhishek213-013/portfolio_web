<template>
    <AdminLayout>
        <div class="max-w-7xl mx-auto">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-900">Skills Management</h2>
                <button 
                    @click="showForm = !showForm" 
                    class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors"
                >
                    {{ showForm ? 'Cancel' : 'Add New Skill' }}
                </button>
            </div>

            <!-- Add/Edit Skill Form -->
            <div v-if="showForm" class="bg-white rounded-lg shadow p-6 mb-8">
                <form @submit.prevent="saveSkill" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Skill Name</label>
                            <input 
                                v-model="form.name" 
                                type="text" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                required
                            />
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Percentage (0-100)</label>
                            <div class="flex items-center space-x-2">
                                <input 
                                    v-model="form.percentage" 
                                    type="range" 
                                    min="0" 
                                    max="100" 
                                    class="flex-1"
                                />
                                <span class="w-16 text-center font-medium">{{ form.percentage }}%</span>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Column</label>
                            <select 
                                v-model="form.column" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            >
                                <option value="left">Left Column</option>
                                <option value="right">Right Column</option>
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
                            {{ loading ? 'Saving...' : (editingId ? 'Update Skill' : 'Add Skill') }}
                        </button>
                    </div>
                </form>
            </div>

            <!-- Skills List -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Left Column Skills -->
                <div class="bg-white rounded-lg shadow">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Left Column Skills</h3>
                    </div>
                    <div class="p-6">
                        <draggable 
                            v-model="leftSkills" 
                            @end="updateOrder"
                            item-key="id"
                            class="space-y-4"
                        >
                            <template #item="{ element: skill }">
                                <div class="border rounded-lg p-4 hover:shadow-md transition-shadow">
                                    <div class="flex justify-between items-center mb-2">
                                        <h4 class="font-medium text-gray-900">{{ skill.name }}</h4>
                                        <div class="flex items-center space-x-2">
                                            <span :class="[
                                                'px-2 py-1 text-xs font-semibold rounded-full',
                                                skill.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'
                                            ]">
                                                {{ skill.is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                            <div class="flex space-x-1">
                                                <button 
                                                    @click="editSkill(skill)"
                                                    class="p-1 text-blue-600 hover:text-blue-900"
                                                >
                                                    <PencilIcon class="h-4 w-4" />
                                                </button>
                                                <button 
                                                    @click="deleteSkill(skill.id)"
                                                    class="p-1 text-red-600 hover:text-red-900"
                                                >
                                                    <TrashIcon class="h-4 w-4" />
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <div class="flex-1 h-2 bg-gray-200 rounded-full overflow-hidden">
                                            <div 
                                                class="h-full bg-indigo-500 rounded-full"
                                                :style="{ width: skill.percentage + '%' }"
                                            ></div>
                                        </div>
                                        <span class="text-sm font-medium text-gray-700">{{ skill.percentage }}%</span>
                                    </div>
                                    <div class="mt-2 text-xs text-gray-500">
                                        Order: {{ skill.order }}
                                    </div>
                                </div>
                            </template>
                        </draggable>
                        <div v-if="leftSkills.length === 0" class="text-center text-gray-500 py-8">
                            No skills in left column
                        </div>
                    </div>
                </div>

                <!-- Right Column Skills -->
                <div class="bg-white rounded-lg shadow">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Right Column Skills</h3>
                    </div>
                    <div class="p-6">
                        <draggable 
                            v-model="rightSkills" 
                            @end="updateOrder"
                            item-key="id"
                            class="space-y-4"
                        >
                            <template #item="{ element: skill }">
                                <div class="border rounded-lg p-4 hover:shadow-md transition-shadow">
                                    <div class="flex justify-between items-center mb-2">
                                        <h4 class="font-medium text-gray-900">{{ skill.name }}</h4>
                                        <div class="flex items-center space-x-2">
                                            <span :class="[
                                                'px-2 py-1 text-xs font-semibold rounded-full',
                                                skill.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'
                                            ]">
                                                {{ skill.is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                            <div class="flex space-x-1">
                                                <button 
                                                    @click="editSkill(skill)"
                                                    class="p-1 text-blue-600 hover:text-blue-900"
                                                >
                                                    <PencilIcon class="h-4 w-4" />
                                                </button>
                                                <button 
                                                    @click="deleteSkill(skill.id)"
                                                    class="p-1 text-red-600 hover:text-red-900"
                                                >
                                                    <TrashIcon class="h-4 w-4" />
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <div class="flex-1 h-2 bg-gray-200 rounded-full overflow-hidden">
                                            <div 
                                                class="h-full bg-indigo-500 rounded-full"
                                                :style="{ width: skill.percentage + '%' }"
                                            ></div>
                                        </div>
                                        <span class="text-sm font-medium text-gray-700">{{ skill.percentage }}%</span>
                                    </div>
                                    <div class="mt-2 text-xs text-gray-500">
                                        Order: {{ skill.order }}
                                    </div>
                                </div>
                            </template>
                        </draggable>
                        <div v-if="rightSkills.length === 0" class="text-center text-gray-500 py-8">
                            No skills in right column
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { ref, reactive, computed, onMounted } from 'vue';
import { router } from '@inertiajs/vue3';
import draggable from 'vuedraggable';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { PencilIcon, TrashIcon } from '@heroicons/vue/24/outline';

const showForm = ref(false);
const loading = ref(false);
const editingId = ref(null);
const skills = ref([]);
const form = reactive({
    name: '',
    percentage: 50,
    column: 'left',
    order: 0,
    is_active: true
});

const leftSkills = computed(() => 
    skills.value.filter(skill => skill.column === 'left').sort((a, b) => a.order - b.order)
);

const rightSkills = computed(() => 
    skills.value.filter(skill => skill.column === 'right').sort((a, b) => a.order - b.order)
);

onMounted(() => {
    fetchSkills();
});

const fetchSkills = async () => {
    try {
        const response = await axios.get('/api/skills');
        skills.value = response.data;
    } catch (error) {
        console.error('Error fetching skills:', error);
    }
};

const editSkill = (skill) => {
    editingId.value = skill.id;
    Object.assign(form, {
        name: skill.name,
        percentage: skill.percentage,
        column: skill.column,
        order: skill.order,
        is_active: skill.is_active
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
        name: '',
        percentage: 50,
        column: 'left',
        order: 0,
        is_active: true
    });
};

const saveSkill = async () => {
    loading.value = true;
    
    try {
        if (editingId.value) {
            await axios.put(`/api/skills/${editingId.value}`, form);
        } else {
            await axios.post('/api/skills', form);
        }
        
        await fetchSkills();
        cancelEdit();
        router.reload({ only: ['flash'] });
    } catch (error) {
        console.error('Error saving skill:', error);
    } finally {
        loading.value = false;
    }
};

const deleteSkill = async (id) => {
    if (!confirm('Are you sure you want to delete this skill?')) return;
    
    try {
        await axios.delete(`/api/skills/${id}`);
        await fetchSkills();
        router.reload({ only: ['flash'] });
    } catch (error) {
        console.error('Error deleting skill:', error);
    }
};

const updateOrder = async () => {
    const allSkills = [...leftSkills.value, ...rightSkills.value];
    const skillsWithOrder = allSkills.map((skill, index) => ({
        id: skill.id,
        order: index
    }));
    
    try {
        await axios.post('/api/skills/order', { skills: skillsWithOrder });
        await fetchSkills();
    } catch (error) {
        console.error('Error updating order:', error);
    }
};
</script>