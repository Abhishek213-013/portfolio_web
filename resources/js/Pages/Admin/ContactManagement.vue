<template>
    <AdminLayout>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 space-y-4 sm:space-y-0">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Contact Information Management</h2>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Manage your contact details for the contact section</p>
                </div>
                <button 
                    @click="showForm = !showForm" 
                    class="px-4 py-2 bg-indigo-600 dark:bg-indigo-500 text-white rounded-lg hover:bg-indigo-700 dark:hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900 transition-colors"
                >
                    {{ showForm ? 'Cancel' : 'Add New Contact Info' }}
                </button>
            </div>

            <!-- Add/Edit Contact Form -->
            <div v-if="showForm" class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-4 sm:p-6 mb-8 border border-gray-200 dark:border-gray-700">
                <form @submit.prevent="saveContactInfo" class="space-y-6">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Contact Type</label>
                            <select 
                                v-model="form.type" 
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                                required
                            >
                                <option value="address" class="text-gray-900 dark:text-white">Address</option>
                                <option value="phone" class="text-gray-900 dark:text-white">Phone</option>
                                <option value="email" class="text-gray-900 dark:text-white">Email</option>
                            </select>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Choose the type of contact information</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Icon Class</label>
                            <input 
                                v-model="form.icon" 
                                type="text" 
                                placeholder="e.g., bi bi-geo-alt or fas fa-map-marker-alt"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400"
                                required
                            />
                            <div class="flex items-center space-x-2 mt-1">
                                <span class="text-xs text-gray-500 dark:text-gray-400">Examples:</span>
                                <span class="text-xs px-2 py-1 bg-gray-100 dark:bg-gray-700 rounded">bi bi-geo-alt</span>
                                <span class="text-xs px-2 py-1 bg-gray-100 dark:bg-gray-700 rounded">fas fa-phone</span>
                                <span class="text-xs px-2 py-1 bg-gray-100 dark:bg-gray-700 rounded">far fa-envelope</span>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Title</label>
                            <input 
                                v-model="form.title" 
                                type="text" 
                                placeholder="e.g., Address, Call Us, Email Us"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400"
                                required
                            />
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Display title for the contact info</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Value</label>
                            <input 
                                v-model="form.value" 
                                type="text" 
                                :placeholder="getPlaceholder(form.type)"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400"
                                required
                            />
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">The actual contact information</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
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
                            <span v-else>{{ editingId ? 'Update Contact Info' : 'Add Contact Info' }}</span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Loading State -->
            <div v-if="initialLoading && contactInfo.length === 0" class="text-center py-12">
                <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-600 dark:border-indigo-400"></div>
                <p class="mt-4 text-sm text-gray-600 dark:text-gray-400">Loading contact information...</p>
            </div>

            <!-- Contact Information List -->
            <div v-else-if="!initialLoading || contactInfo.length > 0" class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden border border-gray-200 dark:border-gray-700">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Contact Information</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                {{ sortedContactInfo.length }} item{{ sortedContactInfo.length !== 1 ? 's' : '' }} • 
                                {{ activeContactInfoCount }} active
                            </p>
                        </div>
                        <div class="flex space-x-2">
                            <span class="px-3 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                {{ getTypeCount('address') }} Address
                            </span>
                            <span class="px-3 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                {{ getTypeCount('phone') }} Phone
                            </span>
                            <span class="px-3 py-1 text-xs font-medium rounded-full bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200">
                                {{ getTypeCount('email') }} Email
                            </span>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    <draggable 
                        v-model="sortedContactInfo" 
                        @end="updateOrder"
                        item-key="id"
                        class="space-y-4"
                        handle=".drag-handle"
                        :disabled="sortedContactInfo.length === 0"
                        v-if="Array.isArray(sortedContactInfo)"
                    >
                        <template #item="{ element: contact }">
                            <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-6 hover:shadow-md transition-all duration-300 bg-white dark:bg-gray-900">
                                <div class="flex items-start space-x-4">
                                    <div class="flex-shrink-0">
                                        <div class="drag-handle cursor-move text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 pt-1">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"></path>
                                            </svg>
                                        </div>
                                    </div>
                                    <div :class="[
                                        'flex-shrink-0 h-14 w-14 rounded-xl flex items-center justify-center',
                                        getTypeColor(contact.type)
                                    ]">
                                        <i :class="contact.icon" class="text-2xl text-white"></i>
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex flex-col lg:flex-row lg:items-start justify-between">
                                            <div>
                                                <h4 class="text-lg font-medium text-gray-900 dark:text-white">{{ contact.title }}</h4>
                                                <p class="text-gray-700 dark:text-gray-300 mt-2 text-sm">{{ contact.value }}</p>
                                                
                                                <div class="flex items-center space-x-3 mt-3">
                                                    <span class="text-xs px-2 py-1 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400 rounded">
                                                        Icon: <code class="ml-1 font-mono">{{ contact.icon }}</code>
                                                    </span>
                                                    <span class="text-xs px-2 py-1 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400 rounded capitalize">
                                                        {{ contact.type }}
                                                    </span>
                                                    <span class="text-xs px-2 py-1 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400 rounded">
                                                        Order: {{ contact.order }}
                                                    </span>
                                                </div>
                                            </div>
                                            
                                            <div class="flex items-center space-x-2 mt-3 lg:mt-0">
                                                <span :class="[
                                                    'px-3 py-1 text-xs font-medium rounded-full',
                                                    contact.is_active 
                                                        ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' 
                                                        : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200'
                                                ]">
                                                    <span class="w-2 h-2 rounded-full inline-block mr-1" 
                                                          :class="contact.is_active ? 'bg-green-500' : 'bg-red-500'"></span>
                                                    {{ contact.is_active ? 'Active' : 'Inactive' }}
                                                </span>
                                                <div class="flex space-x-1">
                                                    <button 
                                                        @click="editContactInfo(contact)"
                                                        class="p-1.5 text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-300 hover:bg-blue-50 dark:hover:bg-blue-900/30 rounded-md transition-colors"
                                                        title="Edit contact info"
                                                    >
                                                        <PencilIcon class="h-4 w-4" />
                                                    </button>
                                                    <button 
                                                        @click="deleteContactInfo(contact.id)"
                                                        class="p-1.5 text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300 hover:bg-red-50 dark:hover:bg-red-900/30 rounded-md transition-colors"
                                                        title="Delete contact info"
                                                    >
                                                        <TrashIcon class="h-4 w-4" />
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700 text-xs text-gray-500 dark:text-gray-400">
                                            <div class="flex items-center space-x-3">
                                                <span class="inline-flex items-center">
                                                    <svg class="h-3 w-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                    </svg>
                                                    Drag handle to reorder
                                                </span>
                                                <span>Last updated: {{ formatDate(contact.updated_at) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </draggable>

                    <div v-if="!sortedContactInfo || sortedContactInfo.length === 0" class="text-center py-12 border-2 border-dashed border-gray-300 dark:border-gray-700 rounded-lg">
                        <div class="mx-auto h-16 w-16 bg-indigo-100 dark:bg-indigo-900 rounded-full flex items-center justify-center mb-4">
                            <EnvelopeIcon class="h-8 w-8 text-indigo-600 dark:text-indigo-400" />
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No Contact Information</h3>
                        <p class="text-gray-600 dark:text-gray-400 mb-6 max-w-md mx-auto">
                            Add your contact information so visitors can easily get in touch with you.
                        </p>
                        <button 
                            @click="showForm = true" 
                            class="px-6 py-3 bg-indigo-600 dark:bg-indigo-500 text-white font-medium rounded-lg hover:bg-indigo-700 dark:hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition-colors"
                        >
                            Add First Contact Info
                        </button>
                    </div>
                </div>
            </div>

            <!-- Contact Info Preview -->
            <div v-if="!initialLoading && sortedContactInfo && sortedContactInfo.length > 0" class="mt-8 bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden border border-gray-200 dark:border-gray-700">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Live Preview</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">How your contact information will appear on the website</p>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div 
                            v-for="contact in sortedContactInfo.filter(c => c && c.is_active)" 
                            :key="contact.id"
                            class="border border-gray-200 dark:border-gray-700 rounded-xl p-6 text-center hover:shadow-lg transition-all duration-300 bg-white dark:bg-gray-900"
                        >
                            <div :class="[
                                'inline-flex h-16 w-16 items-center justify-center rounded-full mb-4 transition-transform hover:scale-110',
                                getTypeColor(contact.type)
                            ]">
                                <i :class="contact.icon" class="text-2xl text-white"></i>
                            </div>
                            <h4 class="text-lg font-medium text-gray-900 dark:text-white mb-3">{{ contact.title }}</h4>
                            <p class="text-gray-600 dark:text-gray-400">{{ contact.value }}</p>
                            <div class="mt-4">
                                <span :class="[
                                    'text-xs px-3 py-1 rounded-full font-medium',
                                    contact.type === 'address' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' :
                                    contact.type === 'phone' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' :
                                    'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200'
                                ]">
                                    {{ getTypeLabel(contact.type) }}
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <div v-if="sortedContactInfo.filter(c => c && c.is_active).length === 0" 
                         class="text-center py-8 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg">
                        <p class="text-yellow-800 dark:text-yellow-200">
                            <svg class="h-5 w-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.998-.833-2.732 0L4.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                            No active contact information. Activate items to see them in preview.
                        </p>
                    </div>
                    
                    <div class="mt-6 text-center">
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            Showing {{ sortedContactInfo.filter(c => c && c.is_active).length }} of {{ sortedContactInfo.length }} items
                        </p>
                    </div>
                </div>
            </div>
            
            <!-- Contact Stats -->
            <div v-if="!initialLoading" class="mt-8 grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center">
                        <div class="h-10 w-10 bg-indigo-100 dark:bg-indigo-900 rounded-lg flex items-center justify-center mr-4">
                            <EnvelopeIcon class="h-5 w-5 text-indigo-600 dark:text-indigo-400" />
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Items</p>
                            <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ sortedContactInfo.length }}</p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center">
                        <div class="h-10 w-10 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center mr-4">
                            <svg class="h-5 w-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Address</p>
                            <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ getTypeCount('address') }}</p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center">
                        <div class="h-10 w-10 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center mr-4">
                            <svg class="h-5 w-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Phone</p>
                            <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ getTypeCount('phone') }}</p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center">
                        <div class="h-10 w-10 bg-purple-100 dark:bg-purple-900 rounded-lg flex items-center justify-center mr-4">
                            <svg class="h-5 w-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Email</p>
                            <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ getTypeCount('email') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { ref, reactive, computed, onMounted } from 'vue';
import { router } from '@inertiajs/vue3';
import draggable from 'vuedraggable';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { PencilIcon, TrashIcon, EnvelopeIcon } from '@heroicons/vue/24/outline';

const showForm = ref(false);
const loading = ref(false);
const initialLoading = ref(true);
const editingId = ref(null);
const contactInfo = ref([]);
const form = reactive({
    type: 'address',
    icon: '',
    title: '',
    value: '',
    order: 0,
    is_active: true
});

// Safeguard computed properties with fallbacks
const sortedContactInfo = computed(() => {
    if (!Array.isArray(contactInfo.value)) return [];
    return contactInfo.value
        .filter(contact => contact && typeof contact === 'object')
        .sort((a, b) => (a.order || 0) - (b.order || 0));
});

const activeContactInfoCount = computed(() => {
    if (!Array.isArray(contactInfo.value)) return 0;
    return contactInfo.value.filter(contact => contact && contact.is_active).length;
});

onMounted(() => {
    fetchContactInfo();
});

const fetchContactInfo = async () => {
    try {
        initialLoading.value = true;
        const response = await axios.get('/api/contact-info');
        
        // Handle different response formats
        let contactData = [];
        
        if (Array.isArray(response.data)) {
            contactData = response.data;
        } else if (response.data && Array.isArray(response.data.data)) {
            contactData = response.data.data;
        } else if (response.data && typeof response.data === 'object') {
            // Convert object values to array if needed
            contactData = Object.values(response.data);
        }
        
        // Ensure all contact info have required properties
        contactData = contactData.map(contact => ({
            id: contact.id || 0,
            type: contact.type || 'address',
            icon: contact.icon || '',
            title: contact.title || '',
            value: contact.value || '',
            order: contact.order || 0,
            is_active: contact.is_active !== undefined ? contact.is_active : true,
            updated_at: contact.updated_at || new Date().toISOString()
        }));
        
        contactInfo.value = contactData;
        
        console.log('Fetched contact information:', contactInfo.value);
        
    } catch (error) {
        console.error('Error fetching contact info:', error);
        contactInfo.value = [];
        
        // Show error to user
        if (router.page.props && router.page.props.flash) {
            router.page.props.flash.error = 'Failed to load contact information. Please try again.';
        }
    } finally {
        initialLoading.value = false;
    }
};

const getTypeColor = (type) => {
    const colorMap = {
        address: 'bg-gradient-to-br from-blue-500 to-blue-600 dark:from-blue-600 dark:to-blue-700',
        phone: 'bg-gradient-to-br from-green-500 to-green-600 dark:from-green-600 dark:to-green-700',
        email: 'bg-gradient-to-br from-purple-500 to-purple-600 dark:from-purple-600 dark:to-purple-700'
    };
    return colorMap[type] || 'bg-gradient-to-br from-gray-500 to-gray-600 dark:from-gray-600 dark:to-gray-700';
};

const getTypeLabel = (type) => {
    const labelMap = {
        address: 'Address',
        phone: 'Phone',
        email: 'Email'
    };
    return labelMap[type] || type;
};

const getPlaceholder = (type) => {
    const placeholderMap = {
        address: 'e.g., A108 Adam Street, New York, NY 535022',
        phone: 'e.g., +1 5589 55488 55',
        email: 'e.g., info@example.com'
    };
    return placeholderMap[type] || 'Enter contact information';
};

const getTypeCount = (type) => {
    if (!Array.isArray(contactInfo.value)) return 0;
    return contactInfo.value.filter(contact => contact && contact.type === type).length;
};

const editContactInfo = (contact) => {
    editingId.value = contact.id;
    Object.assign(form, {
        type: contact.type,
        icon: contact.icon,
        title: contact.title,
        value: contact.value,
        order: contact.order,
        is_active: contact.is_active
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
        type: 'address',
        icon: '',
        title: '',
        value: '',
        order: 0,
        is_active: true
    });
};

const saveContactInfo = async () => {
    loading.value = true;
    
    try {
        let response;
        if (editingId.value) {
            response = await axios.put(`/api/contact-info/${editingId.value}`, form);
        } else {
            response = await axios.post('/api/contact-info', form);
        }

        if (response.status === 200 || response.status === 201) {
            await fetchContactInfo();
            cancelEdit();
            
            // Show success message
            router.visit(window.location.pathname, {
                preserveState: true,
                preserveScroll: true,
                only: [],
                onSuccess: () => {
                    if (router.page.props && router.page.props.flash) {
                        router.page.props.flash.success = editingId.value 
                            ? 'Contact information updated successfully!' 
                            : 'Contact information added successfully!';
                    }
                }
            });
        }
    } catch (error) {
        console.error('Error saving contact info:', error);
        
        // Show error message
        const errorMessage = error.response?.data?.message || 
                           (error.response?.data?.errors ? 
                            Object.values(error.response.data.errors).flat().join(', ') : 
                            'Failed to save contact information. Please try again.');
        
        alert(errorMessage);
    } finally {
        loading.value = false;
    }
};

const deleteContactInfo = async (id) => {
    if (!confirm('Are you sure you want to delete this contact information? This action cannot be undone.')) return;
    
    try {
        await axios.delete(`/api/contact-info/${id}`);
        await fetchContactInfo();
        
        // Show success message
        router.visit(window.location.pathname, {
            preserveState: true,
            preserveScroll: true,
            only: [],
            onSuccess: () => {
                if (router.page.props && router.page.props.flash) {
                    router.page.props.flash.success = 'Contact information deleted successfully!';
                }
            }
        });
        
    } catch (error) {
        console.error('Error deleting contact info:', error);
        
        // Show error message
        const errorMessage = error.response?.data?.message || 'Failed to delete contact information. Please try again.';
        alert(errorMessage);
    }
};

const updateOrder = async () => {
    if (!Array.isArray(sortedContactInfo.value)) return;
    
    const contactsWithOrder = sortedContactInfo.value.map((contact, index) => ({
        id: contact.id,
        order: index
    }));
    
    try {
        // Update order for each contact
        for (const contact of contactsWithOrder) {
            await axios.put(`/api/contact-info/${contact.id}`, { order: contact.order });
        }
        await fetchContactInfo();
    } catch (error) {
        console.error('Error updating order:', error);
        
        // Show error message
        const errorMessage = error.response?.data?.message || 'Failed to update contact information order. Please try again.';
        alert(errorMessage);
        
        // Revert local changes on error
        await fetchContactInfo();
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

/* Drag and drop styles */
.drag-handle {
    transition: color 150ms ease;
}

.drag-handle:hover {
    color: #4b5563;
}

.dark .drag-handle:hover {
    color: #d1d5db;
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

/* Gradient background animation */
.bg-gradient-to-br {
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

/* Icon styling */
i[class^="bi-"], i[class^="fa-"] {
    font-style: normal;
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

/* Card hover effects */
.hover\:shadow-md:hover {
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
}

/* Preview card hover effect */
.hover\:scale-110 {
    transition: transform 0.3s ease;
}

/* Type badge pulse animation */
@keyframes pulse-badge {
    0%, 100% {
        opacity: 1;
    }
    50% {
        opacity: 0.7;
    }
}

.pulse-animation {
    animation: pulse-badge 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}
</style>