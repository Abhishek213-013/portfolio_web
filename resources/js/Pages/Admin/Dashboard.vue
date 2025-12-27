<template>
    <AdminLayout>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Stats Cards -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <PhotographIcon class="h-8 w-8 text-blue-600" />
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Hero Sections</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ stats.hero_sections }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <BriefcaseIcon class="h-8 w-8 text-green-600" />
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Portfolio Items</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ stats.portfolio_items }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <ChatBubbleLeftRightIcon class="h-8 w-8 text-yellow-600" />
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Testimonials</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ stats.testimonials }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <CogIcon class="h-8 w-8 text-purple-600" />
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Services</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ stats.services }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Recent Portfolio Items -->
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Recent Portfolio Items</h3>
                </div>
                <div class="p-6">
                    <div v-if="recentUpdates.portfolio && recentUpdates.portfolio.length > 0" class="space-y-4">
                        <div v-for="item in recentUpdates.portfolio" :key="item.id" class="flex items-center space-x-4">
                            <img :src="item.image" :alt="item.title" class="h-16 w-16 object-cover rounded">
                            <div>
                                <h4 class="text-sm font-medium text-gray-900">{{ item.title }}</h4>
                                <p class="text-sm text-gray-500">{{ item.category }}</p>
                            </div>
                            <span :class="[
                                'px-2 py-1 text-xs font-semibold rounded-full',
                                item.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'
                            ]">
                                {{ item.is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                    </div>
                    <div v-else class="text-center text-gray-500 py-4">
                        No portfolio items yet
                    </div>
                </div>
            </div>

            <!-- Recent Testimonials -->
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Recent Testimonials</h3>
                </div>
                <div class="p-6">
                    <div v-if="recentUpdates.testimonials && recentUpdates.testimonials.length > 0" class="space-y-4">
                        <div v-for="testimonial in recentUpdates.testimonials" :key="testimonial.id" class="border-l-4 border-indigo-500 pl-4">
                            <p class="text-sm text-gray-600 italic mb-2">"{{ testimonial.content.substring(0, 100) }}..."</p>
                            <div class="flex items-center justify-between">
                                <div>
                                    <h4 class="text-sm font-medium text-gray-900">{{ testimonial.name }}</h4>
                                    <p class="text-xs text-gray-500">{{ testimonial.position }}</p>
                                </div>
                                <div class="flex">
                                    <StarIcon v-for="i in testimonial.rating" :key="i" class="h-4 w-4 text-yellow-400" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div v-else class="text-center text-gray-500 py-4">
                        No testimonials yet
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="mt-8 bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Quick Actions</h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <Link :href="route('admin.hero')" class="bg-blue-50 hover:bg-blue-100 p-4 rounded-lg text-center transition-colors">
                        <PhotographIcon class="h-8 w-8 text-blue-600 mx-auto mb-2" />
                        <span class="text-sm font-medium text-blue-900">Edit Hero</span>
                    </Link>
                    
                    <Link :href="route('admin.portfolio')" class="bg-green-50 hover:bg-green-100 p-4 rounded-lg text-center transition-colors">
                        <PlusIcon class="h-8 w-8 text-green-600 mx-auto mb-2" />
                        <span class="text-sm font-medium text-green-900">Add Portfolio</span>
                    </Link>
                    
                    <Link :href="route('admin.testimonials')" class="bg-yellow-50 hover:bg-yellow-100 p-4 rounded-lg text-center transition-colors">
                        <ChatBubbleLeftRightIcon class="h-8 w-8 text-yellow-600 mx-auto mb-2" />
                        <span class="text-sm font-medium text-yellow-900">Add Testimonial</span>
                    </Link>
                    
                    <Link :href="route('admin.settings')" class="bg-purple-50 hover:bg-purple-100 p-4 rounded-lg text-center transition-colors">
                        <CogIcon class="h-8 w-8 text-purple-600 mx-auto mb-2" />
                        <span class="text-sm font-medium text-purple-900">Site Settings</span>
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
    StarIcon 
} from '@heroicons/vue/24/outline';

defineProps({
    stats: Object,
    recentUpdates: Object
});
</script>