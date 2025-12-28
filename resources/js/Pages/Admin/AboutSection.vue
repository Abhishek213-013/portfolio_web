<template>
    <AdminLayout>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 space-y-4 sm:space-y-0">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">About Section Management</h2>
                <button 
                    @click="showForm = !showForm" 
                    class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900 transition-colors"
                >
                    {{ showForm ? 'Cancel' : aboutData ? 'Edit About' : 'Create About' }}
                </button>
            </div>

            <!-- About Form -->
            <div v-if="showForm" class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-4 sm:p-6 mb-8 border border-gray-200 dark:border-gray-700">
                <form @submit.prevent="saveAbout" class="space-y-6">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Profile Image</label>
                                <div class="mt-1 flex flex-col sm:flex-row items-start sm:items-center space-y-4 sm:space-y-0">
                                    <input 
                                        type="file" 
                                        @change="handleImageUpload"
                                        accept="image/*"
                                        class="w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 dark:file:bg-gray-700 dark:file:text-gray-300"
                                    />
                                </div>
                                <div v-if="form.profile_image" class="mt-4">
                                    <img :src="form.profile_image instanceof File ? URL.createObjectURL(form.profile_image) : form.profile_image" 
                                         class="h-32 w-32 object-cover rounded-full border-4 border-white dark:border-gray-800 shadow-md">
                                </div>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Title</label>
                                <input 
                                    v-model="form.title" 
                                    type="text" 
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400"
                                    required
                                    placeholder="Enter title"
                                />
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Description</label>
                        <textarea 
                            v-model="form.description" 
                            rows="2"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400"
                            required
                            placeholder="Enter description"
                        ></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Bio</label>
                        <textarea 
                            v-model="form.bio" 
                            rows="3"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400"
                            required
                            placeholder="Enter bio"
                        ></textarea>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <div class="bg-gray-50 dark:bg-gray-900 p-4 rounded-lg">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Left Column Details</h3>
                            <div v-for="(detail, index) in leftDetails" :key="index" class="grid grid-cols-1 sm:grid-cols-2 gap-2 mb-3">
                                <input 
                                    v-model="detail.label" 
                                    type="text" 
                                    placeholder="Label"
                                    class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:border-transparent bg-white dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 text-sm"
                                />
                                <input 
                                    v-model="detail.value" 
                                    type="text" 
                                    placeholder="Value"
                                    class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:border-transparent bg-white dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 text-sm"
                                />
                            </div>
                            <button 
                                type="button" 
                                @click="addDetail('left')"
                                class="mt-2 px-3 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-md hover:bg-gray-300 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-400 dark:focus:ring-gray-500 transition-colors text-sm"
                            >
                                + Add Detail
                            </button>
                        </div>

                        <div class="bg-gray-50 dark:bg-gray-900 p-4 rounded-lg">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Right Column Details</h3>
                            <div v-for="(detail, index) in rightDetails" :key="index" class="grid grid-cols-1 sm:grid-cols-2 gap-2 mb-3">
                                <input 
                                    v-model="detail.label" 
                                    type="text" 
                                    placeholder="Label"
                                    class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:border-transparent bg-white dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 text-sm"
                                />
                                <input 
                                    v-model="detail.value" 
                                    type="text" 
                                    placeholder="Value"
                                    class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:border-transparent bg-white dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 text-sm"
                                />
                            </div>
                            <button 
                                type="button" 
                                @click="addDetail('right')"
                                class="mt-2 px-3 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-md hover:bg-gray-300 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-400 dark:focus:ring-gray-500 transition-colors text-sm"
                            >
                                + Add Detail
                            </button>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Extended Bio</label>
                        <textarea 
                            v-model="form.extended_bio" 
                            rows="4"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400"
                            required
                            placeholder="Enter extended bio"
                        ></textarea>
                    </div>

                    <div class="flex items-center p-4 bg-gray-50 dark:bg-gray-900 rounded-lg">
                        <input 
                            v-model="form.is_active" 
                            type="checkbox" 
                            id="is_active"
                            class="h-4 w-4 text-indigo-600 dark:text-indigo-400 focus:ring-indigo-500 dark:focus:ring-indigo-400 border-gray-300 dark:border-gray-600 rounded"
                        />
                        <label for="is_active" class="ml-2 block text-sm font-medium text-gray-900 dark:text-white">Active (Show on website)</label>
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
                            <span v-else>Save About Section</span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- About Preview -->
            <div v-if="aboutData" class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden border border-gray-200 dark:border-gray-700">
                <div class="p-4 sm:p-6">
                    <div class="flex flex-col lg:flex-row items-center lg:items-start space-y-6 lg:space-y-0 lg:space-x-6">
                        <div class="shrink-0">
                            <div class="relative">
                                <img 
                                    v-if="aboutData.profile_image" 
                                    :src="aboutData.profile_image" 
                                    class="h-48 w-48 object-cover rounded-lg shadow-lg border-4 border-white dark:border-gray-800"
                                />
                                <div v-else class="h-48 w-48 bg-gray-200 dark:bg-gray-700 rounded-lg flex items-center justify-center">
                                    <UserIcon class="h-16 w-16 text-gray-400 dark:text-gray-500" />
                                </div>
                                <div v-if="aboutData.is_active" class="absolute top-2 right-2">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                        Active
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-3">{{ aboutData.title }}</h3>
                            <p class="text-gray-600 dark:text-gray-400 italic mb-4 border-l-4 border-indigo-500 dark:border-indigo-400 pl-4 py-1">{{ aboutData.bio }}</p>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                                <div>
                                    <h4 class="font-medium text-gray-900 dark:text-white mb-3 text-sm uppercase tracking-wider">Personal Details</h4>
                                    <ul class="space-y-2">
                                        <li v-for="(detail, index) in aboutData.personal_details?.left || []" :key="index" 
                                            class="text-gray-700 dark:text-gray-300 flex items-start">
                                            <span class="font-medium text-gray-900 dark:text-white min-w-25">{{ detail.label }}:</span>
                                            <span class="ml-2 flex-1">{{ detail.value }}</span>
                                        </li>
                                    </ul>
                                </div>
                                <div>
                                    <h4 class="font-medium text-gray-900 dark:text-white mb-3 text-sm uppercase tracking-wider">Additional Details</h4>
                                    <ul class="space-y-2">
                                        <li v-for="(detail, index) in aboutData.personal_details?.right || []" :key="index" 
                                            class="text-gray-700 dark:text-gray-300 flex items-start">
                                            <span class="font-medium text-gray-900 dark:text-white min-w-25">{{ detail.label }}:</span>
                                            <span class="ml-2 flex-1">{{ detail.value }}</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            
                            <div class="mt-6 pt-4 border-t border-gray-200 dark:border-gray-700">
                                <h4 class="font-medium text-gray-900 dark:text-white mb-3">Extended Bio</h4>
                                <p class="text-gray-700 dark:text-gray-300 leading-relaxed whitespace-pre-line">{{ aboutData.extended_bio }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-6 pt-4 border-t border-gray-200 dark:border-gray-700 flex flex-col sm:flex-row justify-between items-start sm:items-center space-y-3 sm:space-y-0">
                        <div class="flex items-center space-x-2">
                            <span :class="[
                                'inline-flex items-center px-3 py-1 rounded-full text-sm font-medium',
                                aboutData.is_active 
                                    ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' 
                                    : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200'
                            ]">
                                <span class="w-2 h-2 rounded-full mr-2" :class="aboutData.is_active ? 'bg-green-500' : 'bg-red-500'"></span>
                                {{ aboutData.is_active ? 'Active' : 'Inactive' }}
                            </span>
                            <span class="text-sm text-gray-500 dark:text-gray-400">
                                {{ aboutData.is_active ? 'Visible on website' : 'Hidden from website' }}
                            </span>
                        </div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">
                            Last updated: {{ formatDate(aboutData.updated_at) }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Empty State -->
            <div v-else-if="!showForm" class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-8 text-center border border-gray-200 dark:border-gray-700">
                <div class="max-w-md mx-auto">
                    <div class="h-16 w-16 bg-indigo-100 dark:bg-indigo-900 rounded-full flex items-center justify-center mx-auto mb-4">
                        <UserIcon class="h-8 w-8 text-indigo-600 dark:text-indigo-400" />
                    </div>
                    <h3 class="text-xl font-medium text-gray-900 dark:text-white mb-2">No About Section Found</h3>
                    <p class="text-gray-600 dark:text-gray-400 mb-6">Create your about section to display on the homepage. This section will showcase your personal and professional information.</p>
                    <button 
                        @click="showForm = true" 
                        class="px-6 py-3 bg-indigo-600 dark:bg-indigo-500 text-white font-medium rounded-lg hover:bg-indigo-700 dark:hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition-colors"
                    >
                        Create About Section
                    </button>
                </div>
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
        // Validate file type
        if (!file.type.match('image.*')) {
            alert('Please select an image file (JPEG, PNG, GIF)');
            return;
        }
        
        // Validate file size (max 5MB)
        if (file.size > 5 * 1024 * 1024) {
            alert('Image size should be less than 5MB');
            return;
        }
        
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
        formData.append('title', form.title.trim());
        formData.append('description', form.description.trim());
        formData.append('bio', form.bio.trim());
        formData.append('personal_details', JSON.stringify(form.personal_details));
        formData.append('extended_bio', form.extended_bio.trim());
        formData.append('is_active', form.is_active);
        
        if (form.profile_image instanceof File) {
            formData.append('profile_image', form.profile_image);
        }

        let response;
        if (aboutData.value && aboutData.value.id) {
            // Update existing
            response = await axios.post(`/api/about/${aboutData.value.id}`, formData, {
                headers: { 
                    'Content-Type': 'multipart/form-data',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                }
            });
        } else {
            // Create new
            response = await axios.post('/api/about', formData, {
                headers: { 
                    'Content-Type': 'multipart/form-data',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                }
            });
        }

        if (response.status === 200 || response.status === 201) {
            aboutData.value = response.data;
            showForm.value = false;
            
            // Show success message
            if (router.page.props && router.page.props.setFlash) {
                router.page.props.setFlash({
                    type: 'success',
                    message: 'About section saved successfully!'
                });
            }
            
            // Reload to show flash message
            router.reload({ only: ['flash'] });
        }
    } catch (error) {
        console.error('Error saving about:', error);
        
        // Show error message
        const errorMessage = error.response?.data?.message || 'Failed to save about section. Please try again.';
        alert(errorMessage);
    } finally {
        loading.value = false;
    }
};

const formatDate = (dateString) => {
    try {
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

/* Custom scrollbar for textareas */
textarea {
    scrollbar-width: thin;
    scrollbar-color: rgba(156, 163, 175, 0.5) transparent;
}

textarea::-webkit-scrollbar {
    width: 8px;
}

textarea::-webkit-scrollbar-track {
    background: transparent;
}

textarea::-webkit-scrollbar-thumb {
    background-color: rgba(156, 163, 175, 0.5);
    border-radius: 4px;
}

.dark textarea::-webkit-scrollbar-thumb {
    background-color: rgba(75, 85, 99, 0.5);
}

.dark textarea::-webkit-scrollbar-thumb:hover {
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

/* Responsive image styling */
img {
    max-width: 100%;
    height: auto;
}

/* Better spacing for mobile */
@media (max-width: 640px) {
    .space-y-4 > * + * {
        margin-top: 1rem;
    }
    
    .space-y-6 > * + * {
        margin-top: 1.5rem;
    }
}

/* Print styles */
@media print {
    button, form, .shadow-lg, .border {
        display: none !important;
    }
    
    .bg-white, .dark\:bg-gray-800 {
        background: white !important;
        color: black !important;
    }
}
</style>