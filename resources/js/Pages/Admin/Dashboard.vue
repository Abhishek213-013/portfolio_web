<template>
    <AdminLayout>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <!-- Page Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Dashboard Overview</h1>
                <p class="mt-2 text-gray-600 dark:text-gray-400">Welcome to your portfolio administration dashboard</p>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Hero Sections Card -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 border border-gray-200 dark:border-gray-700 hover:shadow-xl transition-shadow duration-300">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="h-12 w-12 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center">
                                <PhotographIcon class="h-6 w-6 text-blue-600 dark:text-blue-400" />
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Hero Sections</p>
                            <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ stats.hero_sections || 0 }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Active on website</p>
                        </div>
                    </div>
                </div>

                <!-- Portfolio Items Card -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 border border-gray-200 dark:border-gray-700 hover:shadow-xl transition-shadow duration-300">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="h-12 w-12 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center">
                                <BriefcaseIcon class="h-6 w-6 text-green-600 dark:text-green-400" />
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Portfolio Items</p>
                            <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ stats.portfolio_items || 0 }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Total projects</p>
                        </div>
                    </div>
                </div>

                <!-- Testimonials Card -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 border border-gray-200 dark:border-gray-700 hover:shadow-xl transition-shadow duration-300">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="h-12 w-12 bg-yellow-100 dark:bg-yellow-900 rounded-lg flex items-center justify-center">
                                <ChatBubbleLeftRightIcon class="h-6 w-6 text-yellow-600 dark:text-yellow-400" />
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Testimonials</p>
                            <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ stats.testimonials || 0 }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Client reviews</p>
                        </div>
                    </div>
                </div>

                <!-- Services Card -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 border border-gray-200 dark:border-gray-700 hover:shadow-xl transition-shadow duration-300">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="h-12 w-12 bg-purple-100 dark:bg-purple-900 rounded-lg flex items-center justify-center">
                                <CogIcon class="h-6 w-6 text-purple-600 dark:text-purple-400" />
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Services</p>
                            <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ stats.services || 0 }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Offered services</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Content Section -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                <!-- Recent Portfolio Items -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden border border-gray-200 dark:border-gray-700">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Recent Portfolio Items</h3>
                            <Link :href="route('admin.portfolio')" class="text-sm text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300">
                                View All →
                            </Link>
                        </div>
                    </div>
                    <div class="p-6">
                        <div v-if="recentUpdates.portfolio && recentUpdates.portfolio.length > 0" class="space-y-4">
                            <div v-for="item in recentUpdates.portfolio" :key="item.id" 
                                 class="flex items-center space-x-4 p-3 hover:bg-gray-50 dark:hover:bg-gray-900 rounded-lg transition-colors">
                                <div class="flex-shrink-0">
                                    <div class="relative">
                                        <img :src="item.image" :alt="item.title" 
                                             class="h-16 w-16 object-cover rounded-lg border-2 border-gray-200 dark:border-gray-700">
                                        <span :class="[
                                            'absolute -top-1 -right-1 h-3 w-3 rounded-full border-2 border-white dark:border-gray-800',
                                            item.is_active ? 'bg-green-500' : 'bg-red-500'
                                        ]" :title="item.is_active ? 'Active' : 'Inactive'"></span>
                                    </div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h4 class="text-sm font-medium text-gray-900 dark:text-white truncate">{{ item.title }}</h4>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ item.category }}</p>
                                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">
                                        Created {{ formatDate(item.created_at) }}
                                    </p>
                                </div>
                                <div>
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
                        <div v-else class="text-center py-8">
                            <div class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500 mb-4">
                                <BriefcaseIcon class="h-12 w-12" />
                            </div>
                            <h3 class="text-sm font-medium text-gray-900 dark:text-white mb-1">No portfolio items</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Start by creating your first portfolio item</p>
                            <Link :href="route('admin.portfolio')" 
                                  class="mt-4 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 dark:bg-indigo-500 hover:bg-indigo-700 dark:hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                                <PlusIcon class="h-4 w-4 mr-2" />
                                Add Portfolio Item
                            </Link>
                        </div>
                    </div>
                </div>

                <!-- Recent Testimonials -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden border border-gray-200 dark:border-gray-700">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Recent Testimonials</h3>
                            <Link :href="route('admin.testimonials')" class="text-sm text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300">
                                View All →
                            </Link>
                        </div>
                    </div>
                    <div class="p-6">
                        <div v-if="recentUpdates.testimonials && recentUpdates.testimonials.length > 0" class="space-y-4">
                            <div v-for="testimonial in recentUpdates.testimonials" :key="testimonial.id" 
                                 class="p-4 border-l-4 border-indigo-500 dark:border-indigo-400 bg-gray-50 dark:bg-gray-900 rounded-r-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                                <div class="flex items-start mb-3">
                                    <div class="flex-1">
                                        <p class="text-sm text-gray-600 dark:text-gray-300 italic mb-2">
                                            "{{ testimonial.content.substring(0, 100) }}{{ testimonial.content.length > 100 ? '...' : '' }}"
                                        </p>
                                        <div class="flex items-center">
                                            <div class="flex-1">
                                                <h4 class="text-sm font-medium text-gray-900 dark:text-white">{{ testimonial.name }}</h4>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ testimonial.position }}</p>
                                            </div>
                                            <div class="flex ml-4">
                                                <StarIcon v-for="i in 5" :key="i" 
                                                         :class="[
                                                             'h-4 w-4',
                                                             i <= testimonial.rating 
                                                                 ? 'text-yellow-400' 
                                                                 : 'text-gray-300 dark:text-gray-600'
                                                         ]" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-xs text-gray-400 dark:text-gray-500 text-right">
                                    Added {{ formatDate(testimonial.created_at) }}
                                </div>
                            </div>
                        </div>
                        <div v-else class="text-center py-8">
                            <div class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500 mb-4">
                                <ChatBubbleLeftRightIcon class="h-12 w-12" />
                            </div>
                            <h3 class="text-sm font-medium text-gray-900 dark:text-white mb-1">No testimonials</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Add client testimonials to build trust</p>
                            <Link :href="route('admin.testimonials')" 
                                  class="mt-4 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 dark:bg-indigo-500 hover:bg-indigo-700 dark:hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                                <PlusIcon class="h-4 w-4 mr-2" />
                                Add Testimonial
                            </Link>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden border border-gray-200 dark:border-gray-700">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Quick Actions</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Manage your portfolio quickly</p>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        <!-- Edit Hero -->
                        <Link :href="route('admin.hero')" 
                              class="group bg-blue-50 dark:bg-blue-900/20 hover:bg-blue-100 dark:hover:bg-blue-900/40 p-5 rounded-lg text-center transition-all duration-300 hover:shadow-md border border-blue-100 dark:border-blue-800">
                            <div class="h-12 w-12 bg-blue-100 dark:bg-blue-800 rounded-full flex items-center justify-center mx-auto mb-3 group-hover:scale-110 transition-transform">
                                <PhotographIcon class="h-6 w-6 text-blue-600 dark:text-blue-400" />
                            </div>
                            <span class="text-sm font-medium text-blue-900 dark:text-blue-300">Edit Hero Section</span>
                            <p class="text-xs text-blue-600 dark:text-blue-400 mt-2">Update main banner</p>
                        </Link>
                        
                        <!-- Add Portfolio -->
                        <Link :href="route('admin.portfolio')" 
                              class="group bg-green-50 dark:bg-green-900/20 hover:bg-green-100 dark:hover:bg-green-900/40 p-5 rounded-lg text-center transition-all duration-300 hover:shadow-md border border-green-100 dark:border-green-800">
                            <div class="h-12 w-12 bg-green-100 dark:bg-green-800 rounded-full flex items-center justify-center mx-auto mb-3 group-hover:scale-110 transition-transform">
                                <PlusIcon class="h-6 w-6 text-green-600 dark:text-green-400" />
                            </div>
                            <span class="text-sm font-medium text-green-900 dark:text-green-300">Add Portfolio Item</span>
                            <p class="text-xs text-green-600 dark:text-green-400 mt-2">Showcase your work</p>
                        </Link>
                        
                        <!-- Add Testimonial -->
                        <Link :href="route('admin.testimonials')" 
                              class="group bg-yellow-50 dark:bg-yellow-900/20 hover:bg-yellow-100 dark:hover:bg-yellow-900/40 p-5 rounded-lg text-center transition-all duration-300 hover:shadow-md border border-yellow-100 dark:border-yellow-800">
                            <div class="h-12 w-12 bg-yellow-100 dark:bg-yellow-800 rounded-full flex items-center justify-center mx-auto mb-3 group-hover:scale-110 transition-transform">
                                <ChatBubbleLeftRightIcon class="h-6 w-6 text-yellow-600 dark:text-yellow-400" />
                            </div>
                            <span class="text-sm font-medium text-yellow-900 dark:text-yellow-300">Add Testimonial</span>
                            <p class="text-xs text-yellow-600 dark:text-yellow-400 mt-2">Client reviews</p>
                        </Link>
                        
                        <!-- Site Settings -->
                        <Link :href="route('admin.settings')" 
                              class="group bg-purple-50 dark:bg-purple-900/20 hover:bg-purple-100 dark:hover:bg-purple-900/40 p-5 rounded-lg text-center transition-all duration-300 hover:shadow-md border border-purple-100 dark:border-purple-800">
                            <div class="h-12 w-12 bg-purple-100 dark:bg-purple-800 rounded-full flex items-center justify-center mx-auto mb-3 group-hover:scale-110 transition-transform">
                                <CogIcon class="h-6 w-6 text-purple-600 dark:text-purple-400" />
                            </div>
                            <span class="text-sm font-medium text-purple-900 dark:text-purple-300">Site Settings</span>
                            <p class="text-xs text-purple-600 dark:text-purple-400 mt-2">Configure site</p>
                        </Link>
                    </div>
                </div>
            </div>

            <!-- Additional Stats -->
            <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Skills -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="text-lg font-medium text-gray-900 dark:text-white">Skills</h4>
                        <ChartBarIcon class="h-6 w-6 text-gray-400 dark:text-gray-500" />
                    </div>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ stats.skills || 0 }}</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Technical skills listed</p>
                    <Link :href="route('admin.skills')" class="mt-4 inline-flex items-center text-sm text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300">
                        Manage skills →
                    </Link>
                </div>

                <!-- Resume Items -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="text-lg font-medium text-gray-900 dark:text-white">Resume Items</h4>
                        <AcademicCapIcon class="h-6 w-6 text-gray-400 dark:text-gray-500" />
                    </div>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ stats.resume_items || 0 }}</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Experience & education</p>
                    <Link :href="route('admin.resume')" class="mt-4 inline-flex items-center text-sm text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300">
                        Update resume →
                    </Link>
                </div>

                <!-- Contact Info -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="text-lg font-medium text-gray-900 dark:text-white">Contact Info</h4>
                        <EnvelopeIcon class="h-6 w-6 text-gray-400 dark:text-gray-500" />
                    </div>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ stats.contact_info || 0 }}</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Contact details</p>
                    <Link :href="route('admin.contact')" class="mt-4 inline-flex items-center text-sm text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300">
                        Update contact →
                    </Link>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Link } from '@inertiajs/vue3';
import { 
    PhotoIcon as PhotographIcon,
    BriefcaseIcon, 
    ChatBubbleLeftRightIcon, 
    Cog6ToothIcon as CogIcon,
    PlusIcon,
    StarIcon,
    ChartBarIcon,
    AcademicCapIcon,
    EnvelopeIcon
} from '@heroicons/vue/24/outline';

defineProps({
    stats: Object,
    recentUpdates: Object
});

// Format date function
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

/* Focus styles for better accessibility */
a:focus, button:focus {
    outline: none;
    ring-width: 2px;
    ring-color: rgba(99, 102, 241, 0.5);
    ring-offset-width: 2px;
}

/* Hover effects */
.group:hover .group-hover\:scale-110 {
    transform: scale(1.1);
}

/* Image styling */
img {
    max-width: 100%;
    height: auto;
    object-fit: cover;
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

/* Loading skeleton animation */
@keyframes pulse {
    0%, 100% {
        opacity: 1;
    }
    50% {
        opacity: 0.5;
    }
}

.animate-pulse {
    animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}
</style>