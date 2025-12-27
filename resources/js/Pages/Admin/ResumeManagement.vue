<template>
    <AdminLayout>
        <div class="max-w-7xl mx-auto">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-900">Resume Management</h2>
                <div class="flex space-x-2">
                    <select v-model="activeType" class="px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="summary">Summary</option>
                        <option value="education">Education</option>
                        <option value="experience">Experience</option>
                    </select>
                    <button 
                        @click="showForm = !showForm" 
                        class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors"
                    >
                        {{ showForm ? 'Cancel' : 'Add New' }}
                    </button>
                </div>
            </div>

            <!-- Add/Edit Resume Form -->
            <div v-if="showForm" class="bg-white rounded-lg shadow p-6 mb-8">
                <form @submit.prevent="saveResumeSection" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Type</label>
                            <select 
                                v-model="form.type" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                required
                            >
                                <option value="summary">Summary</option>
                                <option value="education">Education</option>
                                <option value="experience">Experience</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Title</label>
                            <input 
                                v-model="form.title" 
                                type="text" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                required
                            />
                        </div>
                    </div>

                    <div v-if="form.type !== 'summary'" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Subtitle/Institution</label>
                            <input 
                                v-model="form.subtitle" 
                                type="text" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            />
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Period</label>
                            <input 
                                v-model="form.period" 
                                type="text" 
                                placeholder="e.g., 2019 - Present"
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
                        <label class="block text-sm font-medium text-gray-700 mb-2">Details (Bullet Points)</label>
                        <div v-for="(detail, index) in form.details" :key="index" class="flex space-x-2 mb-2">
                            <input 
                                v-model="form.details[index]" 
                                type="text" 
                                placeholder="Detail item"
                                class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            />
                            <button 
                                type="button" 
                                @click="removeDetail(index)"
                                class="px-3 py-2 bg-red-100 text-red-600 rounded-md hover:bg-red-200"
                            >
                                Remove
                            </button>
                        </div>
                        <button 
                            type="button" 
                            @click="addDetail"
                            class="mt-2 px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200"
                        >
                            Add Detail
                        </button>
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
                            {{ loading ? 'Saving...' : (editingId ? 'Update Section' : 'Add Section') }}
                        </button>
                    </div>
                </form>
            </div>

            <!-- Resume Sections Tabs -->
            <div class="bg-white rounded-lg shadow">
                <div class="border-b border-gray-200">
                    <nav class="flex -mb-px">
                        <button 
                            v-for="tab in tabs" 
                            :key="tab.value"
                            @click="activeType = tab.value"
                            :class="[
                                'py-4 px-6 text-sm font-medium border-b-2',
                                activeType === tab.value
                                    ? 'border-indigo-500 text-indigo-600'
                                    : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'
                            ]"
                        >
                            {{ tab.label }}
                        </button>
                    </nav>
                </div>

                <div class="p-6">
                    <draggable 
                        v-model="filteredResumeSections" 
                        @end="updateOrder"
                        item-key="id"
                        class="space-y-6"
                    >
                        <template #item="{ element: section }">
                            <div class="border rounded-lg p-6 hover:shadow-md transition-shadow">
                                <div class="flex justify-between items-start mb-4">
                                    <div>
                                        <h4 class="text-lg font-medium text-gray-900">{{ section.title }}</h4>
                                        <div v-if="section.subtitle || section.period" class="flex items-center space-x-4 mt-2">
                                            <span v-if="section.subtitle" class="text-sm text-gray-600">{{ section.subtitle }}</span>
                                            <span v-if="section.period" class="text-sm font-medium text-indigo-600">{{ section.period }}</span>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <span :class="[
                                            'px-2 py-1 text-xs font-medium rounded-full',
                                            section.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'
                                        ]">
                                            {{ section.is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                        <div class="flex space-x-1">
                                            <button 
                                                @click="editResumeSection(section)"
                                                class="p-1 text-blue-600 hover:text-blue-900"
                                            >
                                                <PencilIcon class="h-4 w-4" />
                                            </button>
                                            <button 
                                                @click="deleteResumeSection(section.id)"
                                                class="p-1 text-red-600 hover:text-red-900"
                                            >
                                                <TrashIcon class="h-4 w-4" />
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                
                                <p class="text-gray-700 mb-4">{{ section.description }}</p>
                                
                                <ul v-if="section.details && section.details.length > 0" class="space-y-1">
                                    <li v-for="(detail, index) in section.details" :key="index" class="text-gray-600">
                                        • {{ detail }}
                                    </li>
                                </ul>
                                
                                <div class="mt-4 pt-4 border-t border-gray-100 flex justify-between items-center text-sm text-gray-500">
                                    <span>Type: {{ section.type }}</span>
                                    <span>Order: {{ section.order }}</span>
                                </div>
                            </div>
                        </template>
                    </draggable>

                    <div v-if="filteredResumeSections.length === 0" class="text-center py-12">
                        <AcademicCapIcon class="h-16 w-16 text-gray-400 mx-auto mb-4" />
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No {{ activeType }} sections</h3>
                        <p class="text-gray-500 mb-4">Add your first {{ activeType }} section to your resume.</p>
                        <button 
                            @click="showForm = true; form.type = activeType" 
                            class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors"
                        >
                            Add {{ getTypeLabel(activeType) }} Section
                        </button>
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
import { PencilIcon, TrashIcon, AcademicCapIcon } from '@heroicons/vue/24/outline';

const showForm = ref(false);
const loading = ref(false);
const editingId = ref(null);
const activeType = ref('summary');
const resumeSections = ref([]);
const form = reactive({
    type: 'summary',
    title: '',
    subtitle: '',
    period: '',
    institution: '',
    description: '',
    details: [],
    order: 0,
    is_active: true
});

const tabs = [
    { label: 'Summary', value: 'summary' },
    { label: 'Education', value: 'education' },
    { label: 'Experience', value: 'experience' }
];

const filteredResumeSections = computed(() => 
    resumeSections.value
        .filter(section => section.type === activeType.value)
        .sort((a, b) => a.order - b.order)
);

onMounted(() => {
    fetchResumeSections();
});

const fetchResumeSections = async () => {
    try {
        const response = await axios.get('/api/resume-sections');
        resumeSections.value = response.data;
    } catch (error) {
        console.error('Error fetching resume sections:', error);
    }
};

const getTypeLabel = (type) => {
    const tab = tabs.find(t => t.value === type);
    return tab ? tab.label : type;
};

const editResumeSection = (section) => {
    editingId.value = section.id;
    Object.assign(form, {
        type: section.type,
        title: section.title,
        subtitle: section.subtitle,
        period: section.period,
        institution: section.institution,
        description: section.description,
        details: section.details || [],
        order: section.order,
        is_active: section.is_active
    });
    showForm.value = true;
    activeType.value = section.type;
};

const cancelEdit = () => {
    editingId.value = null;
    resetForm();
    showForm.value = false;
};

const resetForm = () => {
    Object.assign(form, {
        type: activeType.value,
        title: '',
        subtitle: '',
        period: '',
        institution: '',
        description: '',
        details: [],
        order: 0,
        is_active: true
    });
};

const addDetail = () => {
    form.details.push('');
};

const removeDetail = (index) => {
    form.details.splice(index, 1);
};

const saveResumeSection = async () => {
    loading.value = true;
    
    try {
        const data = { ...form };
        
        if (editingId.value) {
            await axios.put(`/api/resume-sections/${editingId.value}`, data);
        } else {
            await axios.post('/api/resume-sections', data);
        }
        
        await fetchResumeSections();
        cancelEdit();
        router.reload({ only: ['flash'] });
    } catch (error) {
        console.error('Error saving resume section:', error);
    } finally {
        loading.value = false;
    }
};

const deleteResumeSection = async (id) => {
    if (!confirm('Are you sure you want to delete this section?')) return;
    
    try {
        await axios.delete(`/api/resume-sections/${id}`);
        await fetchResumeSections();
        router.reload({ only: ['flash'] });
    } catch (error) {
        console.error('Error deleting resume section:', error);
    }
};

const updateOrder = async () => {
    const sectionsWithOrder = filteredResumeSections.value.map((section, index) => ({
        id: section.id,
        order: index
    }));
    
    try {
        // Update order for each section
        for (const section of sectionsWithOrder) {
            await axios.put(`/api/resume-sections/${section.id}`, { order: section.order });
        }
        await fetchResumeSections();
    } catch (error) {
        console.error('Error updating order:', error);
    }
};
</script>