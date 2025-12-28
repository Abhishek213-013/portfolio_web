<template>
    <AdminLayout>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 space-y-4 sm:space-y-0">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Hero Section Management</h2>
                <button 
                    @click="showForm = !showForm" 
                    class="px-4 py-2 bg-indigo-600 dark:bg-indigo-500 text-white rounded-lg hover:bg-indigo-700 dark:hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900 transition-colors"
                >
                    {{ showForm ? 'Cancel' : heroData ? 'Edit Hero' : 'Create Hero' }}
                </button>
            </div>

            <!-- Hero Form -->
            <div v-if="showForm" class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-4 sm:p-6 mb-8 border border-gray-200 dark:border-gray-700">
                <form @submit.prevent="saveHero" class="space-y-6">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Name</label>
                            <input 
                                v-model="form.name" 
                                type="text" 
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400"
                                required
                                placeholder="Enter your name"
                            />
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Background Image</label>
                            <div class="mt-1 flex flex-col sm:flex-row items-start sm:items-center space-y-4 sm:space-y-0">
                                <input 
                                    type="file" 
                                    @change="handleImageUpload"
                                    accept="image/*"
                                    class="w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 dark:file:bg-gray-700 dark:file:text-gray-300"
                                />
                            </div>
                            <div v-if="form.background_image" class="mt-4">
                                <img :src="form.background_image" 
                                     class="h-48 w-full object-cover rounded-lg border-2 border-gray-300 dark:border-gray-600 shadow-md">
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">Preview of uploaded image</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 dark:bg-gray-900 p-4 rounded-lg">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Roles</label>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-3">These roles will be displayed in the hero section (e.g., "Web Developer", "UI/UX Designer")</p>
                        
                        <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-2 mb-3">
                            <input 
                                v-model="newRole" 
                                type="text" 
                                placeholder="Add a role (e.g., Full Stack Developer)"
                                class="flex-1 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:border-transparent bg-white dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400"
                                @keyup.enter="addRole"
                            />
                            <button 
                                type="button" 
                                @click="addRole"
                                class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-md hover:bg-gray-300 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-400 dark:focus:ring-gray-500 transition-colors"
                            >
                                Add Role
                            </button>
                        </div>
                        
                        <div class="flex flex-wrap gap-2 mt-2" v-if="form.roles && form.roles.length > 0">
                            <span 
                                v-for="(role, index) in form.roles" 
                                :key="index"
                                class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200"
                            >
                                {{ role }}
                                <button 
                                    type="button" 
                                    @click="removeRole(index)"
                                    class="ml-2 text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300"
                                    :title="`Remove ${role}`"
                                >
                                    ×
                                </button>
                            </span>
                        </div>
                        <p v-else class="text-sm text-gray-500 dark:text-gray-400 italic">No roles added yet. Add at least one role to display.</p>
                    </div>

                    <div class="bg-gray-50 dark:bg-gray-900 p-4 rounded-lg">
                        <div class="flex items-center justify-between mb-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Social Links</label>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Add your social media profiles</p>
                            </div>
                            <button 
                                type="button" 
                                @click="addSocialLink"
                                class="px-3 py-1 bg-indigo-100 dark:bg-indigo-900 text-indigo-700 dark:text-indigo-300 rounded-md hover:bg-indigo-200 dark:hover:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 transition-colors text-sm"
                            >
                                + Add Link
                            </button>
                        </div>
                        
                        <div v-for="(link, index) in form.social_links" :key="index" 
                             class="grid grid-cols-1 lg:grid-cols-12 gap-2 mb-3 p-3 bg-white dark:bg-gray-800 rounded-md border border-gray-200 dark:border-gray-700">
                            <div class="lg:col-span-4">
                                <input 
                                    v-model="link.platform" 
                                    type="text" 
                                    placeholder="Platform (e.g., Twitter)"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 text-sm"
                                />
                            </div>
                            <div class="lg:col-span-5">
                                <input 
                                    v-model="link.url" 
                                    type="url" 
                                    placeholder="URL (e.g., https://twitter.com/username)"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 text-sm"
                                />
                            </div>
                            <div class="lg:col-span-2">
                                <input 
                                    v-model="link.icon" 
                                    type="text" 
                                    placeholder="Icon class"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 text-sm"
                                />
                            </div>
                            <div class="lg:col-span-1">
                                <button 
                                    type="button" 
                                    @click="removeSocialLink(index)"
                                    class="w-full px-3 py-2 bg-red-100 dark:bg-red-900 text-red-600 dark:text-red-300 rounded-md hover:bg-red-200 dark:hover:bg-red-800 focus:outline-none focus:ring-2 focus:ring-red-500 dark:focus:ring-red-400 transition-colors"
                                    :title="`Remove ${link.platform || 'social link'}`"
                                >
                                    Remove
                                </button>
                            </div>
                        </div>
                        
                        <div v-if="!form.social_links || form.social_links.length === 0" 
                             class="text-center py-4 border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-md">
                            <p class="text-sm text-gray-500 dark:text-gray-400">No social links added yet</p>
                        </div>
                        
                        <div class="mt-4 text-xs text-gray-500 dark:text-gray-400">
                            <p><strong>Icon Examples:</strong></p>
                            <ul class="list-disc list-inside space-y-1 mt-1">
                                <li>Font Awesome: <code class="bg-gray-200 dark:bg-gray-700 px-1 rounded">fab fa-twitter</code></li>
                                <li>Heroicons: <code class="bg-gray-200 dark:bg-gray-700 px-1 rounded">h-5 w-5</code></li>
                                <li>Custom: <code class="bg-gray-200 dark:bg-gray-700 px-1 rounded">social-twitter</code></li>
                            </ul>
                        </div>
                    </div>

                    <div class="flex items-center p-4 bg-gray-50 dark:bg-gray-900 rounded-lg">
                        <input 
                            v-model="form.is_active" 
                            type="checkbox" 
                            id="is_active"
                            class="h-4 w-4 text-indigo-600 dark:text-indigo-400 focus:ring-indigo-500 dark:focus:ring-indigo-400 border-gray-300 dark:border-gray-600 rounded"
                        />
                        <label for="is_active" class="ml-2 block text-sm font-medium text-gray-900 dark:text-white">
                            Active (Show on website)
                        </label>
                    </div>

                    <div class="flex flex-col sm:flex-row justify-end space-y-3 sm:space-y-0 sm:space-x-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <button 
                            type="button" 
                            @click="showForm = false"
                            class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-400 dark:focus:ring-gray-500 rounded-md transition-colors"
                        >
                            Cancel
                        </button>
                        <button 
                            type="submit" 
                            :disabled="loading"
                            :class="[
                                'px-4 py-2 text-sm font-medium text-white rounded-md transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800',
                                loading 
                                    ? 'bg-indigo-400 dark:bg-indigo-500 cursor-not-allowed' 
                                    : 'bg-indigo-600 dark:bg-indigo-500 hover:bg-indigo-700 dark:hover:bg-indigo-600'
                            ]"
                        >
                            <span v-if="loading" class="flex items-center justify-center">
                                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Saving...
                            </span>
                            <span v-else>{{ heroData ? 'Update Hero Section' : 'Create Hero Section' }}</span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Hero Preview -->
            <div v-if="heroData" class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden border border-gray-200 dark:border-gray-700">
                <div class="relative h-72 bg-gradient-to-r from-gray-900 to-gray-800 dark:from-gray-800 dark:to-gray-900">
                    <img 
                        v-if="heroData.background_image" 
                        :src="heroData.background_image" 
                        class="absolute inset-0 w-full h-full object-cover opacity-30"
                        alt="Hero background"
                    />
                    <div class="relative z-10 h-full flex flex-col items-center justify-center text-white p-6 text-center">
                        <h3 class="text-4xl font-bold mb-3">{{ heroData.name }}</h3>
                        
                        <div class="text-xl mb-6">
                            <span class="text-gray-300">I'm a </span>
                            <span class="relative">
                                <span class="text-indigo-300 font-semibold inline-block">
                                    {{ heroData.roles && heroData.roles.length > 0 ? heroData.roles[0] : 'Your Profession' }}
                                </span>
                                <span v-if="heroData.roles && heroData.roles.length > 1" 
                                      class="text-sm text-gray-400 ml-2">+{{ heroData.roles.length - 1 }} more</span>
                            </span>
                        </div>
                        
                        <div v-if="heroData.roles && heroData.roles.length > 0" class="flex flex-wrap justify-center gap-2 mb-8 max-w-2xl">
                            <span v-for="(role, index) in heroData.roles" :key="index" 
                                  class="px-3 py-1 bg-white/10 backdrop-blur-sm rounded-full text-sm text-gray-200 border border-white/20">
                                {{ role }}
                            </span>
                        </div>
                        
                        <div v-if="heroData.social_links && heroData.social_links.length > 0" class="flex space-x-4">
                            <a 
                                v-for="social in heroData.social_links" 
                                :key="social.platform"
                                :href="social.url"
                                target="_blank"
                                class="h-12 w-12 bg-white/10 backdrop-blur-sm rounded-full flex items-center justify-center text-white hover:bg-white/20 hover:scale-110 transition-all duration-300 border border-white/20"
                                :title="social.platform"
                            >
                                <i v-if="social.icon" :class="social.icon" class="text-xl"></i>
                                <span v-else class="text-sm font-medium">{{ social.platform.charAt(0) }}</span>
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="p-6">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center space-y-4 sm:space-y-0">
                        <div class="flex items-center space-x-4">
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Status</p>
                                <span :class="[
                                    'inline-flex items-center px-3 py-1 rounded-full text-sm font-medium',
                                    heroData.is_active 
                                        ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' 
                                        : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200'
                                ]">
                                    <span class="w-2 h-2 rounded-full mr-2" :class="heroData.is_active ? 'bg-green-500' : 'bg-red-500'"></span>
                                    {{ heroData.is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Roles</p>
                                <p class="text-sm font-medium text-gray-900 dark:text-white">{{ heroData.roles ? heroData.roles.length : 0 }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Social Links</p>
                                <p class="text-sm font-medium text-gray-900 dark:text-white">{{ heroData.social_links ? heroData.social_links.length : 0 }}</p>
                            </div>
                        </div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">
                            Last updated: {{ formatDate(heroData.updated_at) }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Empty State -->
            <div v-else-if="!showForm" class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-8 text-center border border-gray-200 dark:border-gray-700">
                <div class="max-w-md mx-auto">
                    <div class="h-16 w-16 bg-indigo-100 dark:bg-indigo-900 rounded-full flex items-center justify-center mx-auto mb-4">
                        <PhotographIcon class="h-8 w-8 text-indigo-600 dark:text-indigo-400" />
                    </div>
                    <h3 class="text-xl font-medium text-gray-900 dark:text-white mb-2">No Hero Section Found</h3>
                    <p class="text-gray-600 dark:text-gray-400 mb-6">Create your hero section to display on the homepage. This is the first thing visitors will see.</p>
                    <button 
                        @click="showForm = true" 
                        class="px-6 py-3 bg-indigo-600 dark:bg-indigo-500 text-white font-medium rounded-lg hover:bg-indigo-700 dark:hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition-colors"
                    >
                        Create Hero Section
                    </button>
                </div>
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
    background_image_file: null,
    social_links: [],
    is_active: true
});

onMounted(() => {
    fetchHeroData();
});

const fetchHeroData = async () => {
    try {
        const response = await axios.get('/api/hero');
        console.log('Hero data response:', response.data);
        
        if (response.data && response.data.data) {
            // If response has data property (from our updated controller)
            heroData.value = response.data.data;
        } else if (response.data) {
            // If response is the data directly
            heroData.value = response.data;
        }
        
        // Populate form if hero data exists
        if (heroData.value) {
            Object.assign(form, {
                name: heroData.value.name || '',
                roles: heroData.value.roles || [],
                social_links: heroData.value.social_links || [],
                is_active: heroData.value.is_active !== undefined ? heroData.value.is_active : true,
                background_image: heroData.value.background_image || null
            });
        }
    } catch (error) {
        console.error('Error fetching hero data:', error);
        console.error('Error response:', error.response?.data);
    }
};

const handleImageUpload = (event) => {
    const file = event.target.files[0];
    if (file) {
        // Validate file type
        if (!file.type.match('image.*')) {
            alert('Please select an image file (JPEG, PNG, GIF)');
            event.target.value = '';
            return;
        }
        
        // Validate file size (max 5MB)
        if (file.size > 5 * 1024 * 1024) {
            alert('Image size should be less than 5MB');
            event.target.value = '';
            return;
        }
        
        // Create preview URL
        form.background_image = URL.createObjectURL(file);
        
        // Store the actual file for upload
        form.background_image_file = file;
    }
};

const addRole = () => {
    if (newRole.value.trim()) {
        if (!form.roles) form.roles = [];
        form.roles.push(newRole.value.trim());
        newRole.value = '';
    }
};

const removeRole = (index) => {
    if (form.roles && form.roles.length > index) {
        form.roles.splice(index, 1);
    }
};

const addSocialLink = () => {
    if (!form.social_links) form.social_links = [];
    form.social_links.push({ platform: '', url: '', icon: '' });
};

const removeSocialLink = (index) => {
    if (form.social_links && form.social_links.length > index) {
        form.social_links.splice(index, 1);
    }
};

const saveHero = async () => {
    loading.value = true;
    
    try {
        // Validate form
        if (!form.name || !form.name.trim()) {
            alert('Name is required');
            loading.value = false;
            return;
        }
        
        if (!form.roles || form.roles.length === 0) {
            alert('At least one role is required');
            loading.value = false;
            return;
        }
        
        // Create FormData properly
        const formData = new FormData();
        formData.append('name', form.name.trim());
        formData.append('is_active', form.is_active ? '1' : '0');
        
        // Append roles as separate fields
        form.roles.forEach((role, index) => {
            formData.append(`roles[${index}]`, role);
        });
        
        // Append social links properly
        if (form.social_links && form.social_links.length > 0) {
            form.social_links.forEach((link, index) => {
                if (link.platform) formData.append(`social_links[${index}][platform]`, link.platform);
                if (link.url) formData.append(`social_links[${index}][url]`, link.url);
                if (link.icon) formData.append(`social_links[${index}][icon]`, link.icon);
            });
        } else {
            // Send empty array
            formData.append('social_links', JSON.stringify([]));
        }
        
        // Use the file if it's a new upload
        if (form.background_image_file) {
            formData.append('background_image', form.background_image_file);
        } else if (form.background_image && typeof form.background_image === 'string') {
            // If it's an actual URL (not a blob), send it as URL
            if (!form.background_image.startsWith('blob:')) {
                formData.append('background_image_url', form.background_image);
            }
        }

        let url, method;
        if (heroData.value && heroData.value.id) {
            // Update existing
            url = `/api/hero/${heroData.value.id}`;
            method = 'put';
        } else {
            // Create new
            url = '/api/hero';
            method = 'post';
        }

        console.log('Sending form data:');
        for (let pair of formData.entries()) {
            console.log(pair[0] + ': ' + pair[1]);
        }

        const response = await axios({
            method: method,
            url: url,
            data: formData,
            headers: { 
                'Content-Type': 'multipart/form-data',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
            }
        });

        console.log('Save response:', response);

        if (response.data.success) {
            heroData.value = response.data.data;
            showForm.value = false;
            
            // Reset file reference
            form.background_image_file = null;
            
            // Show success message
            alert('Hero section saved successfully!');
            
            // Refresh data
            await fetchHeroData();
        }
    } catch (error) {
        console.error('Error saving hero:', error);
        console.error('Error response:', error.response?.data);
        
        // Show detailed error message
        if (error.response?.data?.errors) {
            const errors = error.response.data.errors;
            const errorMessages = Object.values(errors).flat().join('\n');
            alert(`Validation errors:\n${errorMessages}`);
        } else {
            const errorMessage = error.response?.data?.message || 'Failed to save hero section. Please try again.';
            alert(errorMessage);
        }
    } finally {
        loading.value = false;
    }
};

const formatDate = (dateString) => {
    try {
        if (!dateString) return 'Never';
        const date = new Date(dateString);
        return date.toLocaleDateString('en-US', {
            year: 'numeric',
            month: 'short',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
    } catch (error) {
        return 'Invalid date';
    }
};
</script>

<style scoped>
/* Smooth transitions for all elements */
* {
    transition-property: background-color, border-color, color, fill, stroke;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
    transition-duration: 200ms;
}

/* Custom scrollbar */
::-webkit-scrollbar {
    width: 8px;
}

::-webkit-scrollbar-track {
    background: transparent;
}

::-webkit-scrollbar-thumb {
    background-color: rgba(156, 163, 175, 0.5);
    border-radius: 4px;
}

.dark ::-webkit-scrollbar-thumb {
    background-color: rgba(75, 85, 99, 0.5);
}

.dark ::-webkit-scrollbar-thumb:hover {
    background-color: rgba(75, 85, 99, 0.7);
}

/* Focus styles for better accessibility */
input:focus, textarea:focus, button:focus {
    outline: none;
}

/* Button hover effects */
button:not(:disabled) {
    transition: all 150ms ease;
}

button:not(:disabled):hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
}

button:not(:disabled):active {
    transform: translateY(0);
}

/* Loading spinner animation */
@keyframes spin {
    from {
        transform: rotate(0deg);
    }
    to {
        transform: rotate(360deg);
    }
}

.animate-spin {
    animation: spin 1s linear infinite;
}

/* Image styling */
img {
    max-width: 100%;
    height: auto;
    object-fit: cover;
}

/* Responsive adjustments */
@media (max-width: 640px) {
    .grid-cols-12 {
        grid-template-columns: 1fr;
    }
    
    .lg\:col-span-4,
    .lg\:col-span-5,
    .lg\:col-span-2,
    .lg\:col-span-1 {
        grid-column: span 1;
    }
}

/* Social link icon styling */
i[class^="fa-"] {
    font-style: normal;
}

/* Hero preview gradient animation */
.bg-gradient-to-r {
    background-size: 200% 200%;
    animation: gradient 15s ease infinite;
}

@keyframes gradient {
    0% {
        background-position: 0% 50%;
    }
    50% {
        background-position: 100% 50%;
    }
    100% {
        background-position: 0% 50%;
    }
}
</style>