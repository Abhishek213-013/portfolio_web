<template>
    <AdminLayout>
        <div class="max-w-7xl mx-auto">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Testimonials Management</h2>
                <button 
                    @click="showForm = !showForm" 
                    class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors"
                >
                    {{ showForm ? 'Cancel' : 'Add New Testimonial' }}
                </button>
            </div>

            <!-- Add/Edit Testimonial Form -->
            <div v-if="showForm" class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 mb-8">
                <form @submit.prevent="saveTestimonial" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Name</label>
                            <input 
                                v-model="form.name" 
                                type="text" 
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                                required
                            />
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Position</label>
                            <input 
                                v-model="form.position" 
                                type="text" 
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                                required
                            />
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Image</label>
                        <div class="mt-1 flex items-center">
                            <input 
                                type="file" 
                                @change="handleImageUpload"
                                accept="image/*"
                                class="w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 dark:file:bg-indigo-900 file:text-indigo-700 dark:file:text-indigo-300 hover:file:bg-indigo-100 dark:hover:file:bg-indigo-800"
                            />
                        </div>
                        <div v-if="form.image" class="mt-2">
                            <img :src="form.image instanceof File ? URL.createObjectURL(form.image) : form.image" 
                                 class="h-32 w-32 object-cover rounded-full">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Testimonial Content</label>
                        <textarea 
                            v-model="form.content" 
                            rows="4"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                            required
                        ></textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Rating</label>
                            <div class="flex items-center space-x-1">
                                <button 
                                    v-for="star in 5" 
                                    :key="star"
                                    type="button"
                                    @click="form.rating = star"
                                    class="focus:outline-none"
                                >
                                    <StarIcon 
                                        :class="[
                                            'h-8 w-8',
                                            star <= form.rating ? 'text-yellow-400 fill-current' : 'text-gray-300 dark:text-gray-600'
                                        ]" 
                                    />
                                </button>
                                <span class="ml-2 text-gray-700 dark:text-gray-300">{{ form.rating }}/5</span>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Order</label>
                            <input 
                                v-model="form.order" 
                                type="number" 
                                min="0"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                            />
                        </div>
                    </div>

                    <div class="flex items-center">
                        <input 
                            v-model="form.is_active" 
                            type="checkbox" 
                            id="is_active"
                            class="h-4 w-4 text-indigo-600 dark:text-indigo-500 focus:ring-indigo-500 border-gray-300 dark:border-gray-600 rounded bg-white dark:bg-gray-700"
                        />
                        <label for="is_active" class="ml-2 block text-sm text-gray-900 dark:text-white">Active</label>
                    </div>

                    <div class="flex justify-end space-x-4">
                        <button 
                            type="button" 
                            @click="cancelEdit"
                            class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-md transition-colors"
                        >
                            Cancel
                        </button>
                        <button 
                            type="submit" 
                            :disabled="loading"
                            class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-md disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                        >
                            {{ loading ? 'Saving...' : (editingId ? 'Update Testimonial' : 'Add Testimonial') }}
                        </button>
                    </div>
                </form>
            </div>

            <!-- Loading State -->
            <div v-if="loading && !testimonials.length" class="text-center py-12">
                <div class="inline-block animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-indigo-600 dark:border-indigo-500"></div>
                <p class="mt-4 text-gray-600 dark:text-gray-400">Loading testimonials...</p>
            </div>

            <!-- Testimonials List -->
            <div v-else-if="testimonials.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div 
                    v-for="testimonial in testimonials" 
                    :key="testimonial.id"
                    class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden hover:shadow-lg transition-shadow duration-200"
                >
                    <div class="p-6">
                        <div class="flex items-start space-x-4 mb-4">
                            <div class="shrink-0">
                                <div v-if="testimonial.image" class="relative">
                                    <img 
                                        :src="testimonial.image" 
                                        :alt="testimonial?.name || 'Client'"
                                        class="h-16 w-16 object-cover rounded-full"
                                    />
                                </div>
                                <div v-else class="h-16 w-16 bg-gradient-to-br from-indigo-100 to-purple-100 dark:from-indigo-900 dark:to-purple-900 rounded-full flex items-center justify-center">
                                    <span class="text-xl font-bold text-indigo-600 dark:text-indigo-400">
                                        {{ testimonial?.name?.charAt(0)?.toUpperCase() || 'C' }}
                                    </span>
                                </div>
                            </div>
                            <div class="flex-1">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h4 class="text-lg font-medium text-gray-900 dark:text-white">{{ testimonial?.name || 'Client Name' }}</h4>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ testimonial?.position || 'Position' }}</p>
                                    </div>
                                    <div class="flex space-x-2">
                                        <button 
                                            @click="editTestimonial(testimonial)"
                                            class="p-1 text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-300 transition-colors"
                                            title="Edit"
                                        >
                                            <PencilIcon class="h-4 w-4" />
                                        </button>
                                        <button 
                                            @click="deleteTestimonial(testimonial.id)"
                                            class="p-1 text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300 transition-colors"
                                            title="Delete"
                                        >
                                            <TrashIcon class="h-4 w-4" />
                                        </button>
                                    </div>
                                </div>
                                <div class="flex mt-2">
                                    <StarIcon 
                                        v-for="star in 5" 
                                        :key="star"
                                        :class="[
                                            'h-4 w-4',
                                            star <= (testimonial?.rating || 0) ? 'text-yellow-400 fill-current' : 'text-gray-300 dark:text-gray-600'
                                        ]" 
                                    />
                                </div>
                            </div>
                        </div>
                        <div class="border-l-4 border-indigo-500 dark:border-indigo-400 pl-4 py-2">
                            <p class="text-gray-600 dark:text-gray-300 italic">"{{ testimonial?.content || 'No content available' }}"</p>
                        </div>
                        <div class="mt-4 flex justify-between items-center text-sm">
                            <span :class="[
                                'px-2 py-1 rounded-full text-xs font-medium',
                                testimonial?.is_active ? 'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200' : 'bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200'
                            ]">
                                {{ testimonial?.is_active ? 'Active' : 'Inactive' }}
                            </span>
                            <span class="text-gray-500 dark:text-gray-400">Order: {{ testimonial?.order || 0 }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Empty State -->
            <div v-else-if="!loading" class="text-center bg-white dark:bg-gray-800 rounded-lg shadow p-12">
                <ChatBubbleLeftRightIcon class="h-16 w-16 text-gray-400 mx-auto mb-4" />
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No Testimonials</h3>
                <p class="text-gray-500 dark:text-gray-400 mb-4">Add testimonials from your clients to build trust.</p>
                <button 
                    @click="showForm = true" 
                    class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors"
                >
                    Add Testimonial
                </button>
            </div>

            <!-- Error State -->
            <div v-if="error" class="mt-6 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
                <div class="flex">
                    <div class="shrink-0">
                        <ExclamationCircleIcon class="h-5 w-5 text-red-400" />
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800 dark:text-red-200">Failed to load testimonials</h3>
                        <div class="mt-2 text-sm text-red-700 dark:text-red-300">
                            <p>{{ error }}</p>
                        </div>
                        <div class="mt-4">
                            <button 
                                @click="fetchTestimonials"
                                class="text-sm font-medium text-red-800 dark:text-red-200 hover:text-red-900 dark:hover:text-red-100"
                            >
                                Try again
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Success Toast -->
            <div v-if="showToast" class="fixed top-4 right-4 z-50">
                <div :class="[
                    'rounded-lg shadow-lg p-4 max-w-sm',
                    toastType === 'success' 
                        ? 'bg-green-50 dark:bg-green-900 border border-green-200 dark:border-green-800' 
                        : 'bg-red-50 dark:bg-red-900 border border-red-200 dark:border-red-800'
                ]">
                    <div class="flex items-start">
                        <div class="shrink-0">
                            <CheckCircleIcon v-if="toastType === 'success'" class="h-5 w-5 text-green-400" />
                            <ExclamationCircleIcon v-else class="h-5 w-5 text-red-400" />
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ toastMessage }}</p>
                        </div>
                        <div class="ml-auto pl-3">
                            <button 
                                @click="hideToast"
                                class="inline-flex text-gray-400 hover:text-gray-500"
                            >
                                <XMarkIcon class="h-5 w-5" />
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue';
import axios from 'axios';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { 
    PencilIcon, 
    TrashIcon, 
    ChatBubbleLeftRightIcon, 
    StarIcon,
    ExclamationCircleIcon,
    CheckCircleIcon,
    XMarkIcon 
} from '@heroicons/vue/24/outline';

const API_BASE_URL = '/api';

// Reactive state
const showForm = ref(false);
const loading = ref(false);
const editingId = ref(null);
const testimonials = ref([]);
const error = ref(null);
const showToast = ref(false);
const toastMessage = ref('');
const toastType = ref('success');

// Form data
const form = reactive({
    name: '',
    position: '',
    image: null,
    content: '',
    rating: 5,
    order: 0,
    is_active: true
});

// Lifecycle
onMounted(() => {
    fetchTestimonials();
});

// Methods
const fetchTestimonials = async () => {
    loading.value = true;
    error.value = null;
    
    try {
        const response = await axios.get(`${API_BASE_URL}/testimonials`);
        
        // Debug: Log the response to see the actual structure
        console.log('API Response:', response.data);
        
        // Handle different response structures
        if (response.data && Array.isArray(response.data)) {
            testimonials.value = response.data;
        } else if (response.data && response.data.data && Array.isArray(response.data.data)) {
            testimonials.value = response.data.data;
        } else if (response.data && Array.isArray(response.data.testimonials)) {
            testimonials.value = response.data.testimonials;
        } else {
            testimonials.value = [];
            displayToast('No testimonials data found', 'error');
        }
        
        // Filter out any null or undefined items
        testimonials.value = testimonials.value.filter(item => item != null);
        
    } catch (err) {
        console.error('Error fetching testimonials:', err);
        error.value = err.response?.data?.message || 'Failed to load testimonials. Please try again.';
        
        // For testing/demo - create mock data if API fails
        if (process.env.NODE_ENV === 'development') {
            testimonials.value = getMockTestimonials();
            displayToast('Using demo data. API endpoint not found.', 'error');
        }
    } finally {
        loading.value = false;
    }
};

const handleImageUpload = (event) => {
    const file = event.target.files[0];
    if (file) {
        // Validate file size (2MB max)
        if (file.size > 2 * 1024 * 1024) {
            displayToast('Image size should be less than 2MB', 'error');
            return;
        }
        
        // Validate file type
        const validTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
        if (!validTypes.includes(file.type)) {
            displayToast('Please upload a valid image (JPEG, PNG, JPG, GIF)', 'error');
            return;
        }
        
        form.image = file;
    }
};

const editTestimonial = (testimonial) => {
    if (!testimonial) return;
    
    editingId.value = testimonial.id;
    Object.assign(form, {
        name: testimonial.name || '',
        position: testimonial.position || '',
        image: testimonial.image || null,
        content: testimonial.content || '',
        rating: testimonial.rating || 5,
        order: testimonial.order || 0,
        is_active: testimonial.is_active !== undefined ? testimonial.is_active : true
    });
    showForm.value = true;
    window.scrollTo({ top: 0, behavior: 'smooth' });
};

const cancelEdit = () => {
    editingId.value = null;
    resetForm();
    showForm.value = false;
};

const resetForm = () => {
    Object.assign(form, {
        name: '',
        position: '',
        image: null,
        content: '',
        rating: 5,
        order: 0,
        is_active: true
    });
};

// Toast functions
const displayToast = (message, type = 'success') => {
    toastMessage.value = message;
    toastType.value = type;
    showToast.value = true;
    
    // Auto hide after 5 seconds
    setTimeout(() => {
        hideToast();
    }, 5000);
};

const hideToast = () => {
    showToast.value = false;
};

const saveTestimonial = async () => {
    // Validate form
    if (!form.name.trim() || !form.position.trim() || !form.content.trim()) {
        displayToast('Please fill in all required fields', 'error');
        return;
    }
    
    loading.value = true;
    
    try {
        const formData = new FormData();
        formData.append('name', form.name);
        formData.append('position', form.position);
        formData.append('content', form.content);
        formData.append('rating', form.rating);
        formData.append('order', form.order);
        formData.append('is_active', form.is_active ? '1' : '0');
        
        if (form.image instanceof File) {
            formData.append('image', form.image);
        } else if (form.image && typeof form.image === 'string') {
            formData.append('image_url', form.image);
        }

        if (editingId.value) {
            await axios.post(`${API_BASE_URL}/testimonials/${editingId.value}`, formData, {
                headers: { 
                    'Content-Type': 'multipart/form-data',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                }
            });
            displayToast('Testimonial updated successfully!', 'success');
        } else {
            await axios.post(`${API_BASE_URL}/testimonials`, formData, {
                headers: { 
                    'Content-Type': 'multipart/form-data',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                }
            });
            displayToast('Testimonial added successfully!', 'success');
        }
        
        // Refresh the list
        await fetchTestimonials();
        cancelEdit();
        
    } catch (err) {
        console.error('Error saving testimonial:', err);
        
        if (err.response?.status === 422) {
            // Validation errors
            const errors = err.response.data.errors;
            const firstError = Object.values(errors)[0]?.[0];
            displayToast(firstError || 'Validation error', 'error');
        } else if (err.response?.status === 401) {
            displayToast('Session expired. Please login again.', 'error');
            setTimeout(() => {
                window.location.href = '/admin/login';
            }, 2000);
        } else {
            displayToast('Failed to save testimonial. Please try again.', 'error');
        }
    } finally {
        loading.value = false;
    }
};

const deleteTestimonial = async (id) => {
    if (!id) return;
    
    if (!confirm('Are you sure you want to delete this testimonial? This action cannot be undone.')) {
        return;
    }
    
    try {
        await axios.delete(`${API_BASE_URL}/testimonials/${id}`, {
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
            }
        });
        
        displayToast('Testimonial deleted successfully!', 'success');
        
        // Refresh the list
        await fetchTestimonials();
    } catch (err) {
        console.error('Error deleting testimonial:', err);
        displayToast('Failed to delete testimonial. Please try again.', 'error');
    }
};

// Demo mock data for development/testing
const getMockTestimonials = () => {
    return [
        {
            id: 1,
            name: 'John Smith',
            position: 'CEO at TechCorp',
            image: null,
            content: 'Excellent work! The portfolio design is stunning and perfectly captures the brand identity.',
            rating: 5,
            order: 1,
            is_active: true
        },
        {
            id: 2,
            name: 'Sarah Johnson',
            position: 'Product Manager',
            image: null,
            content: 'Professional and timely delivery. Highly recommended for web development projects.',
            rating: 4,
            order: 2,
            is_active: true
        },
        {
            id: 3,
            name: 'Mike Wilson',
            position: 'Marketing Director',
            image: null,
            content: 'Great attention to detail and creative solutions. Very pleased with the results.',
            rating: 5,
            order: 3,
            is_active: false
        }
    ];
};
</script>

<style scoped>
/* Custom scrollbar for testimonials */
.grid::-webkit-scrollbar {
    width: 6px;
    height: 6px;
}

.grid::-webkit-scrollbar-track {
    background: transparent;
}

.grid::-webkit-scrollbar-thumb {
    background-color: rgba(156, 163, 175, 0.5);
    border-radius: 3px;
}

.dark .grid::-webkit-scrollbar-thumb {
    background-color: rgba(75, 85, 99, 0.5);
}

/* Smooth transitions */
* {
    transition-property: background-color, border-color, color, fill, stroke, transform, opacity, box-shadow;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
    transition-duration: 200ms;
}

/* Image hover effect */
img {
    transition: transform 0.3s ease;
}

img:hover {
    transform: scale(1.05);
}

/* Toast animation */
.fixed {
    animation: slideIn 0.3s ease-out;
}

@keyframes slideIn {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}
</style>