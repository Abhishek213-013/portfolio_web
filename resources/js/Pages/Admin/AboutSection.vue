<template>
    <AdminLayout>
        <div class="max-w-7xl mx-auto">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-900">About Section Management</h2>
                <button 
                    @click="showForm = !showForm" 
                    class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors"
                >
                    {{ showForm ? 'Cancel' : aboutData ? 'Edit About' : 'Create About' }}
                </button>
            </div>

            <!-- About Form -->
            <div v-if="showForm" class="bg-white rounded-lg shadow p-6 mb-8">
                <form @submit.prevent="saveAbout" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Profile Image</label>
                            <div class="mt-1 flex items-center">
                                <input 
                                    type="file" 
                                    @change="handleImageUpload"
                                    accept="image/*"
                                    class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100"
                                />
                            </div>
                            <div v-if="form.profile_image" class="mt-2">
                                <img :src="form.profile_image instanceof File ? URL.createObjectURL(form.profile_image) : form.profile_image" 
                                     class="h-32 w-32 object-cover rounded-full">
                            </div>
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

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                        <textarea 
                            v-model="form.description" 
                            rows="2"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            required
                        ></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Bio</label>
                        <textarea 
                            v-model="form.bio" 
                            rows="3"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            required
                        ></textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Left Column Details</h3>
                            <div v-for="(detail, index) in leftDetails" :key="index" class="grid grid-cols-2 gap-2 mb-2">
                                <input 
                                    v-model="detail.label" 
                                    type="text" 
                                    placeholder="Label"
                                    class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                />
                                <input 
                                    v-model="detail.value" 
                                    type="text" 
                                    placeholder="Value"
                                    class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                />
                            </div>
                            <button 
                                type="button" 
                                @click="addDetail('left')"
                                class="mt-2 px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200"
                            >
                                Add Detail
                            </button>
                        </div>

                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Right Column Details</h3>
                            <div v-for="(detail, index) in rightDetails" :key="index" class="grid grid-cols-2 gap-2 mb-2">
                                <input 
                                    v-model="detail.label" 
                                    type="text" 
                                    placeholder="Label"
                                    class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                />
                                <input 
                                    v-model="detail.value" 
                                    type="text" 
                                    placeholder="Value"
                                    class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                />
                            </div>
                            <button 
                                type="button" 
                                @click="addDetail('right')"
                                class="mt-2 px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200"
                            >
                                Add Detail
                            </button>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Extended Bio</label>
                        <textarea 
                            v-model="form.extended_bio" 
                            rows="4"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            required
                        ></textarea>
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
                            @click="showForm = false"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-md"
                        >
                            Cancel
                        </button>
                        <button 
                            type="submit" 
                            :disabled="loading"
                            class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-md disabled:opacity-50"
                        >
                            {{ loading ? 'Saving...' : 'Save About Section' }}
                        </button>
                    </div>
                </form>
            </div>

            <!-- About Preview -->
            <div v-if="aboutData" class="bg-white rounded-lg shadow overflow-hidden">
                <div class="p-6">
                    <div class="flex flex-col md:flex-row items-center md:items-start space-y-6 md:space-y-0 md:space-x-6">
                        <img 
                            v-if="aboutData.profile_image" 
                            :src="aboutData.profile_image" 
                            class="h-48 w-48 object-cover rounded-lg shadow"
                        />
                        <div class="flex-1">
                            <h3 class="text-2xl font-bold text-gray-900 mb-2">{{ aboutData.title }}</h3>
                            <p class="text-gray-600 italic mb-4">{{ aboutData.bio }}</p>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                                <div>
                                    <ul class="space-y-2">
                                        <li v-for="detail in aboutData.personal_details?.left || []" :key="detail.label" class="text-gray-700">
                                            <strong>{{ detail.label }}:</strong> {{ detail.value }}
                                        </li>
                                    </ul>
                                </div>
                                <div>
                                    <ul class="space-y-2">
                                        <li v-for="detail in aboutData.personal_details?.right || []" :key="detail.label" class="text-gray-700">
                                            <strong>{{ detail.label }}:</strong> {{ detail.value }}
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            
                            <p class="text-gray-700">{{ aboutData.extended_bio }}</p>
                        </div>
                    </div>
                    
                    <div class="mt-6 pt-6 border-t border-gray-200 flex justify-between items-center">
                        <div>
                            <span :class="[
                                'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium',
                                aboutData.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'
                            ]">
                                {{ aboutData.is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                        <div class="text-sm text-gray-500">
                            Last updated: {{ formatDate(aboutData.updated_at) }}
                        </div>
                    </div>
                </div>
            </div>

            <div v-else class="bg-white rounded-lg shadow p-8 text-center">
                <UserIcon class="h-12 w-12 text-gray-400 mx-auto mb-4" />
                <h3 class="text-lg font-medium text-gray-900 mb-2">No About Section Found</h3>
                <p class="text-gray-500 mb-4">Create your about section to display on the homepage.</p>
                <button 
                    @click="showForm = true" 
                    class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors"
                >
                    Create About Section
                </button>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { ref, reactive, computed, onMounted } from 'vue';
import { router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { UserIcon } from '@heroicons/vue/24/outline';

const showForm = ref(false);
const loading = ref(false);
const aboutData = ref(null);
const form = reactive({
    profile_image: null,
    title: '',
    description: '',
    bio: '',
    personal_details: {
        left: [],
        right: []
    },
    extended_bio: '',
    is_active: true
});

const leftDetails = computed({
    get: () => form.personal_details.left || [],
    set: (value) => form.personal_details.left = value
});

const rightDetails = computed({
    get: () => form.personal_details.right || [],
    set: (value) => form.personal_details.right = value
});

onMounted(() => {
    fetchAboutData();
});

const fetchAboutData = async () => {
    try {
        const response = await axios.get('/api/about');
        if (response.data) {
            aboutData.value = response.data;
            Object.assign(form, {
                title: response.data.title,
                description: response.data.description,
                bio: response.data.bio,
                personal_details: response.data.personal_details || { left: [], right: [] },
                extended_bio: response.data.extended_bio,
                is_active: response.data.is_active,
                profile_image: response.data.profile_image
            });
        }
    } catch (error) {
        console.error('Error fetching about data:', error);
    }
};

const handleImageUpload = (event) => {
    const file = event.target.files[0];
    if (file) {
        form.profile_image = file;
    }
};

const addDetail = (side) => {
    if (side === 'left') {
        leftDetails.value.push({ label: '', value: '' });
    } else {
        rightDetails.value.push({ label: '', value: '' });
    }
};

const saveAbout = async () => {
    loading.value = true;
    
    try {
        const formData = new FormData();
        formData.append('title', form.title);
        formData.append('description', form.description);
        formData.append('bio', form.bio);
        formData.append('personal_details', JSON.stringify(form.personal_details));
        formData.append('extended_bio', form.extended_bio);
        formData.append('is_active', form.is_active);
        
        if (form.profile_image instanceof File) {
            formData.append('profile_image', form.profile_image);
        }

        let response;
        if (aboutData.value) {
            response = await axios.post(`/api/about/${aboutData.value.id}`, formData, {
                headers: { 'Content-Type': 'multipart/form-data' }
            });
        } else {
            response = await axios.post('/api/about', formData, {
                headers: { 'Content-Type': 'multipart/form-data' }
            });
        }

        aboutData.value = response.data;
        showForm.value = false;
        router.reload({ only: ['flash'] });
    } catch (error) {
        console.error('Error saving about:', error);
    } finally {
        loading.value = false;
    }
};

const formatDate = (dateString) => {
    return new Date(dateString).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    });
};
</script>