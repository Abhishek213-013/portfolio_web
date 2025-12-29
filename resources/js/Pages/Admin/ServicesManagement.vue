<template>
    <AdminLayout>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 space-y-4 sm:space-y-0">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Services Management</h2>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Manage services you offer to clients</p>
                </div>
                <button 
                    @click="showForm = !showForm" 
                    class="px-4 py-2 bg-indigo-600 dark:bg-indigo-500 text-white rounded-lg hover:bg-indigo-700 dark:hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900 transition-colors"
                >
                    {{ showForm ? 'Cancel' : 'Add New Service' }}
                </button>
            </div>

            <!-- Add/Edit Service Form -->
            <div v-if="showForm" class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-4 sm:p-6 mb-8 border border-gray-200 dark:border-gray-700">
                <form @submit.prevent="saveService" class="space-y-6">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Service Title</label>
                            <input 
                                v-model="form.title" 
                                type="text" 
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400"
                                required
                                placeholder="Enter service name"
                            />
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Icon Class</label>
                            <input 
                                v-model="form.icon" 
                                type="text" 
                                placeholder="e.g., bi bi-code or fas fa-code"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400"
                                required
                            />
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                Use Bootstrap Icons (bi) or Font Awesome (fas) class names
                            </p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Theme Color</label>
                            <div class="flex flex-wrap gap-2 mt-2">
                                <button 
                                    v-for="colorOption in colorOptions" 
                                    :key="colorOption.value"
                                    type="button"
                                    @click="form.color = colorOption.value"
                                    :class="[
                                        'px-3 py-2 text-sm font-medium rounded-lg transition-all',
                                        form.color === colorOption.value 
                                            ? colorOption.activeClass 
                                            : 'bg-gray-100 text-gray-800 hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600'
                                    ]"
                                >
                                    <div class="flex items-center space-x-2">
                                        <div :class="['w-4 h-4 rounded-full', colorOption.bgClass]"></div>
                                        <span>{{ colorOption.label }}</span>
                                    </div>
                                </button>
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

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Service Description</label>
                        <textarea 
                            v-model="form.description" 
                            rows="4"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400"
                            required
                            placeholder="Describe what this service includes and benefits for clients..."
                        ></textarea>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Brief description of the service you offer</p>
                    </div>

                    <div class="bg-gray-50 dark:bg-gray-900 p-4 rounded-lg">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Custom SVG Background (Optional)</label>
                        <textarea 
                            v-model="form.svg_path" 
                            rows="3"
                            placeholder="Enter SVG path data for custom background"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:border-transparent bg-white dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 font-mono text-sm"
                        ></textarea>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                            Leave empty for default background. Example: M 0,0 L 100,0 L 100,100 L 0,100 Z
                        </p>
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
                            <span v-else>{{ editingId ? 'Update Service' : 'Add Service' }}</span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Loading State -->
            <div v-if="initialLoading && services.length === 0" class="text-center py-12">
                <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-600 dark:border-indigo-400"></div>
                <p class="mt-4 text-sm text-gray-600 dark:text-gray-400">Loading services...</p>
            </div>

            <!-- Services Stats -->
            <div v-if="!initialLoading" class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center">
                        <div class="h-10 w-10 bg-indigo-100 dark:bg-indigo-900 rounded-lg flex items-center justify-center mr-4">
                            <CogIcon class="h-5 w-5 text-indigo-600 dark:text-indigo-400" />
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Services</p>
                            <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ services.length }}</p>
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
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Active Services</p>
                            <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ activeServicesCount }}</p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center">
                        <div class="h-10 w-10 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center mr-4">
                            <svg class="h-5 w-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Top Color</p>
                            <p class="text-2xl font-semibold text-gray-900 dark:text-white capitalize">{{ mostCommonColor }}</p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center">
                        <div class="h-10 w-10 bg-purple-100 dark:bg-purple-900 rounded-lg flex items-center justify-center mr-4">
                            <svg class="h-5 w-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Custom SVG</p>
                            <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ svgServicesCount }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Services Grid -->
            <div v-if="!initialLoading" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div 
                    v-for="service in sortedServices" 
                    :key="service.id"
                    class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300 border border-gray-200 dark:border-gray-700 group"
                >
                    <!-- Service Header -->
                    <div :class="[
                        'relative overflow-hidden p-8',
                        getColorClasses(service.color).bg
                    ]">
                        <div class="absolute top-4 right-4 flex space-x-1">
                            <button 
                                @click="editService(service)"
                                class="p-2 bg-white dark:bg-gray-900 rounded-full shadow-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors"
                                title="Edit service"
                            >
                                <PencilIcon class="h-4 w-4 text-blue-600 dark:text-blue-400" />
                            </button>
                            <button 
                                @click="deleteService(service.id)"
                                class="p-2 bg-white dark:bg-gray-900 rounded-full shadow-lg hover:bg-red-50 dark:hover:bg-red-900/30 transition-colors"
                                title="Delete service"
                            >
                                <TrashIcon class="h-4 w-4 text-red-600 dark:text-red-400" />
                            </button>
                        </div>
                        
                        <div class="relative z-10">
                            <div class="flex items-start justify-between">
                                <div>
                                    <div :class="[
                                        'text-4xl mb-4',
                                        getColorClasses(service.color).text
                                    ]">
                                        <i :class="service.icon" class="transition-transform group-hover:scale-110 duration-300"></i>
                                    </div>
                                    <h4 class="text-xl font-bold text-gray-900 dark:text-white mb-2 group-hover:text-gray-800 dark:group-hover:text-gray-100 transition-colors">
                                        {{ service.title }}
                                    </h4>
                                </div>
                                <span class="text-xs px-2 py-1 rounded-full bg-white/20 dark:bg-black/20 text-gray-900 dark:text-white">
                                    #{{ service.order }}
                                </span>
                            </div>
                            
                            <p class="text-gray-700 dark:text-gray-300 mt-4">{{ service.description }}</p>
                        </div>
                        
                        <!-- SVG Background -->
                        <div v-if="service.svg_path" class="absolute inset-0 opacity-10">
                            <svg width="100%" height="100%" viewBox="0 0 600 600" xmlns="http://www.w3.org/2000/svg">
                                <path :d="service.svg_path" :fill="getColorHex(service.color)" />
                            </svg>
                        </div>
                        <div v-else class="absolute inset-0 opacity-5">
                            <div class="absolute -right-4 -bottom-4 h-32 w-32 rounded-full" :class="getColorClasses(service.color).gradient"></div>
                        </div>
                    </div>
                    
                    <!-- Service Footer -->
                    <div class="p-6 border-t border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900">
                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center space-y-3 sm:space-y-0">
                            <div class="flex items-center space-x-3">
                                <span :class="[
                                    'px-3 py-1 text-xs font-medium rounded-full',
                                    service.is_active 
                                        ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' 
                                        : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200'
                                ]">
                                    <span class="w-2 h-2 rounded-full inline-block mr-1" 
                                          :class="service.is_active ? 'bg-green-500' : 'bg-red-500'"></span>
                                    {{ service.is_active ? 'Active' : 'Inactive' }}
                                </span>
                                <span :class="[
                                    'px-3 py-1 text-xs font-medium rounded-full',
                                    getColorClasses(service.color).badge
                                ]">
                                    {{ service.color }}
                                </span>
                                <span v-if="service.svg_path" class="px-2 py-1 text-xs bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400 rounded-full">
                                    SVG
                                </span>
                            </div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                <span class="inline-flex items-center">
                                    <svg class="h-3 w-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                                    </svg>
                                    Order: {{ service.order }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Empty State -->
            <div v-if="!initialLoading && sortedServices.length === 0" 
                 class="text-center bg-white dark:bg-gray-800 rounded-lg shadow-lg p-12 border border-gray-200 dark:border-gray-700">
                <div class="max-w-md mx-auto">
                    <div class="h-16 w-16 bg-indigo-100 dark:bg-indigo-900 rounded-full flex items-center justify-center mx-auto mb-4">
                        <CogIcon class="h-8 w-8 text-indigo-600 dark:text-indigo-400" />
                    </div>
                    <h3 class="text-xl font-medium text-gray-900 dark:text-white mb-2">No Services Added</h3>
                    <p class="text-gray-600 dark:text-gray-400 mb-6">
                        Add services to showcase what you offer to clients. Each service can have a unique icon, color, and description.
                    </p>
                    <button 
                        @click="showForm = true" 
                        class="px-6 py-3 bg-indigo-600 dark:bg-indigo-500 text-white font-medium rounded-lg hover:bg-indigo-700 dark:hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition-colors"
                    >
                        Add Your First Service
                    </button>
                </div>
            </div>

            <!-- Color Legend -->
            <div v-if="!initialLoading" class="mt-8 bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Color Theme Guide</h3>
                    <span class="text-sm text-gray-500 dark:text-gray-400">{{ services.length }} services</span>
                </div>
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                    <div v-for="colorOption in colorOptions" :key="colorOption.value" 
                         class="flex items-center justify-between p-3 rounded-lg bg-gray-50 dark:bg-gray-900 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                        <div class="flex items-center space-x-3">
                            <div :class="['h-8 w-8 rounded-lg flex items-center justify-center', colorOption.bgClass]">
                                <svg class="h-4 w-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div>
                                <span class="text-sm font-medium text-gray-900 dark:text-white capitalize">{{ colorOption.label }}</span>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ getColorCount(colorOption.value) }} services</p>
                            </div>
                        </div>
                        <span class="text-sm font-medium" :class="colorOption.textClass">
                            {{ Math.round((getColorCount(colorOption.value) / services.length) * 100) || 0 }}%
                        </span>
                    </div>
                </div>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-4">
                    Each color represents a different service category. Click on color buttons in the form to select.
                </p>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { ref, reactive, computed, onMounted } from 'vue';
import { router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { PencilIcon, TrashIcon, CogIcon } from '@heroicons/vue/24/outline';

const showForm = ref(false);
const loading = ref(false);
const initialLoading = ref(true);
const editingId = ref(null);
const services = ref([]);
const form = reactive({
    title: '',
    icon: '',
    color: 'cyan',
    description: '',
    svg_path: '',
    order: 0,
    is_active: true
});

const colorOptions = [
    { 
        label: 'Cyan', 
        value: 'cyan', 
        activeClass: 'bg-cyan-600 text-white dark:bg-cyan-500 dark:text-white',
        bgClass: 'bg-cyan-500',
        textClass: 'text-cyan-600 dark:text-cyan-400'
    },
    { 
        label: 'Orange', 
        value: 'orange', 
        activeClass: 'bg-orange-600 text-white dark:bg-orange-500 dark:text-white',
        bgClass: 'bg-orange-500',
        textClass: 'text-orange-600 dark:text-orange-400'
    },
    { 
        label: 'Teal', 
        value: 'teal', 
        activeClass: 'bg-teal-600 text-white dark:bg-teal-500 dark:text-white',
        bgClass: 'bg-teal-500',
        textClass: 'text-teal-600 dark:text-teal-400'
    },
    { 
        label: 'Red', 
        value: 'red', 
        activeClass: 'bg-red-600 text-white dark:bg-red-500 dark:text-white',
        bgClass: 'bg-red-500',
        textClass: 'text-red-600 dark:text-red-400'
    },
    { 
        label: 'Indigo', 
        value: 'indigo', 
        activeClass: 'bg-indigo-600 text-white dark:bg-indigo-500 dark:text-white',
        bgClass: 'bg-indigo-500',
        textClass: 'text-indigo-600 dark:text-indigo-400'
    },
    { 
        label: 'Pink', 
        value: 'pink', 
        activeClass: 'bg-pink-600 text-white dark:bg-pink-500 dark:text-white',
        bgClass: 'bg-pink-500',
        textClass: 'text-pink-600 dark:text-pink-400'
    },
    { 
        label: 'Purple', 
        value: 'purple', 
        activeClass: 'bg-purple-600 text-white dark:bg-purple-500 dark:text-white',
        bgClass: 'bg-purple-500',
        textClass: 'text-purple-600 dark:text-purple-400'
    },
    { 
        label: 'Green', 
        value: 'green', 
        activeClass: 'bg-green-600 text-white dark:bg-green-500 dark:text-white',
        bgClass: 'bg-green-500',
        textClass: 'text-green-600 dark:text-green-400'
    },
];

// Safeguard computed properties with fallbacks
const sortedServices = computed(() => {
    if (!Array.isArray(services.value)) return [];
    return services.value
        .filter(service => service && typeof service === 'object')
        .sort((a, b) => (a.order || 0) - (b.order || 0));
});

const activeServicesCount = computed(() => {
    if (!Array.isArray(services.value)) return 0;
    return services.value.filter(service => service && service.is_active).length;
});

const svgServicesCount = computed(() => {
    if (!Array.isArray(services.value)) return 0;
    return services.value.filter(service => service && service.svg_path && service.svg_path.trim() !== '').length;
});

const mostCommonColor = computed(() => {
    if (!Array.isArray(services.value)) return 'cyan';
    
    const colorCounts = {};
    services.value.forEach(service => {
        if (service && service.color) {
            colorCounts[service.color] = (colorCounts[service.color] || 0) + 1;
        }
    });
    
    let mostCommon = 'cyan';
    let highestCount = 0;
    
    Object.entries(colorCounts).forEach(([color, count]) => {
        if (count > highestCount) {
            highestCount = count;
            mostCommon = color;
        }
    });
    
    return mostCommon;
});

onMounted(() => {
    fetchServices();
});

const fetchServices = async () => {
    try {
        initialLoading.value = true;
        const response = await axios.get('/api/services');
        
        // Handle different response formats
        let servicesData = [];
        
        if (Array.isArray(response.data)) {
            servicesData = response.data;
        } else if (response.data && Array.isArray(response.data.data)) {
            servicesData = response.data.data;
        } else if (response.data && typeof response.data === 'object') {
            // Convert object values to array if needed
            servicesData = Object.values(response.data);
        }
        
        // Ensure all services have required properties
        servicesData = servicesData.map(service => ({
            id: service.id || 0,
            title: service.title || '',
            icon: service.icon || 'bi bi-question-circle',
            color: service.color || 'cyan',
            description: service.description || '',
            svg_path: service.svg_path || '',
            order: service.order || 0,
            is_active: service.is_active !== undefined ? service.is_active : true
        }));
        
        services.value = servicesData;
        
        console.log('Fetched services:', services.value);
        
    } catch (error) {
        console.error('Error fetching services:', error);
        services.value = [];
        
        // Show error notification
        showNotification('Failed to load services. Please try again.', 'error');
    } finally {
        initialLoading.value = false;
    }
};

const getColorClasses = (color) => {
    const colorMap = {
        cyan: {
            bg: 'bg-cyan-50 dark:bg-cyan-900/20',
            text: 'text-cyan-600 dark:text-cyan-400',
            badge: 'bg-cyan-100 text-cyan-800 dark:bg-cyan-900 dark:text-cyan-200',
            gradient: 'bg-gradient-to-br from-cyan-400 to-cyan-600'
        },
        orange: {
            bg: 'bg-orange-50 dark:bg-orange-900/20',
            text: 'text-orange-600 dark:text-orange-400',
            badge: 'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200',
            gradient: 'bg-gradient-to-br from-orange-400 to-orange-600'
        },
        teal: {
            bg: 'bg-teal-50 dark:bg-teal-900/20',
            text: 'text-teal-600 dark:text-teal-400',
            badge: 'bg-teal-100 text-teal-800 dark:bg-teal-900 dark:text-teal-200',
            gradient: 'bg-gradient-to-br from-teal-400 to-teal-600'
        },
        red: {
            bg: 'bg-red-50 dark:bg-red-900/20',
            text: 'text-red-600 dark:text-red-400',
            badge: 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
            gradient: 'bg-gradient-to-br from-red-400 to-red-600'
        },
        indigo: {
            bg: 'bg-indigo-50 dark:bg-indigo-900/20',
            text: 'text-indigo-600 dark:text-indigo-400',
            badge: 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200',
            gradient: 'bg-gradient-to-br from-indigo-400 to-indigo-600'
        },
        pink: {
            bg: 'bg-pink-50 dark:bg-pink-900/20',
            text: 'text-pink-600 dark:text-pink-400',
            badge: 'bg-pink-100 text-pink-800 dark:bg-pink-900 dark:text-pink-200',
            gradient: 'bg-gradient-to-br from-pink-400 to-pink-600'
        },
        purple: {
            bg: 'bg-purple-50 dark:bg-purple-900/20',
            text: 'text-purple-600 dark:text-purple-400',
            badge: 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200',
            gradient: 'bg-gradient-to-br from-purple-400 to-purple-600'
        },
        green: {
            bg: 'bg-green-50 dark:bg-green-900/20',
            text: 'text-green-600 dark:text-green-400',
            badge: 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
            gradient: 'bg-gradient-to-br from-green-400 to-green-600'
        }
    };
    
    return colorMap[color] || colorMap.cyan;
};

const getColorHex = (color) => {
    const colorMap = {
        cyan: '#06b6d4',
        orange: '#f97316',
        teal: '#14b8a6',
        red: '#ef4444',
        indigo: '#6366f1',
        pink: '#ec4899',
        purple: '#8b5cf6',
        green: '#10b981'
    };
    return colorMap[color] || '#06b6d4';
};

const getColorCount = (color) => {
    if (!Array.isArray(services.value)) return 0;
    return services.value.filter(service => service && service.color === color).length;
};

const editService = (service) => {
    editingId.value = service.id;
    Object.assign(form, {
        title: service.title,
        icon: service.icon,
        color: service.color,
        description: service.description,
        svg_path: service.svg_path,
        order: service.order,
        is_active: service.is_active
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
        icon: '',
        color: 'cyan',
        description: '',
        svg_path: '',
        order: 0,
        is_active: true
    });
};

const saveService = async () => {
    loading.value = true;
    
    try {
        let response;
        if (editingId.value) {
            response = await axios.put(`/api/services/${editingId.value}`, form);
        } else {
            response = await axios.post('/api/services', form);
        }

        if (response.status === 200 || response.status === 201) {
            await fetchServices();
            cancelEdit();
            
            // Show success notification
            showNotification(
                editingId.value 
                    ? 'Service updated successfully!' 
                    : 'Service added successfully!',
                'success'
            );
        }
    } catch (error) {
        console.error('Error saving service:', error);
        
        // Show error message
        const errorMessage = error.response?.data?.message || 
                           (error.response?.data?.errors ? 
                            Object.values(error.response.data.errors).flat().join(', ') : 
                            'Failed to save service. Please try again.');
        
        showNotification(errorMessage, 'error');
    } finally {
        loading.value = false;
    }
};

const deleteService = async (id) => {
    if (!confirm('Are you sure you want to delete this service? This action cannot be undone.')) return;
    
    try {
        await axios.delete(`/api/services/${id}`);
        await fetchServices();
        
        // Show success notification
        showNotification('Service deleted successfully!', 'success');
        
    } catch (error) {
        console.error('Error deleting service:', error);
        
        // Show error message
        const errorMessage = error.response?.data?.message || 'Failed to delete service. Please try again.';
        showNotification(errorMessage, 'error');
    }
};

// Notification helper function
const showNotification = (message, type = 'info') => {
    // Remove any existing notifications
    const existingNotifications = document.querySelectorAll('.custom-notification');
    existingNotifications.forEach(notification => {
        if (notification.parentNode) {
            notification.parentNode.removeChild(notification);
        }
    });
    
    // Create a toast notification element
    const toast = document.createElement('div');
    toast.className = `custom-notification fixed top-4 right-4 z-50 px-6 py-4 rounded-lg shadow-lg transform transition-all duration-300 translate-x-0 opacity-100 ${
        type === 'success' 
            ? 'bg-green-500 text-white' 
            : type === 'error' 
            ? 'bg-red-500 text-white' 
            : 'bg-blue-500 text-white'
    }`;
    
    // Add icon based on type
    let icon = 'ℹ';
    if (type === 'success') icon = '✓';
    if (type === 'error') icon = '✗';
    
    toast.innerHTML = `
        <div class="flex items-center">
            <span class="font-bold mr-2">${icon}</span>
            <span class="text-sm font-medium">${message}</span>
        </div>
    `;
    
    // Add to document
    document.body.appendChild(toast);
    
    // Remove after 5 seconds
    setTimeout(() => {
        toast.style.transform = 'translateX(100%)';
        toast.style.opacity = '0';
        setTimeout(() => {
            if (toast.parentNode) {
                toast.parentNode.removeChild(toast);
            }
        }, 300);
    }, 5000);
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

/* Icon hover effects */
.group:hover .group-hover\:scale-110 {
    transform: scale(1.1);
}

/* Responsive adjustments */
@media (max-width: 640px) {
    .grid-cols-4 {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
    
    .grid-cols-3 {
        grid-template-columns: 1fr;
    }
    
    .grid-cols-6 {
        grid-template-columns: repeat(3, minmax(0, 1fr));
    }
}

@media (max-width: 480px) {
    .grid-cols-4 {
        grid-template-columns: 1fr;
    }
    
    .grid-cols-6 {
        grid-template-columns: repeat(2, minmax(0, 1fr));
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

/* SVG background styling */
svg path {
    transition: fill 200ms ease;
}

/* Gradient animation */
.bg-gradient-to-br {
    animation: gradientShift 10s ease infinite;
    background-size: 200% 200%;
}

@keyframes gradientShift {
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

/* Color badge animation */
@keyframes pulse {
    0% {
        box-shadow: 0 0 0 0 rgba(6, 182, 212, 0.7);
    }
    70% {
        box-shadow: 0 0 0 10px rgba(6, 182, 212, 0);
    }
    100% {
        box-shadow: 0 0 0 0 rgba(6, 182, 212, 0);
    }
}

.rounded-full:has(span.bg-green-500),
.rounded-full:has(span.bg-red-500) {
    animation: pulse 2s infinite;
}

/* Icon font sizing */
i[class^="bi-"],
i[class^="fas-"],
i[class^="fab-"] {
    font-size: 2.5rem;
    line-height: 1;
}

/* Notification styles */
.custom-notification {
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