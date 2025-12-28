<template>
    <AdminLayout>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 space-y-4 sm:space-y-0">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Portfolio Management</h2>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Showcase your projects and work</p>
                </div>
                <button 
                    @click="showForm = !showForm" 
                    class="px-4 py-2 bg-indigo-600 dark:bg-indigo-500 text-white rounded-lg hover:bg-indigo-700 dark:hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900 transition-colors"
                >
                    {{ showForm ? 'Cancel' : 'Add New Portfolio Item' }}
                </button>
            </div>

            <!-- Add/Edit Portfolio Form -->
            <div v-if="showForm" class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-4 sm:p-6 mb-8 border border-gray-200 dark:border-gray-700">
                <form @submit.prevent="savePortfolioItem" class="space-y-6">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Project Title</label>
                            <input 
                                v-model="form.title" 
                                type="text" 
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400"
                                required
                                placeholder="Enter project title"
                            />
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Category</label>
                            <select 
                                v-model="form.category" 
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                            >
                                <option value="app" class="text-gray-900 dark:text-white">Web Application</option>
                                <option value="mobile" class="text-gray-900 dark:text-white">Mobile App</option>
                                <option value="website" class="text-gray-900 dark:text-white">Website</option>
                                <option value="design" class="text-gray-900 dark:text-white">UI/UX Design</option>
                                <option value="branding" class="text-gray-900 dark:text-white">Branding</option>
                                <option value="graphic" class="text-gray-900 dark:text-white">Graphic Design</option>
                                <option value="ecommerce" class="text-gray-900 dark:text-white">E-commerce</option>
                                <option value="other" class="text-gray-900 dark:text-white">Other</option>
                            </select>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Choose the project category</p>
                        </div>
                    </div>

                    <div class="bg-gray-50 dark:bg-gray-900 p-4 rounded-lg">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Project Image</label>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-3">Upload a high-quality image that represents your project (Recommended: 800x600px)</p>
                        
                        <div class="mt-1 flex flex-col sm:flex-row items-start sm:items-center space-y-4 sm:space-y-0">
                            <input 
                                type="file" 
                                @change="handleImageUpload"
                                accept="image/*"
                                class="w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 dark:file:bg-gray-700 dark:file:text-gray-300"
                            />
                        </div>
                        
                        <div v-if="form.image" class="mt-4">
                            <div class="relative">
                                <img :src="form.image instanceof File ? URL.createObjectURL(form.image) : form.image" 
                                     class="h-64 w-full object-cover rounded-lg border-2 border-gray-300 dark:border-gray-600 shadow-md">
                                <div class="absolute top-2 right-2">
                                    <span class="px-3 py-1 text-xs font-medium rounded-full bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200">
                                        {{ getCategoryLabel(form.category) }}
                                    </span>
                                </div>
                            </div>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">Image preview</p>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Project Description</label>
                        <textarea 
                            v-model="form.description" 
                            rows="3"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400"
                            required
                            placeholder="Describe the project, technologies used, and your role..."
                        ></textarea>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Brief description of the project and your contribution</p>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Project URL (Optional)</label>
                            <input 
                                v-model="form.details_url" 
                                type="url" 
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400"
                                placeholder="https://example.com"
                            />
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Link to live project or case study</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Display Order</label>
                            <input 
                                v-model="form.order" 
                                type="number" 
                                min="0"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                            />
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Lower numbers appear first in the portfolio</p>
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
                            Active (Show in portfolio)
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
                            <span v-else>{{ editingId ? 'Update Portfolio Item' : 'Add Portfolio Item' }}</span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Loading State -->
            <div v-if="initialLoading && portfolioItems.length === 0" class="text-center py-12">
                <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-600 dark:border-indigo-400"></div>
                <p class="mt-4 text-sm text-gray-600 dark:text-gray-400">Loading portfolio items...</p>
            </div>

            <!-- Portfolio Stats -->
            <div v-if="!initialLoading" class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center">
                        <div class="h-10 w-10 bg-indigo-100 dark:bg-indigo-900 rounded-lg flex items-center justify-center mr-4">
                            <PhotoIcon class="h-5 w-5 text-indigo-600 dark:text-indigo-400" />
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Projects</p>
                            <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ portfolioItems.length }}</p>
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
                            <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ activeItemsCount }}</p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center">
                        <div class="h-10 w-10 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center mr-4">
                            <svg class="h-5 w-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Web Apps</p>
                            <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ getCategoryCount('app') + getCategoryCount('website') + getCategoryCount('ecommerce') }}</p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center">
                        <div class="h-10 w-10 bg-purple-100 dark:bg-purple-900 rounded-lg flex items-center justify-center mr-4">
                            <svg class="h-5 w-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Design</p>
                            <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ getCategoryCount('design') + getCategoryCount('branding') + getCategoryCount('graphic') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Category Filter -->
            <div v-if="!initialLoading" class="mb-6">
                <div class="flex flex-wrap gap-2">
                    <button 
                        v-for="category in categories" 
                        :key="category.value"
                        @click="activeCategory = activeCategory === category.value ? null : category.value"
                        :class="[
                            'px-4 py-2 text-sm font-medium rounded-lg transition-colors',
                            activeCategory === category.value
                                ? category.activeClass
                                : 'bg-gray-100 text-gray-800 hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600'
                        ]"
                    >
                        {{ category.label }}
                        <span class="ml-1 px-1.5 py-0.5 text-xs rounded-full bg-white/30 dark:bg-gray-800/30">
                            {{ getCategoryCount(category.value) }}
                        </span>
                    </button>
                </div>
            </div>

            <!-- Portfolio Items Grid -->
            <div v-if="!initialLoading" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div 
                    v-for="item in filteredPortfolioItems" 
                    :key="item.id"
                    class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300 border border-gray-200 dark:border-gray-700 group"
                >
                    <div class="relative overflow-hidden">
                        <img 
                            :src="item.image" 
                            :alt="item.title" 
                            class="w-full h-56 object-cover group-hover:scale-105 transition-transform duration-500"
                        />
                        <div class="absolute top-2 right-2 flex space-x-1">
                            <button 
                                @click="editPortfolioItem(item)"
                                class="p-2 bg-white dark:bg-gray-900 rounded-full shadow-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors"
                                title="Edit"
                            >
                                <PencilIcon class="h-4 w-4 text-blue-600 dark:text-blue-400" />
                            </button>
                            <button 
                                @click="deletePortfolioItem(item.id)"
                                class="p-2 bg-white dark:bg-gray-900 rounded-full shadow-lg hover:bg-red-50 dark:hover:bg-red-900/30 transition-colors"
                                title="Delete"
                            >
                                <TrashIcon class="h-4 w-4 text-red-600 dark:text-red-400" />
                            </button>
                        </div>
                        <div class="absolute bottom-2 left-2">
                            <span :class="[
                                'px-3 py-1 text-xs font-medium rounded-full',
                                getCategoryClass(item.category)
                            ]">
                                {{ getCategoryLabel(item.category) }}
                            </span>
                        </div>
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        <div class="absolute bottom-0 left-0 right-0 p-4 transform translate-y-full group-hover:translate-y-0 transition-transform duration-300">
                            <div class="flex justify-between items-center">
                                <button 
                                    v-if="item.details_url"
                                    @click.stop="window.open(item.details_url, '_blank')"
                                    class="px-3 py-1.5 text-xs font-medium bg-white dark:bg-gray-900 text-gray-900 dark:text-white rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors"
                                >
                                    View Project →
                                </button>
                                <span :class="[
                                    'px-2 py-1 text-xs font-medium rounded-full',
                                    item.is_active 
                                        ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' 
                                        : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200'
                                ]">
                                    {{ item.is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        <h4 class="text-lg font-medium text-gray-900 dark:text-white mb-2 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">
                            {{ item.title }}
                        </h4>
                        <p class="text-gray-600 dark:text-gray-400 text-sm mb-4 line-clamp-2">{{ item.description }}</p>
                        <div class="flex justify-between items-center text-sm">
                            <div class="flex items-center text-gray-500 dark:text-gray-400">
                                <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                {{ formatDate(item.updated_at) }}
                            </div>
                            <span class="text-xs text-gray-500 dark:text-gray-400">Order: {{ item.order }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Empty State -->
            <div v-if="!initialLoading && filteredPortfolioItems.length === 0" 
                 class="text-center bg-white dark:bg-gray-800 rounded-lg shadow-lg p-12 border border-gray-200 dark:border-gray-700">
                <div class="max-w-md mx-auto">
                    <div class="h-16 w-16 bg-indigo-100 dark:bg-indigo-900 rounded-full flex items-center justify-center mx-auto mb-4">
                        <PhotoIcon class="h-8 w-8 text-indigo-600 dark:text-indigo-400" />
                    </div>
                    <h3 class="text-xl font-medium text-gray-900 dark:text-white mb-2">
                        {{ activeCategory ? `No ${getCategoryLabel(activeCategory)} Projects` : 'No Portfolio Items' }}
                    </h3>
                    <p class="text-gray-600 dark:text-gray-400 mb-6">
                        {{ activeCategory 
                            ? `Start by adding your first ${getCategoryLabel(activeCategory).toLowerCase()} project.` 
                            : 'Add your first portfolio item to showcase your work and attract clients.'
                        }}
                    </p>
                    <button 
                        @click="showForm = true" 
                        class="px-6 py-3 bg-indigo-600 dark:bg-indigo-500 text-white font-medium rounded-lg hover:bg-indigo-700 dark:hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition-colors"
                    >
                        Add Portfolio Item
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
import { PencilIcon, TrashIcon, PhotoIcon } from '@heroicons/vue/24/outline';

const showForm = ref(false);
const loading = ref(false);
const initialLoading = ref(true);
const editingId = ref(null);
const activeCategory = ref(null);
const portfolioItems = ref([]);
const form = reactive({
    title: '',
    category: 'app',
    image: null,
    description: '',
    details_url: '',
    order: 0,
    is_active: true
});

const categories = [
    { label: 'All Projects', value: null, activeClass: 'bg-indigo-600 text-white dark:bg-indigo-500 dark:text-white' },
    { label: 'Web Apps', value: 'app', activeClass: 'bg-blue-600 text-white dark:bg-blue-500 dark:text-white' },
    { label: 'Mobile Apps', value: 'mobile', activeClass: 'bg-green-600 text-white dark:bg-green-500 dark:text-white' },
    { label: 'Websites', value: 'website', activeClass: 'bg-teal-600 text-white dark:bg-teal-500 dark:text-white' },
    { label: 'UI/UX Design', value: 'design', activeClass: 'bg-purple-600 text-white dark:bg-purple-500 dark:text-white' },
    { label: 'Branding', value: 'branding', activeClass: 'bg-pink-600 text-white dark:bg-pink-500 dark:text-white' },
    { label: 'Graphic Design', value: 'graphic', activeClass: 'bg-yellow-600 text-white dark:bg-yellow-500 dark:text-white' },
    { label: 'E-commerce', value: 'ecommerce', activeClass: 'bg-orange-600 text-white dark:bg-orange-500 dark:text-white' },
    { label: 'Other', value: 'other', activeClass: 'bg-gray-600 text-white dark:bg-gray-500 dark:text-white' },
];

// Safeguard computed properties with fallbacks
const filteredPortfolioItems = computed(() => {
    if (!Array.isArray(portfolioItems.value)) return [];
    
    let items = portfolioItems.value;
    if (activeCategory.value) {
        items = items.filter(item => item && item.category === activeCategory.value);
    }
    return items.sort((a, b) => (a.order || 0) - (b.order || 0));
});

const activeItemsCount = computed(() => {
    if (!Array.isArray(portfolioItems.value)) return 0;
    return portfolioItems.value.filter(item => item && item.is_active).length;
});

onMounted(() => {
    fetchPortfolioItems();
});

const fetchPortfolioItems = async () => {
    try {
        initialLoading.value = true;
        const response = await axios.get('/api/portfolio-items');
        
        // Handle different response formats
        let portfolioData = [];
        
        if (Array.isArray(response.data)) {
            portfolioData = response.data;
        } else if (response.data && Array.isArray(response.data.data)) {
            portfolioData = response.data.data;
        } else if (response.data && typeof response.data === 'object') {
            // Convert object values to array if needed
            portfolioData = Object.values(response.data);
        }
        
        // Ensure all portfolio items have required properties
        portfolioData = portfolioData.map(item => ({
            id: item.id || 0,
            title: item.title || '',
            category: item.category || 'app',
            image: item.image || '/images/default-project.jpg',
            description: item.description || '',
            details_url: item.details_url || '',
            order: item.order || 0,
            is_active: item.is_active !== undefined ? item.is_active : true,
            updated_at: item.updated_at || new Date().toISOString()
        }));
        
        portfolioItems.value = portfolioData;
        
        console.log('Fetched portfolio items:', portfolioItems.value);
        
    } catch (error) {
        console.error('Error fetching portfolio items:', error);
        portfolioItems.value = [];
        
        // Show error to user
        if (router.page.props && router.page.props.flash) {
            router.page.props.flash.error = 'Failed to load portfolio items. Please try again.';
        }
    } finally {
        initialLoading.value = false;
    }
};

const getCategoryLabel = (category) => {
    const cat = categories.find(c => c.value === category);
    return cat ? cat.label : category.charAt(0).toUpperCase() + category.slice(1);
};

const getCategoryClass = (category) => {
    const cat = categories.find(c => c.value === category);
    if (!cat) return 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300';
    
    return cat.activeClass;
};

const getCategoryCount = (category) => {
    if (!Array.isArray(portfolioItems.value)) return 0;
    
    if (category === 'app' || category === 'website' || category === 'ecommerce') {
        return portfolioItems.value.filter(item => item && ['app', 'website', 'ecommerce'].includes(item.category)).length;
    }
    if (category === 'design' || category === 'branding' || category === 'graphic') {
        return portfolioItems.value.filter(item => item && ['design', 'branding', 'graphic'].includes(item.category)).length;
    }
    
    if (category === null) {
        return portfolioItems.value.length;
    }
    
    return portfolioItems.value.filter(item => item && item.category === category).length;
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
        
        form.image = file;
    }
};

const editPortfolioItem = (item) => {
    editingId.value = item.id;
    Object.assign(form, {
        title: item.title,
        category: item.category,
        image: item.image,
        description: item.description,
        details_url: item.details_url,
        order: item.order,
        is_active: item.is_active
    });
    showForm.value = true;
    
    // Scroll to form
    setTimeout(() => {
        const formElement = document.querySelector('.bg-white\\ dark\\:bg-gray-800, .dark\\:bg-gray-800');
        if (formElement) {
            formElement.scrollIntoView({ 
                behavior: 'smooth', 
                block: 'start' 
            });
        }
    }, 100);
};

const cancelEdit = () => {
    editingId.value = null;
    resetForm();
    showForm.value = false;
};

const resetForm = () => {
    Object.assign(form, {
        title: '',
        category: 'app',
        image: null,
        description: '',
        details_url: '',
        order: 0,
        is_active: true
    });
};

const savePortfolioItem = async () => {
    loading.value = true;
    
    try {
        const formData = new FormData();
        formData.append('title', form.title.trim());
        formData.append('category', form.category);
        formData.append('description', form.description.trim());
        if (form.details_url) {
            formData.append('details_url', form.details_url.trim());
        }
        formData.append('order', form.order);
        formData.append('is_active', form.is_active);
        
        if (form.image instanceof File) {
            formData.append('image', form.image);
        } else if (form.image && typeof form.image === 'string') {
            // If it's a string (URL), we need to send it differently
            formData.append('image_url', form.image);
        }

        let response;
        if (editingId.value) {
            // Update existing
            response = await axios.post(`/api/portfolio-items/${editingId.value}`, formData, {
                headers: { 
                    'Content-Type': 'multipart/form-data',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                }
            });
        } else {
            // Create new
            response = await axios.post('/api/portfolio-items', formData, {
                headers: { 
                    'Content-Type': 'multipart/form-data',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                }
            });
        }

        if (response.status === 200 || response.status === 201) {
            await fetchPortfolioItems();
            cancelEdit();
            
            // Show success message
            router.visit(window.location.pathname, {
                preserveState: true,
                preserveScroll: true,
                only: [],
                onSuccess: () => {
                    if (router.page.props && router.page.props.flash) {
                        router.page.props.flash.success = editingId.value 
                            ? 'Portfolio item updated successfully!' 
                            : 'Portfolio item added successfully!';
                    }
                }
            });
        }
    } catch (error) {
        console.error('Error saving portfolio item:', error);
        
        // Show error message
        const errorMessage = error.response?.data?.message || 
                           (error.response?.data?.errors ? 
                            Object.values(error.response.data.errors).flat().join(', ') : 
                            'Failed to save portfolio item. Please try again.');
        
        alert(errorMessage);
    } finally {
        loading.value = false;
    }
};

const deletePortfolioItem = async (id) => {
    if (!confirm('Are you sure you want to delete this portfolio item? This action cannot be undone.')) return;
    
    try {
        await axios.delete(`/api/portfolio-items/${id}`);
        await fetchPortfolioItems();
        
        // Show success message
        router.visit(window.location.pathname, {
            preserveState: true,
            preserveScroll: true,
            only: [],
            onSuccess: () => {
                if (router.page.props && router.page.props.flash) {
                    router.page.props.flash.success = 'Portfolio item deleted successfully!';
                }
            }
        });
        
    } catch (error) {
        console.error('Error deleting portfolio item:', error);
        
        // Show error message
        const errorMessage = error.response?.data?.message || 'Failed to delete portfolio item. Please try again.';
        alert(errorMessage);
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

/* Line clamp for description */
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Image hover effects */
.group:hover .group-hover\:scale-105 {
    transform: scale(1.05);
}

/* Responsive adjustments */
@media (max-width: 640px) {
    .grid-cols-4 {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
    
    .grid-cols-3 {
        grid-template-columns: 1fr;
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

/* Card hover effects */
.hover\:shadow-xl:hover {
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
}

/* Image overlay gradient */
.bg-gradient-to-t {
    background-image: linear-gradient(to top, rgba(0, 0, 0, 0.6), transparent);
}

/* Category badge animation */
@keyframes pulse {
    0% {
        box-shadow: 0 0 0 0 rgba(99, 102, 241, 0.7);
    }
    70% {
        box-shadow: 0 0 0 10px rgba(99, 102, 241, 0);
    }
    100% {
        box-shadow: 0 0 0 0 rgba(99, 102, 241, 0);
    }
}

.rounded-full {
    animation: pulse 2s infinite;
}

/* File input styling */
input[type="file"]::-webkit-file-upload-button {
    cursor: pointer;
}

input[type="file"]::file-selector-button {
    cursor: pointer;
}
</style>