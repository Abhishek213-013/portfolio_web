<template>
    <AdminLayout>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 space-y-4 sm:space-y-0">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Testimonials Management</h2>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Manage client testimonials and reviews</p>
                </div>
                <button 
                    @click="showForm = !showForm" 
                    class="px-4 py-2 bg-indigo-600 dark:bg-indigo-500 text-white rounded-lg hover:bg-indigo-700 dark:hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900 transition-colors"
                >
                    {{ showForm ? 'Cancel' : 'Add New Testimonial' }}
                </button>
            </div>

            <!-- Add/Edit Testimonial Form -->
            <div v-if="showForm" ref="formContainer" class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-4 sm:p-6 mb-8 border border-gray-200 dark:border-gray-700">
                <form @submit.prevent="saveTestimonial" class="space-y-6">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Client Name</label>
                            <input 
                                v-model="form.name" 
                                type="text" 
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400"
                                required
                                placeholder="Enter client name"
                            />
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Position & Company</label>
                            <input 
                                v-model="form.position" 
                                type="text" 
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400"
                                required
                                placeholder="e.g., CEO at TechCorp"
                            />
                        </div>
                    </div>

                    <div class="bg-gray-50 dark:bg-gray-900 p-4 rounded-lg">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Client Photo</label>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-3">Upload a professional photo of the client (optional)</p>
                        
                        <div class="mt-1 flex flex-col sm:flex-row items-start sm:items-center space-y-4 sm:space-y-0">
                            <input 
                                type="file" 
                                @change="handleImageUpload"
                                accept="image/*"
                                class="w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 dark:file:bg-gray-700 dark:file:text-gray-300"
                            />
                        </div>
                        
                        <div v-if="form.image" class="mt-4 flex items-center space-x-4">
                            <img :src="formImageUrl" 
                                 class="h-24 w-24 object-cover rounded-full border-4 border-white dark:border-gray-800 shadow-md">
                            <div>
                                <p class="text-sm font-medium text-gray-900 dark:text-white">Photo Preview</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Recommended: 400x400px square image</p>
                            </div>
                        </div>
                        
                        <div v-else class="mt-4 p-4 border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg text-center">
                            <div class="h-24 w-24 mx-auto bg-gradient-to-br from-indigo-100 to-purple-100 dark:from-indigo-900 dark:to-purple-900 rounded-full flex items-center justify-center mb-3">
                                <span class="text-2xl font-bold text-indigo-600 dark:text-indigo-400">
                                    {{ form.name ? form.name.charAt(0).toUpperCase() : 'C' }}
                                </span>
                            </div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">No photo uploaded. Will use initials as avatar.</p>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Testimonial Content</label>
                        <textarea 
                            v-model="form.content" 
                            rows="4"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400"
                            required
                            placeholder="Enter the client's testimonial..."
                        ></textarea>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Keep it concise and impactful (1-2 paragraphs recommended)</p>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <div class="bg-gray-50 dark:bg-gray-900 p-4 rounded-lg">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Client Rating</label>
                            <div class="flex items-center space-x-2">
                                <div class="flex space-x-1">
                                    <button 
                                        v-for="star in 5" 
                                        :key="star"
                                        type="button"
                                        @click="form.rating = star"
                                        class="focus:outline-none transform hover:scale-110 transition-transform"
                                        :title="`${star} star${star !== 1 ? 's' : ''}`"
                                    >
                                        <StarIcon 
                                            :class="[
                                                'h-8 w-8 transition-all',
                                                star <= form.rating 
                                                    ? 'text-yellow-400 fill-current' 
                                                    : 'text-gray-300 dark:text-gray-600'
                                            ]" 
                                        />
                                    </button>
                                </div>
                                <div class="ml-4">
                                    <span class="text-2xl font-bold text-gray-900 dark:text-white">{{ form.rating }}</span>
                                    <span class="text-gray-500 dark:text-gray-400">/5</span>
                                </div>
                            </div>
                            <div class="flex justify-between text-xs text-gray-500 dark:text-gray-400 mt-3">
                                <span>Poor</span>
                                <span>Fair</span>
                                <span>Good</span>
                                <span>Very Good</span>
                                <span>Excellent</span>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Display Order</label>
                            <input 
                                v-model="form.order" 
                                type="number" 
                                min="0"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                            />
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Lower numbers appear first</p>
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
                            @click="cancelEdit"
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
                            <span v-else>{{ editingId ? 'Update Testimonial' : 'Add Testimonial' }}</span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Stats Overview -->
            <div v-if="!loading && testimonials.length > 0" class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center">
                        <div class="h-10 w-10 bg-indigo-100 dark:bg-indigo-900 rounded-lg flex items-center justify-center mr-4">
                            <ChatBubbleLeftRightIcon class="h-5 w-5 text-indigo-600 dark:text-indigo-400" />
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Testimonials</p>
                            <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ testimonials.length }}</p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center">
                        <div class="h-10 w-10 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center mr-4">
                            <svg class="h-5 w-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Active</p>
                            <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ activeTestimonialsCount }}</p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center">
                        <div class="h-10 w-10 bg-yellow-100 dark:bg-yellow-900 rounded-lg flex items-center justify-center mr-4">
                            <StarIcon class="h-5 w-5 text-yellow-600 dark:text-yellow-400 fill-current" />
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Avg. Rating</p>
                            <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ averageRating.toFixed(1) }}/5</p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center">
                        <div class="h-10 w-10 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center mr-4">
                            <svg class="h-5 w-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">With Photos</p>
                            <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ testimonialsWithPhotos }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Loading State -->
            <div v-if="loading && !testimonials.length" class="text-center py-16">
                <div class="inline-block">
                    <div class="animate-spin rounded-full h-16 w-16 border-t-2 border-b-2 border-indigo-600 dark:border-indigo-500"></div>
                </div>
                <p class="mt-4 text-gray-600 dark:text-gray-400">Loading testimonials...</p>
            </div>

            <!-- Testimonials List -->
            <div v-else-if="testimonials.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div 
                    v-for="testimonial in testimonials" 
                    :key="testimonial.id"
                    class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden border border-gray-200 dark:border-gray-700 hover:shadow-xl transition-all duration-300 hover:-translate-y-1"
                >
                    <div class="p-6">
                        <!-- Client Info & Actions -->
                        <div class="flex items-start space-x-4 mb-6">
                            <div class="shrink-0">
                                <div class="relative">
                                    <div v-if="testimonial.image" 
                                         class="h-20 w-20 rounded-full overflow-hidden border-4 border-white dark:border-gray-800 shadow-lg">
                                        <img 
                                            :src="testimonial.image" 
                                            :alt="testimonial?.name || 'Client'"
                                            class="h-full w-full object-cover"
                                        />
                                    </div>
                                    <div v-else 
                                         class="h-20 w-20 bg-gradient-to-br from-indigo-500 to-purple-500 dark:from-indigo-600 dark:to-purple-600 rounded-full flex items-center justify-center shadow-lg">
                                        <span class="text-2xl font-bold text-white">
                                            {{ testimonial?.name?.charAt(0)?.toUpperCase() || 'C' }}
                                        </span>
                                    </div>
                                    
                                    <!-- Rating Badge -->
                                    <div class="absolute -bottom-1 -right-1 h-8 w-8 bg-yellow-100 dark:bg-yellow-900 rounded-full flex items-center justify-center shadow-md">
                                        <span class="text-xs font-bold text-yellow-800 dark:text-yellow-200">{{ testimonial?.rating || 5 }}</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="flex-1 min-w-0">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h4 class="text-lg font-bold text-gray-900 dark:text-white">{{ testimonial?.name || 'Client Name' }}</h4>
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ testimonial?.position || 'Position' }}</p>
                                    </div>
                                    <div class="flex space-x-2">
                                        <button 
                                            @click="editTestimonial(testimonial)"
                                            class="p-1.5 text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-300 hover:bg-blue-50 dark:hover:bg-blue-900/30 rounded-md transition-colors"
                                            title="Edit testimonial"
                                        >
                                            <PencilIcon class="h-4 w-4" />
                                        </button>
                                        <button 
                                            @click="deleteTestimonial(testimonial.id)"
                                            class="p-1.5 text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300 hover:bg-red-50 dark:hover:bg-red-900/30 rounded-md transition-colors"
                                            title="Delete testimonial"
                                        >
                                            <TrashIcon class="h-4 w-4" />
                                        </button>
                                    </div>
                                </div>
                                
                                <!-- Stars Rating -->
                                <div class="flex mt-3">
                                    <StarIcon 
                                        v-for="star in 5" 
                                        :key="star"
                                        :class="[
                                            'h-4 w-4 mr-0.5',
                                            star <= (testimonial?.rating || 0) 
                                                ? 'text-yellow-400 fill-current' 
                                                : 'text-gray-300 dark:text-gray-600'
                                        ]" 
                                    />
                                </div>
                            </div>
                        </div>
                        
                        <!-- Testimonial Content -->
                        <div class="mb-6">
                            <div class="relative">
                                <div class="absolute top-0 left-0 -ml-3 text-4xl text-gray-300 dark:text-gray-600">"</div>
                                <div class="pl-4">
                                    <p class="text-gray-700 dark:text-gray-300 italic leading-relaxed">
                                        {{ testimonial?.content || 'No content available' }}
                                    </p>
                                </div>
                                <div class="absolute bottom-0 right-0 text-4xl text-gray-300 dark:text-gray-600">"</div>
                            </div>
                        </div>
                        
                        <!-- Footer -->
                        <div class="pt-4 border-t border-gray-200 dark:border-gray-700 flex flex-col sm:flex-row justify-between items-start sm:items-center space-y-3 sm:space-y-0">
                            <div class="flex items-center space-x-3">
                                <span :class="[
                                    'px-3 py-1 rounded-full text-xs font-medium',
                                    testimonial?.is_active 
                                        ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' 
                                        : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200'
                                ]">
                                    <span class="w-2 h-2 rounded-full inline-block mr-1" 
                                          :class="testimonial?.is_active ? 'bg-green-500' : 'bg-red-500'"></span>
                                    {{ testimonial?.is_active ? 'Active' : 'Inactive' }}
                                </span>
                                <span class="text-xs text-gray-500 dark:text-gray-400">
                                    Order: {{ testimonial?.order || 0 }}
                                </span>
                            </div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                {{ formatDate(testimonial?.updated_at) }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Empty State -->
            <div v-else-if="!loading" class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-12 text-center border border-gray-200 dark:border-gray-700">
                <div class="max-w-md mx-auto">
                    <div class="h-20 w-20 bg-gradient-to-br from-indigo-100 to-purple-100 dark:from-indigo-900 dark:to-purple-900 rounded-full flex items-center justify-center mx-auto mb-6">
                        <ChatBubbleLeftRightIcon class="h-10 w-10 text-indigo-600 dark:text-indigo-400" />
                    </div>
                    <h3 class="text-xl font-medium text-gray-900 dark:text-white mb-2">No Testimonials Yet</h3>
                    <p class="text-gray-600 dark:text-gray-400 mb-6">Client testimonials help build trust and credibility. Add your first testimonial to showcase positive feedback.</p>
                    <button 
                        @click="showForm = true" 
                        class="px-6 py-3 bg-indigo-600 dark:bg-indigo-500 text-white font-medium rounded-lg hover:bg-indigo-700 dark:hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition-colors"
                    >
                        Add First Testimonial
                    </button>
                </div>
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
                                class="text-sm font-medium text-red-800 dark:text-red-200 hover:text-red-900 dark:hover:text-red-100 transition-colors"
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
                    'rounded-lg shadow-lg p-4 max-w-sm transform transition-all duration-300',
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
                                class="inline-flex text-gray-400 hover:text-gray-500 transition-colors"
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
import { ref, reactive, computed, onMounted, nextTick } from 'vue';
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
const formContainer = ref(null);

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

// Helper function to check if value is a File object
const isFile = (value) => {
    return value && 
           typeof value === 'object' && 
           value.constructor && 
           value.constructor.name === 'File';
};

// Helper function to get image URL
const getImageUrl = (image) => {
    if (!image) return null;
    
    // Check if it's a File object
    if (isFile(image)) {
        return URL.createObjectURL(image);
    }
    
    // If it's already a string (URL)
    return image;
};

// Computed properties
const activeTestimonialsCount = computed(() => {
    return testimonials.value.filter(t => t.is_active).length;
});

const averageRating = computed(() => {
    if (testimonials.value.length === 0) return 0;
    const sum = testimonials.value.reduce((acc, t) => acc + (t.rating || 0), 0);
    return sum / testimonials.value.length;
});

const testimonialsWithPhotos = computed(() => {
    return testimonials.value.filter(t => t.image).length;
});

const formImageUrl = computed(() => getImageUrl(form.image));

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
        
        console.log('API Response:', response.data);
        
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
        const validTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/webp'];
        if (!validTypes.includes(file.type)) {
            displayToast('Please upload a valid image (JPEG, PNG, JPG, GIF, WEBP)', 'error');
            return;
        }
        
        form.image = file;
    }
};

const editTestimonial = async (testimonial) => {
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
    
    // Wait for Vue to render the form
    await nextTick();
    
    // Scroll to form using the template ref
    if (formContainer.value) {
        formContainer.value.scrollIntoView({ 
            behavior: 'smooth', 
            block: 'start' 
        });
    }
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
    
    if (form.rating < 1 || form.rating > 5) {
        displayToast('Rating must be between 1 and 5', 'error');
        return;
    }
    
    loading.value = true;
    
    try {
        const formData = new FormData();
        formData.append('name', form.name.trim());
        formData.append('position', form.position.trim());
        formData.append('content', form.content.trim());
        formData.append('rating', form.rating.toString());
        formData.append('order', form.order.toString());
        formData.append('is_active', form.is_active ? '1' : '0');
        
        // Handle image upload
        if (form.image) {
            if (isFile(form.image)) {
                formData.append('image', form.image);
            } else if (typeof form.image === 'string') {
                formData.append('image_url', form.image);
            }
        }

        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
        const headers = { 
            'Content-Type': 'multipart/form-data',
            'X-Requested-With': 'XMLHttpRequest'
        };
        
        if (csrfToken) {
            headers['X-CSRF-TOKEN'] = csrfToken;
        }

        let response;
        
        if (editingId.value) {
            // Use POST with _method=PUT for file uploads
            formData.append('_method', 'PUT');
            response = await axios.post(`${API_BASE_URL}/testimonials/${editingId.value}`, formData, { headers });
            displayToast('Testimonial updated successfully!', 'success');
        } else {
            response = await axios.post(`${API_BASE_URL}/testimonials`, formData, { headers });
            displayToast('Testimonial added successfully!', 'success');
        }
        
        console.log('API Response:', response.data);
        
        // Refresh the list
        await fetchTestimonials();
        cancelEdit();
        
    } catch (err) {
        console.error('Error saving testimonial:', err);
        console.error('Error details:', err.response?.data);
        
        if (err.response?.status === 422) {
            const errors = err.response.data.errors;
            console.log('Validation errors:', errors);
            
            // Get all error messages
            const errorMessages = Object.values(errors).flat();
            displayToast(errorMessages[0] || 'Validation error', 'error');
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
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
        const headers = {};
        
        if (csrfToken) {
            headers['X-CSRF-TOKEN'] = csrfToken;
        }
        
        await axios.delete(`${API_BASE_URL}/testimonials/${id}`, { headers });
        
        displayToast('Testimonial deleted successfully!', 'success');
        
        // Refresh the list
        await fetchTestimonials();
    } catch (err) {
        console.error('Error deleting testimonial:', err);
        displayToast('Failed to delete testimonial. Please try again.', 'error');
    }
};

const formatDate = (dateString) => {
    try {
        const date = new Date(dateString);
        const now = new Date();
        const diffTime = Math.abs(now - date);
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
        
        if (diffDays === 0) return 'Today';
        if (diffDays === 1) return 'Yesterday';
        if (diffDays < 7) return `${diffDays} days ago`;
        if (diffDays < 30) return `${Math.floor(diffDays / 7)} weeks ago`;
        
        return date.toLocaleDateString('en-US', {
            month: 'short',
            day: 'numeric'
        });
    } catch (error) {
        return 'Recently';
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
            is_active: true,
            updated_at: new Date().toISOString()
        },
        {
            id: 2,
            name: 'Sarah Johnson',
            position: 'Product Manager',
            image: null,
            content: 'Professional and timely delivery. Highly recommended for web development projects.',
            rating: 4,
            order: 2,
            is_active: true,
            updated_at: new Date().toISOString()
        },
        {
            id: 3,
            name: 'Mike Wilson',
            position: 'Marketing Director',
            image: null,
            content: 'Great attention to detail and creative solutions. Very pleased with the results.',
            rating: 5,
            order: 3,
            is_active: false,
            updated_at: new Date().toISOString()
        }
    ];
};
</script>

<style scoped>
/* Smooth transitions for all elements */
* {
    transition-property: background-color, border-color, color, fill, stroke, transform, opacity, box-shadow;
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
        transform: translateX(100%) translateY(-20px);
        opacity: 0;
    }
    to {
        transform: translateX(0) translateY(0);
        opacity: 1;
    }
}

/* Loading spinner */
.animate-spin {
    animation: spin 1s linear infinite;
}

@keyframes spin {
    from {
        transform: rotate(0deg);
    }
    to {
        transform: rotate(360deg);
    }
}

/* Quote marks animation */
.text-4xl {
    opacity: 0.3;
    transition: opacity 0.3s ease;
}

.hover\:shadow-xl:hover .text-4xl {
    opacity: 0.5;
}

/* Rating badge pulse animation */
.bg-yellow-100 {
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% {
        box-shadow: 0 0 0 0 rgba(245, 158, 11, 0.7);
    }
    70% {
        box-shadow: 0 0 0 10px rgba(245, 158, 11, 0);
    }
    100% {
        box-shadow: 0 0 0 0 rgba(245, 158, 11, 0);
    }
}

/* Responsive adjustments */
@media (max-width: 640px) {
    .grid-cols-4 {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
}

@media (max-width: 480px) {
    .grid-cols-4 {
        grid-template-columns: 1fr;
    }
}

/* Button focus states */
button:focus {
    outline: none;
    ring-width: 2px;
    ring-offset-width: 2px;
}
</style>