<template>
    <AdminLayout>
        <div class="max-w-7xl mx-auto">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-900">Hero Section Management</h2>
                <button 
                    @click="showForm = !showForm" 
                    class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors"
                >
                    {{ showForm ? 'Cancel' : heroData ? 'Edit Hero' : 'Create Hero' }}
                </button>
            </div>

            <!-- Hero Form -->
            <div v-if="showForm" class="bg-white rounded-lg shadow p-6 mb-8">
                <form @submit.prevent="saveHero" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Name</label>
                            <input 
                                v-model="form.name" 
                                type="text" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                required
                            />
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Background Image</label>
                            <div class="mt-1 flex items-center">
                                <input 
                                    type="file" 
                                    @change="handleImageUpload"
                                    accept="image/*"
                                    class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100"
                                />
                            </div>
                            <div v-if="form.background_image" class="mt-2">
                                <img :src="form.background_image" class="h-32 w-full object-cover rounded">
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Roles</label>
                        <div class="flex space-x-2 mb-2">
                            <input 
                                v-model="newRole" 
                                type="text" 
                                placeholder="Add a role"
                                class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            />
                            <button 
                                type="button" 
                                @click="addRole"
                                class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200"
                            >
                                Add
                            </button>
                        </div>
                        <div class="flex flex-wrap gap-2 mt-2">
                            <span 
                                v-for="(role, index) in form.roles" 
                                :key="index"
                                class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-indigo-100 text-indigo-800"
                            >
                                {{ role }}
                                <button 
                                    type="button" 
                                    @click="removeRole(index)"
                                    class="ml-2 text-indigo-600 hover:text-indigo-900"
                                >
                                    ×
                                </button>
                            </span>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Social Links</label>
                        <div v-for="(link, index) in form.social_links" :key="index" class="grid grid-cols-2 gap-2 mb-2">
                            <input 
                                v-model="link.platform" 
                                type="text" 
                                placeholder="Platform (e.g., Twitter)"
                                class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            />
                            <div class="flex">
                                <input 
                                    v-model="link.url" 
                                    type="text" 
                                    placeholder="URL"
                                    class="flex-1 px-3 py-2 border border-gray-300 rounded-l-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                />
                                <input 
                                    v-model="link.icon" 
                                    type="text" 
                                    placeholder="Icon class"
                                    class="w-32 px-3 py-2 border border-gray-300 border-l-0 rounded-r-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                />
                                <button 
                                    type="button" 
                                    @click="removeSocialLink(index)"
                                    class="ml-2 px-3 py-2 bg-red-100 text-red-600 rounded-md hover:bg-red-200"
                                >
                                    Remove
                                </button>
                            </div>
                        </div>
                        <button 
                            type="button" 
                            @click="addSocialLink"
                            class="mt-2 px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200"
                        >
                            Add Social Link
                        </button>
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
                            {{ loading ? 'Saving...' : 'Save Hero Section' }}
                        </button>
                    </div>
                </form>
            </div>

            <!-- Hero Preview -->
            <div v-if="heroData" class="bg-white rounded-lg shadow overflow-hidden">
                <div class="relative h-64 bg-gray-900">
                    <img 
                        v-if="heroData.background_image" 
                        :src="heroData.background_image" 
                        class="w-full h-full object-cover opacity-50"
                    />
                    <div class="absolute inset-0 flex flex-col items-center justify-center text-white p-4">
                        <h3 class="text-3xl font-bold mb-2">{{ heroData.name }}</h3>
                        <p class="text-lg">I'm 
                            <span class="text-indigo-300">{{ heroData.roles ? heroData.roles[0] : '' }}</span>
                        </p>
                        <div class="flex space-x-4 mt-4">
                            <a 
                                v-for="social in heroData.social_links" 
                                :key="social.platform"
                                :href="social.url"
                                class="text-white hover:text-indigo-300"
                            >
                                <i :class="social.icon" class="text-xl"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-sm text-gray-500">Status</p>
                            <span :class="[
                                'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium',
                                heroData.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'
                            ]">
                                {{ heroData.is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                        <div class="text-sm text-gray-500">
                            Last updated: {{ formatDate(heroData.updated_at) }}
                        </div>
                    </div>
                </div>
            </div>

            <div v-else class="bg-white rounded-lg shadow p-8 text-center">
                <PhotographIcon class="h-12 w-12 text-gray-400 mx-auto mb-4" />
                <h3 class="text-lg font-medium text-gray-900 mb-2">No Hero Section Found</h3>
                <p class="text-gray-500 mb-4">Create your hero section to display on the homepage.</p>
                <button 
                    @click="showForm = true" 
                    class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors"
                >
                    Create Hero Section
                </button>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue';
import { router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { PhotoIcon as PhotographIcon } from '@heroicons/vue/24/outline';

const showForm = ref(false);
const loading = ref(false);
const newRole = ref('');
const heroData = ref(null);
const form = reactive({
    name: '',
    roles: [],
    background_image: null,
    social_links: [],
    is_active: true
});

onMounted(() => {
    fetchHeroData();
});

const fetchHeroData = async () => {
    try {
        const response = await axios.get('/api/hero');
        if (response.data) {
            heroData.value = response.data;
            Object.assign(form, {
                name: response.data.name,
                roles: response.data.roles || [],
                social_links: response.data.social_links || [],
                is_active: response.data.is_active
            });
        }
    } catch (error) {
        console.error('Error fetching hero data:', error);
    }
};

const handleImageUpload = (event) => {
    const file = event.target.files[0];
    if (file) {
        form.background_image = file;
    }
};

const addRole = () => {
    if (newRole.value.trim()) {
        form.roles.push(newRole.value.trim());
        newRole.value = '';
    }
};

const removeRole = (index) => {
    form.roles.splice(index, 1);
};

const addSocialLink = () => {
    form.social_links.push({ platform: '', url: '', icon: '' });
};

const removeSocialLink = (index) => {
    form.social_links.splice(index, 1);
};

const saveHero = async () => {
    loading.value = true;
    
    try {
        const formData = new FormData();
        formData.append('name', form.name);
        formData.append('roles', JSON.stringify(form.roles));
        formData.append('social_links', JSON.stringify(form.social_links));
        formData.append('is_active', form.is_active);
        
        if (form.background_image instanceof File) {
            formData.append('background_image', form.background_image);
        }

        let response;
        if (heroData.value) {
            response = await axios.post(`/api/hero/${heroData.value.id}`, formData, {
                headers: { 'Content-Type': 'multipart/form-data' }
            });
        } else {
            response = await axios.post('/api/hero', formData, {
                headers: { 'Content-Type': 'multipart/form-data' }
            });
        }

        heroData.value = response.data;
        showForm.value = false;
        router.reload({ only: ['flash'] });
    } catch (error) {
        console.error('Error saving hero:', error);
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