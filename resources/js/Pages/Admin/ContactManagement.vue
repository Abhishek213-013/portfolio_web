<template>
    <AdminLayout>
        <div class="max-w-7xl mx-auto">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-900">Contact Information Management</h2>
                <button 
                    @click="showForm = !showForm" 
                    class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors"
                >
                    {{ showForm ? 'Cancel' : 'Add New Contact Info' }}
                </button>
            </div>

            <!-- Add/Edit Contact Form -->
            <div v-if="showForm" class="bg-white rounded-lg shadow p-6 mb-8">
                <form @submit.prevent="saveContactInfo" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Type</label>
                            <select 
                                v-model="form.type" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                required
                            >
                                <option value="address">Address</option>
                                <option value="phone">Phone</option>
                                <option value="email">Email</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Icon Class</label>
                            <input 
                                v-model="form.icon" 
                                type="text" 
                                placeholder="e.g., bi bi-geo-alt"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                required
                            />
                            <p class="mt-1 text-sm text-gray-500">Use Bootstrap Icons class names</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Title</label>
                            <input 
                                v-model="form.title" 
                                type="text" 
                                placeholder="e.g., Address, Call Us, Email Us"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                required
                            />
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Value</label>
                            <input 
                                v-model="form.value" 
                                type="text" 
                                placeholder="e.g., A108 Adam Street, New York"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                required
                            />
                        </div>
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
                            {{ loading ? 'Saving...' : (editingId ? 'Update Contact Info' : 'Add Contact Info') }}
                        </button>
                    </div>
                </form>
            </div>

            <!-- Contact Information List -->
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Contact Information</h3>
                </div>
                <div class="p-6">
                    <draggable 
                        v-model="sortedContactInfo" 
                        @end="updateOrder"
                        item-key="id"
                        class="space-y-4"
                    >
                        <template #item="{ element: contact }">
                            <div class="border rounded-lg p-6 hover:shadow-md transition-shadow">
                                <div class="flex items-start space-x-4">
                                    <div :class="[
                                        'flex-shrink-0 h-12 w-12 rounded-lg flex items-center justify-center',
                                        getTypeColor(contact.type)
                                    ]">
                                        <i :class="contact.icon" class="text-xl text-white"></i>
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <h4 class="text-lg font-medium text-gray-900">{{ contact.title }}</h4>
                                                <p class="text-gray-700 mt-1">{{ contact.value }}</p>
                                            </div>
                                            <div class="flex items-center space-x-2">
                                                <span :class="[
                                                    'px-2 py-1 text-xs font-medium rounded-full',
                                                    contact.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'
                                                ]">
                                                    {{ contact.is_active ? 'Active' : 'Inactive' }}
                                                </span>
                                                <span class="px-2 py-1 text-xs font-medium bg-gray-100 text-gray-800 rounded-full capitalize">
                                                    {{ contact.type }}
                                                </span>
                                                <div class="flex space-x-1">
                                                    <button 
                                                        @click="editContactInfo(contact)"
                                                        class="p-1 text-blue-600 hover:text-blue-900"
                                                    >
                                                        <PencilIcon class="h-4 w-4" />
                                                    </button>
                                                    <button 
                                                        @click="deleteContactInfo(contact.id)"
                                                        class="p-1 text-red-600 hover:text-red-900"
                                                    >
                                                        <TrashIcon class="h-4 w-4" />
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mt-4 flex justify-between items-center text-sm text-gray-500">
                                            <div class="flex items-center space-x-4">
                                                <span>Icon: <code class="bg-gray-100 px-2 py-1 rounded">{{ contact.icon }}</code></span>
                                            </div>
                                            <span>Order: {{ contact.order }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </draggable>

                    <div v-if="sortedContactInfo.length === 0" class="text-center py-12">
                        <EnvelopeIcon class="h-16 w-16 text-gray-400 mx-auto mb-4" />
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No Contact Information</h3>
                        <p class="text-gray-500 mb-4">Add contact information for your portfolio.</p>
                        <button 
                            @click="showForm = true" 
                            class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors"
                        >
                            Add Contact Information
                        </button>
                    </div>
                </div>
            </div>

            <!-- Contact Info Preview -->
            <div v-if="sortedContactInfo.length > 0" class="mt-8 bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Preview</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div 
                        v-for="contact in sortedContactInfo.filter(c => c.is_active)" 
                        :key="contact.id"
                        class="border rounded-lg p-6 text-center"
                    >
                        <div :class="[
                            'inline-flex h-16 w-16 items-center justify-center rounded-full mb-4',
                            getTypeColor(contact.type)
                        ]">
                            <i :class="contact.icon" class="text-2xl text-white"></i>
                        </div>
                        <h4 class="text-lg font-medium text-gray-900 mb-2">{{ contact.title }}</h4>
                        <p class="text-gray-600">{{ contact.value }}</p>
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
import { PencilIcon, TrashIcon, EnvelopeIcon } from '@heroicons/vue/24/outline';

const showForm = ref(false);
const loading = ref(false);
const editingId = ref(null);
const contactInfo = ref([]);
const form = reactive({
    type: 'address',
    icon: '',
    title: '',
    value: '',
    order: 0,
    is_active: true
});

const sortedContactInfo = computed(() => 
    contactInfo.value.slice().sort((a, b) => a.order - b.order)
);

onMounted(() => {
    fetchContactInfo();
});

const fetchContactInfo = async () => {
    try {
        const response = await axios.get('/api/contact-info');
        contactInfo.value = response.data;
    } catch (error) {
        console.error('Error fetching contact info:', error);
    }
};

const getTypeColor = (type) => {
    const colorMap = {
        address: 'bg-blue-600',
        phone: 'bg-green-600',
        email: 'bg-purple-600'
    };
    return colorMap[type] || 'bg-gray-600';
};

const editContactInfo = (contact) => {
    editingId.value = contact.id;
    Object.assign(form, {
        type: contact.type,
        icon: contact.icon,
        title: contact.title,
        value: contact.value,
        order: contact.order,
        is_active: contact.is_active
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
        type: 'address',
        icon: '',
        title: '',
        value: '',
        order: 0,
        is_active: true
    });
};

const saveContactInfo = async () => {
    loading.value = true;
    
    try {
        if (editingId.value) {
            await axios.put(`/api/contact-info/${editingId.value}`, form);
        } else {
            await axios.post('/api/contact-info', form);
        }
        
        await fetchContactInfo();
        cancelEdit();
        router.reload({ only: ['flash'] });
    } catch (error) {
        console.error('Error saving contact info:', error);
    } finally {
        loading.value = false;
    }
};

const deleteContactInfo = async (id) => {
    if (!confirm('Are you sure you want to delete this contact information?')) return;
    
    try {
        await axios.delete(`/api/contact-info/${id}`);
        await fetchContactInfo();
        router.reload({ only: ['flash'] });
    } catch (error) {
        console.error('Error deleting contact info:', error);
    }
};

const updateOrder = async () => {
    const contactsWithOrder = sortedContactInfo.value.map((contact, index) => ({
        id: contact.id,
        order: index
    }));
    
    try {
        // Update order for each contact
        for (const contact of contactsWithOrder) {
            await axios.put(`/api/contact-info/${contact.id}`, { order: contact.order });
        }
        await fetchContactInfo();
    } catch (error) {
        console.error('Error updating order:', error);
    }
};
</script>