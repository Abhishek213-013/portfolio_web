<template>
    <AdminLayout>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <!-- Flash Messages -->
            <div v-if="$page.props.flash?.success" 
                 class="mb-6 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-800 dark:text-green-300">
                            {{ $page.props.flash.success }}
                        </p>
                    </div>
                </div>
            </div>
            
            <div v-if="$page.props.flash?.error" 
                 class="mb-6 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-red-800 dark:text-red-300">
                            {{ $page.props.flash.error }}
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 space-y-4 sm:space-y-0">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Resume Management</h2>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Manage your resume sections including summary, education, and experience</p>
                </div>
                <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-2">
                    <select v-model="activeType" 
                            class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                        <option value="summary" class="text-gray-900 dark:text-white">Summary</option>
                        <option value="education" class="text-gray-900 dark:text-white">Education</option>
                        <option value="experience" class="text-gray-900 dark:text-white">Experience</option>
                    </select>
                    <button 
                        @click="showForm = !showForm" 
                        class="px-4 py-2 bg-indigo-600 dark:bg-indigo-500 text-white rounded-lg hover:bg-indigo-700 dark:hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900 transition-colors"
                    >
                        {{ showForm ? 'Cancel' : 'Add New' }}
                    </button>
                </div>
            </div>

            <!-- Add/Edit Resume Form -->
            <div v-if="showForm" class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-4 sm:p-6 mb-8 border border-gray-200 dark:border-gray-700">
                <form @submit.prevent="saveResumeSection" class="space-y-6">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Section Type</label>
                            <select 
                                v-model="form.type" 
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                                required
                            >
                                <option value="summary" class="text-gray-900 dark:text-white">Summary</option>
                                <option value="education" class="text-gray-900 dark:text-white">Education</option>
                                <option value="experience" class="text-gray-900 dark:text-white">Experience</option>
                            </select>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Choose the type of resume section</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Title</label>
                            <input 
                                v-model="form.title" 
                                type="text" 
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400"
                                required
                                placeholder="Enter section title"
                            />
                        </div>
                    </div>

                    <div v-if="form.type !== 'summary'" class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                {{ form.type === 'education' ? 'Institution' : 'Company' }}
                            </label>
                            <input 
                                v-model="form.subtitle" 
                                type="text" 
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400"
                                :placeholder="form.type === 'education' ? 'Enter institution name' : 'Enter company name'"
                            />
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Period</label>
                            <input 
                                v-model="form.period" 
                                type="text" 
                                placeholder="e.g., 2019 - Present"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400"
                            />
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Description</label>
                        <textarea 
                            v-model="form.description" 
                            rows="4"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400"
                            required
                            placeholder="Enter detailed description"
                        ></textarea>
                    </div>

                    <div class="bg-gray-50 dark:bg-gray-900 p-4 rounded-lg">
                        <div class="flex items-center justify-between mb-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Key Points</label>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Add bullet points for this section</p>
                            </div>
                            <button 
                                type="button" 
                                @click="addDetail"
                                class="px-3 py-1 bg-indigo-100 dark:bg-indigo-900 text-indigo-700 dark:text-indigo-300 rounded-md hover:bg-indigo-200 dark:hover:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 transition-colors text-sm"
                            >
                                + Add Point
                            </button>
                        </div>
                        
                        <div v-for="(detail, index) in form.details" :key="index" 
                             class="flex flex-col sm:flex-row sm:items-center space-y-2 sm:space-y-0 sm:space-x-2 mb-3 p-3 bg-white dark:bg-gray-800 rounded-md border border-gray-200 dark:border-gray-700">
                            <div class="flex-1">
                                <input 
                                    v-model="form.details[index]" 
                                    type="text" 
                                    :placeholder="`Key point ${index + 1}`"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 text-sm"
                                />
                            </div>
                            <div>
                                <button 
                                    type="button" 
                                    @click="removeDetail(index)"
                                    class="w-full sm:w-auto px-3 py-2 bg-red-100 dark:bg-red-900 text-red-600 dark:text-red-300 rounded-md hover:bg-red-200 dark:hover:bg-red-800 focus:outline-none focus:ring-2 focus:ring-red-500 dark:focus:ring-red-400 transition-colors text-sm"
                                >
                                    Remove
                                </button>
                            </div>
                        </div>
                        
                        <div v-if="form.details.length === 0" 
                             class="text-center py-4 border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-md">
                            <p class="text-sm text-gray-500 dark:text-gray-400">No key points added yet</p>
                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Add bullet points like achievements, responsibilities, or skills</p>
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
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Lower numbers appear first in the list</p>
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
                            <span v-else>{{ editingId ? 'Update Section' : 'Add Section' }}</span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Loading State -->
            <div v-if="initialLoading && resumeSections.length === 0" class="text-center py-12">
                <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-600 dark:border-indigo-400"></div>
                <p class="mt-4 text-sm text-gray-600 dark:text-gray-400">Loading resume sections...</p>
            </div>

            <!-- Resume Sections Tabs -->
            <div v-else-if="!initialLoading || resumeSections.length > 0" class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden border border-gray-200 dark:border-gray-700">
                <div class="border-b border-gray-200 dark:border-gray-700">
                    <nav class="flex -mb-px overflow-x-auto">
                        <button 
                            v-for="tab in tabs" 
                            :key="tab.value"
                            @click="activeType = tab.value"
                            :class="[
                                'py-4 px-6 text-sm font-medium border-b-2 whitespace-nowrap transition-colors',
                                activeType === tab.value
                                    ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400 dark:border-indigo-400'
                                    : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600'
                            ]"
                        >
                            {{ tab.label }}
                            <span class="ml-2 px-2 py-0.5 text-xs rounded-full bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400">
                                {{ getSectionCount(tab.value) }}
                            </span>
                        </button>
                    </nav>
                </div>

                <div class="p-6">
                    <!-- Section Stats -->
                    <div class="mb-6 p-4 bg-gray-50 dark:bg-gray-900 rounded-lg">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-sm font-medium text-gray-900 dark:text-white">{{ getTypeLabel(activeType) }} Sections</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ filteredResumeSections.length }} section{{ filteredResumeSections.length !== 1 ? 's' : '' }}
                                    • {{ getActiveCount(activeType) }} active
                                </p>
                            </div>
                            <button 
                                @click="showForm = true; form.type = activeType" 
                                class="px-4 py-2 bg-indigo-600 dark:bg-indigo-500 text-white text-sm rounded-lg hover:bg-indigo-700 dark:hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition-colors"
                            >
                                + Add {{ getTypeLabel(activeType) }}
                            </button>
                        </div>
                    </div>

                    <!-- Draggable Sections -->
                    <draggable 
                        v-model="filteredResumeSections" 
                        @end="updateOrder"
                        item-key="id"
                        class="space-y-6"
                        handle=".drag-handle"
                        :disabled="filteredResumeSections.length === 0"
                        v-if="Array.isArray(filteredResumeSections)"
                    >
                        <template #item="{ element: section, index }">
                            <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-6 hover:shadow-md transition-all duration-300 bg-white dark:bg-gray-900">
                                <div class="flex items-start space-x-3">
                                    <div class="drag-handle cursor-move pt-1 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"></path>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex flex-col lg:flex-row lg:items-start justify-between mb-4">
                                            <div>
                                                <div class="flex items-center space-x-2 mb-2">
                                                    <div :class="[
                                                        'h-3 w-3 rounded-full',
                                                        section.type === 'summary' ? 'bg-blue-500' :
                                                        section.type === 'education' ? 'bg-green-500' : 'bg-purple-500'
                                                    ]"></div>
                                                    <h4 class="text-lg font-medium text-gray-900 dark:text-white">{{ section.title }}</h4>
                                                </div>
                                                
                                                <div v-if="section.subtitle || section.period" class="flex flex-col sm:flex-row sm:items-center space-y-1 sm:space-y-0 sm:space-x-4 mt-2">
                                                    <span v-if="section.subtitle" 
                                                          class="inline-flex items-center text-sm text-gray-600 dark:text-gray-400">
                                                        <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                                  :d="section.type === 'education' 
                                                                      ? 'M12 14l9-5-9-5-9 5 9 5z M12 14l9-5-9-5-9 5 9 5z M12 14v6l9-5M12 14l-9-5v10l9 5' 
                                                                      : 'M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z'"/>
                                                        </svg>
                                                        {{ section.subtitle }}
                                                    </span>
                                                    <span v-if="section.period" 
                                                          class="inline-flex items-center text-sm font-medium text-indigo-600 dark:text-indigo-400">
                                                        <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                        </svg>
                                                        {{ section.period }}
                                                    </span>
                                                </div>
                                            </div>
                                            
                                            <div class="flex items-center space-x-2 mt-2 lg:mt-0">
                                                <span :class="[
                                                    'px-3 py-1 text-xs font-medium rounded-full',
                                                    section.is_active 
                                                        ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' 
                                                        : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200'
                                                ]">
                                                    <span class="w-2 h-2 rounded-full inline-block mr-1" 
                                                          :class="section.is_active ? 'bg-green-500' : 'bg-red-500'"></span>
                                                    {{ section.is_active ? 'Active' : 'Inactive' }}
                                                </span>
                                                <div class="flex space-x-1">
                                                    <button 
                                                        @click="editResumeSection(section)"
                                                        class="p-1.5 text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-300 hover:bg-blue-50 dark:hover:bg-blue-900/30 rounded-md transition-colors"
                                                        title="Edit section"
                                                    >
                                                        <PencilIcon class="h-4 w-4" />
                                                    </button>
                                                    <button 
                                                        @click="deleteResumeSection(section.id)"
                                                        class="p-1.5 text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300 hover:bg-red-50 dark:hover:bg-red-900/30 rounded-md transition-colors"
                                                        title="Delete section"
                                                    >
                                                        <TrashIcon class="h-4 w-4" />
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="mb-4">
                                            <p class="text-gray-700 dark:text-gray-300 leading-relaxed">{{ section.description }}</p>
                                        </div>
                                        
                                        <ul v-if="section.details && section.details.length > 0" class="space-y-2 pl-4">
                                            <li v-for="(detail, detailIndex) in section.details" :key="detailIndex" 
                                                class="text-gray-600 dark:text-gray-400 flex items-start">
                                                <span class="text-indigo-500 dark:text-indigo-400 mr-2 mt-1">•</span>
                                                <span>{{ detail }}</span>
                                            </li>
                                        </ul>
                                        
                                        <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700 flex flex-col sm:flex-row justify-between items-start sm:items-center text-sm text-gray-500 dark:text-gray-400">
                                            <div class="flex items-center space-x-3">
                                                <span class="inline-flex items-center">
                                                    <svg class="h-4 w-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                    </svg>
                                                    Drag handle to reorder
                                                </span>
                                                <span>Display Order: {{ section.order }}</span>
                                            </div>
                                            <span class="mt-2 sm:mt-0">
                                                Last updated: {{ formatDate(section.updated_at) }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </draggable>

                    <div v-if="!filteredResumeSections || filteredResumeSections.length === 0" class="text-center py-12 border-2 border-dashed border-gray-300 dark:border-gray-700 rounded-lg">
                        <div :class="[
                            'mx-auto h-16 w-16 rounded-full flex items-center justify-center mb-4',
                            activeType === 'summary' ? 'bg-blue-100 dark:bg-blue-900' :
                            activeType === 'education' ? 'bg-green-100 dark:bg-green-900' : 'bg-purple-100 dark:bg-purple-900'
                        ]">
                            <AcademicCapIcon :class="[
                                'h-8 w-8',
                                activeType === 'summary' ? 'text-blue-600 dark:text-blue-400' :
                                activeType === 'education' ? 'text-green-600 dark:text-green-400' : 'text-purple-600 dark:text-purple-400'
                            ]" />
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No {{ activeType }} sections found</h3>
                        <p class="text-gray-600 dark:text-gray-400 mb-6 max-w-md mx-auto">
                            {{ 
                                activeType === 'summary' ? 'Add a professional summary to introduce yourself.' :
                                activeType === 'education' ? 'Add your educational background and qualifications.' :
                                'Add your work experience and career history.'
                            }}
                        </p>
                        <button 
                            @click="showForm = true; form.type = activeType" 
                            class="px-6 py-3 bg-indigo-600 dark:bg-indigo-500 text-white font-medium rounded-lg hover:bg-indigo-700 dark:hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition-colors"
                        >
                            Add First {{ getTypeLabel(activeType) }} Section
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Resume Stats Overview -->
            <div v-if="!initialLoading" class="mt-8 grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center">
                        <div class="h-10 w-10 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center mr-4">
                            <AcademicCapIcon class="h-5 w-5 text-blue-600 dark:text-blue-400" />
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Sections</p>
                            <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ resumeSections.length }}</p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center">
                        <div class="h-10 w-10 bg-indigo-100 dark:bg-indigo-900 rounded-lg flex items-center justify-center mr-4">
                            <svg class="h-5 w-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Summary</p>
                            <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ getSectionCount('summary') }}</p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center">
                        <div class="h-10 w-10 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center mr-4">
                            <svg class="h-5 w-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z M12 14l9-5-9-5-9 5 9 5z M12 14v6l9-5M12 14l-9-5v10l9 5"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Education</p>
                            <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ getSectionCount('education') }}</p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center">
                        <div class="h-10 w-10 bg-purple-100 dark:bg-purple-900 rounded-lg flex items-center justify-center mr-4">
                            <svg class="h-5 w-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Experience</p>
                            <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ getSectionCount('experience') }}</p>
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
import { PencilIcon, TrashIcon, AcademicCapIcon } from '@heroicons/vue/24/outline';

const showForm = ref(false);
const loading = ref(false);
const initialLoading = ref(true);
const editingId = ref(null);
const activeType = ref('summary');
const resumeSections = ref([]);
const form = reactive({
    type: 'summary',
    title: '',
    subtitle: '',
    period: '',
    description: '',
    details: [],
    order: 0,
    is_active: true
});

const tabs = [
    { label: 'Summary', value: 'summary' },
    { label: 'Education', value: 'education' },
    { label: 'Experience', value: 'experience' }
];

// Safeguard computed properties with fallbacks
const filteredResumeSections = computed(() => {
    if (!Array.isArray(resumeSections.value)) return [];
    return resumeSections.value
        .filter(section => section && section.type === activeType.value)
        .sort((a, b) => (a.order || 0) - (b.order || 0));
});

onMounted(() => {
    fetchResumeSections();
});

const fetchResumeSections = async () => {
    try {
        initialLoading.value = true;
        const response = await axios.get('/api/resume-sections');
        
        // Handle different response formats
        let sectionsData = [];
        
        if (Array.isArray(response.data)) {
            sectionsData = response.data;
        } else if (response.data && Array.isArray(response.data.data)) {
            sectionsData = response.data.data;
        } else if (response.data && typeof response.data === 'object') {
            // Convert object values to array if needed
            sectionsData = Object.values(response.data);
        }
        
        // Map institution to subtitle for frontend display
        sectionsData = sectionsData.map(section => ({
            id: section.id || 0,
            type: section.type || 'summary',
            title: section.title || '',
            subtitle: section.institution || section.subtitle || '', // Use institution or subtitle
            period: section.period || '',
            description: section.description || '',
            details: Array.isArray(section.details) ? section.details : (section.details ? [section.details] : []),
            order: section.order || 0,
            is_active: section.is_active !== undefined ? section.is_active : true,
            updated_at: section.updated_at || new Date().toISOString()
        }));
        
        resumeSections.value = sectionsData;
        
    } catch (error) {
        console.error('Error fetching resume sections:', error);
        resumeSections.value = [];
    } finally {
        initialLoading.value = false;
    }
};

const getTypeLabel = (type) => {
    const tab = tabs.find(t => t.value === type);
    return tab ? tab.label : type;
};

const getSectionCount = (type) => {
    if (!Array.isArray(resumeSections.value)) return 0;
    return resumeSections.value.filter(section => section && section.type === type).length;
};

const getActiveCount = (type) => {
    if (!Array.isArray(resumeSections.value)) return 0;
    return resumeSections.value.filter(section => section && section.type === type && section.is_active).length;
};

const editResumeSection = (section) => {
    editingId.value = section.id;
    Object.assign(form, {
        type: section.type,
        title: section.title,
        subtitle: section.subtitle,
        period: section.period,
        description: section.description,
        details: Array.isArray(section.details) ? [...section.details] : [],
        order: section.order,
        is_active: section.is_active
    });
    showForm.value = true;
    activeType.value = section.type;
    
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
        type: activeType.value,
        title: '',
        subtitle: '',
        period: '',
        description: '',
        details: [],
        order: 0,
        is_active: true
    });
};

const addDetail = () => {
    form.details.push('');
};

const removeDetail = (index) => {
    form.details.splice(index, 1);
};

const saveResumeSection = async () => {
    loading.value = true;
    
    try {
        // Prepare data for API
        const apiData = {
            type: form.type,
            title: form.title,
            institution: form.subtitle, // Map subtitle to institution
            period: form.period,
            description: form.description,
            details: form.details,
            order: form.order,
            is_active: form.is_active
        };
        
        let response;
        if (editingId.value) {
            response = await axios.put(`/api/resume-sections/${editingId.value}`, apiData);
        } else {
            response = await axios.post('/api/resume-sections', apiData);
        }

        if (response.status === 200 || response.status === 201) {
            await fetchResumeSections();
            cancelEdit();
            
            // Show success message
            const message = editingId.value 
                ? 'Resume section updated successfully!' 
                : 'Resume section added successfully!';
            
            // Use Inertia's visit method
            router.visit(window.location.pathname, {
                preserveScroll: true,
                data: {
                    success: message
                }
            });
        }
    } catch (error) {
        console.error('Error saving resume section:', error);
        
        // Show error message
        let errorMessage = 'Failed to save resume section. Please try again.';
        
        if (error.response?.data?.message) {
            errorMessage = error.response.data.message;
        } else if (error.response?.data?.errors) {
            // Handle validation errors
            const errors = error.response.data.errors;
            errorMessage = Object.values(errors).flat().join(', ');
        }
        
        // Show error alert
        alert(errorMessage);
    } finally {
        loading.value = false;
    }
};

const deleteResumeSection = async (id) => {
    if (!confirm('Are you sure you want to delete this resume section? This action cannot be undone.')) return;
    
    try {
        await axios.delete(`/api/resume-sections/${id}`);
        await fetchResumeSections();
        
        // Show success message
        router.visit(window.location.pathname, {
            preserveScroll: true,
            data: {
                success: 'Resume section deleted successfully!'
            }
        });
        
    } catch (error) {
        console.error('Error deleting resume section:', error);
        
        // Show error message
        let errorMessage = 'Failed to delete resume section. Please try again.';
        
        if (error.response?.data?.message) {
            errorMessage = error.response.data.message;
        }
        
        alert(errorMessage);
    }
};

const updateOrder = async () => {
    if (!Array.isArray(filteredResumeSections.value)) return;
    
    const sectionsWithOrder = filteredResumeSections.value.map((section, index) => ({
        id: section.id,
        order: index
    }));
    
    try {
        // Update order for each section
        for (const section of sectionsWithOrder) {
            await axios.put(`/api/resume-sections/${section.id}`, { order: section.order });
        }
        await fetchResumeSections();
    } catch (error) {
        console.error('Error updating order:', error);
        
        // Show error message
        const errorMessage = error.response?.data?.message || 'Failed to update section order. Please try again.';
        alert(errorMessage);
        
        // Revert local changes on error
        await fetchResumeSections();
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

/* Tab overflow scrolling */
nav.flex {
    scrollbar-width: thin;
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

/* Type indicator dots animation */
.h-3.w-3.rounded-full {
    box-shadow: 0 0 0 0 rgba(59, 130, 246, 0.7);
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% {
        box-shadow: 0 0 0 0 rgba(59, 130, 246, 0.7);
    }
    70% {
        box-shadow: 0 0 0 10px rgba(59, 130, 246, 0);
    }
    100% {
        box-shadow: 0 0 0 0 rgba(59, 130, 246, 0);
    }
}
</style>