<template>
    <AdminLayout>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 space-y-4 sm:space-y-0">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Skills Management</h2>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Manage your technical skills and proficiency levels</p>
                </div>
                <button 
                    @click="showForm = !showForm" 
                    class="px-4 py-2 bg-indigo-600 dark:bg-indigo-500 text-white rounded-lg hover:bg-indigo-700 dark:hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900 transition-colors"
                >
                    {{ showForm ? 'Cancel' : 'Add New Skill' }}
                </button>
            </div>

            <!-- Add/Edit Skill Form -->
            <div v-if="showForm" class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-4 sm:p-6 mb-8 border border-gray-200 dark:border-gray-700">
                <form @submit.prevent="saveSkill" class="space-y-6">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Skill Name</label>
                            <input 
                                v-model="form.name" 
                                type="text" 
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400"
                                required
                                placeholder="Enter skill name (e.g., JavaScript)"
                            />
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Proficiency: <span class="font-bold text-indigo-600 dark:text-indigo-400">{{ form.percentage }}%</span>
                            </label>
                            <div class="flex items-center space-x-4">
                                <input 
                                    v-model="form.percentage" 
                                    type="range" 
                                    min="0" 
                                    max="100" 
                                    step="5"
                                    class="flex-1 h-2 bg-gray-200 dark:bg-gray-700 rounded-lg appearance-none cursor-pointer [&::-webkit-slider-thumb]:appearance-none [&::-webkit-slider-thumb]:h-4 [&::-webkit-slider-thumb]:w-4 [&::-webkit-slider-thumb]:rounded-full [&::-webkit-slider-thumb]:bg-indigo-600 dark:[&::-webkit-slider-thumb]:bg-indigo-500"
                                />
                                <div class="w-20 text-center">
                                    <span class="text-lg font-semibold text-gray-900 dark:text-white">{{ form.percentage }}%</span>
                                </div>
                            </div>
                            <div class="flex justify-between text-xs text-gray-500 dark:text-gray-400 mt-1">
                                <span>Beginner</span>
                                <span>Intermediate</span>
                                <span>Expert</span>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Column</label>
                            <select 
                                v-model="form.column" 
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                            >
                                <option value="left" class="text-gray-900 dark:text-white">Left Column</option>
                                <option value="right" class="text-gray-900 dark:text-white">Right Column</option>
                            </select>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Choose which column to display this skill in</p>
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
                            <span v-else>{{ editingId ? 'Update Skill' : 'Add Skill' }}</span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Loading State -->
            <div v-if="initialLoading && skills.length === 0" class="text-center py-12">
                <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-600 dark:border-indigo-400"></div>
                <p class="mt-4 text-sm text-gray-600 dark:text-gray-400">Loading skills...</p>
            </div>

            <!-- Skills Columns -->
            <div v-else-if="!initialLoading || skills.length > 0" class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Left Column Skills -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden border border-gray-200 dark:border-gray-700">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Left Column Skills</h3>
                            <span class="px-3 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                {{ leftSkills.length }} skill{{ leftSkills.length !== 1 ? 's' : '' }}
                            </span>
                        </div>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Drag to reorder skills in this column</p>
                    </div>
                    <div class="p-6">
                        <draggable 
                            v-model="leftSkills" 
                            @end="updateOrder"
                            item-key="id"
                            class="space-y-4"
                            handle=".drag-handle"
                            v-if="Array.isArray(leftSkills)"
                        >
                            <template #item="{ element: skill }">
                                <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 hover:shadow-md transition-all duration-300 bg-white dark:bg-gray-900">
                                    <div class="flex items-start space-x-3">
                                        <div class="drag-handle cursor-move pt-1 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"></path>
                                            </svg>
                                        </div>
                                        <div class="flex-1">
                                            <div class="flex justify-between items-start mb-3">
                                                <div>
                                                    <h4 class="font-medium text-gray-900 dark:text-white">{{ skill.name }}</h4>
                                                    <div class="flex items-center space-x-2 mt-1">
                                                        <span :class="[
                                                            'px-2 py-1 text-xs font-medium rounded-full',
                                                            skill.is_active 
                                                                ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' 
                                                                : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200'
                                                        ]">
                                                            {{ skill.is_active ? 'Active' : 'Inactive' }}
                                                        </span>
                                                        <span class="text-xs text-gray-500 dark:text-gray-400">
                                                            Order: {{ skill.order }}
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="flex space-x-1">
                                                    <button 
                                                        @click="editSkill(skill)"
                                                        class="p-1.5 text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-300 hover:bg-blue-50 dark:hover:bg-blue-900/30 rounded-md transition-colors"
                                                        title="Edit skill"
                                                    >
                                                        <PencilIcon class="h-4 w-4" />
                                                    </button>
                                                    <button 
                                                        @click="deleteSkill(skill.id)"
                                                        class="p-1.5 text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300 hover:bg-red-50 dark:hover:bg-red-900/30 rounded-md transition-colors"
                                                        title="Delete skill"
                                                    >
                                                        <TrashIcon class="h-4 w-4" />
                                                    </button>
                                                </div>
                                            </div>
                                            
                                            <div class="space-y-2">
                                                <div class="flex items-center justify-between">
                                                    <span class="text-sm text-gray-600 dark:text-gray-400">Proficiency</span>
                                                    <span class="text-sm font-medium text-gray-900 dark:text-white">{{ skill.percentage }}%</span>
                                                </div>
                                                <div class="h-3 bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden">
                                                    <div 
                                                        class="h-full bg-gradient-to-r from-indigo-500 to-purple-500 dark:from-indigo-400 dark:to-purple-400 rounded-full transition-all duration-500"
                                                        :style="{ width: skill.percentage + '%' }"
                                                    ></div>
                                                </div>
                                                <div class="flex justify-between text-xs text-gray-500 dark:text-gray-400">
                                                    <span>0%</span>
                                                    <span>50%</span>
                                                    <span>100%</span>
                                                </div>
                                            </div>
                                            
                                            <div class="mt-3 text-xs text-gray-500 dark:text-gray-400">
                                                <span class="inline-flex items-center">
                                                    <svg class="h-3 w-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                    </svg>
                                                    Drag handle to reorder
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </draggable>
                        
                        <div v-if="!leftSkills || leftSkills.length === 0" class="text-center py-10">
                            <div class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500 mb-4">
                                <svg class="h-12 w-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                                </svg>
                            </div>
                            <h3 class="text-sm font-medium text-gray-900 dark:text-white mb-1">No skills in left column</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Add skills to display in the left column</p>
                        </div>
                    </div>
                </div>

                <!-- Right Column Skills -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden border border-gray-200 dark:border-gray-700">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Right Column Skills</h3>
                            <span class="px-3 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                {{ rightSkills.length }} skill{{ rightSkills.length !== 1 ? 's' : '' }}
                            </span>
                        </div>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Drag to reorder skills in this column</p>
                    </div>
                    <div class="p-6">
                        <draggable 
                            v-model="rightSkills" 
                            @end="updateOrder"
                            item-key="id"
                            class="space-y-4"
                            handle=".drag-handle"
                            v-if="Array.isArray(rightSkills)"
                        >
                            <template #item="{ element: skill }">
                                <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 hover:shadow-md transition-all duration-300 bg-white dark:bg-gray-900">
                                    <div class="flex items-start space-x-3">
                                        <div class="drag-handle cursor-move pt-1 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"></path>
                                            </svg>
                                        </div>
                                        <div class="flex-1">
                                            <div class="flex justify-between items-start mb-3">
                                                <div>
                                                    <h4 class="font-medium text-gray-900 dark:text-white">{{ skill.name }}</h4>
                                                    <div class="flex items-center space-x-2 mt-1">
                                                        <span :class="[
                                                            'px-2 py-1 text-xs font-medium rounded-full',
                                                            skill.is_active 
                                                                ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' 
                                                                : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200'
                                                        ]">
                                                            {{ skill.is_active ? 'Active' : 'Inactive' }}
                                                        </span>
                                                        <span class="text-xs text-gray-500 dark:text-gray-400">
                                                            Order: {{ skill.order }}
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="flex space-x-1">
                                                    <button 
                                                        @click="editSkill(skill)"
                                                        class="p-1.5 text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-300 hover:bg-blue-50 dark:hover:bg-blue-900/30 rounded-md transition-colors"
                                                        title="Edit skill"
                                                    >
                                                        <PencilIcon class="h-4 w-4" />
                                                    </button>
                                                    <button 
                                                        @click="deleteSkill(skill.id)"
                                                        class="p-1.5 text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300 hover:bg-red-50 dark:hover:bg-red-900/30 rounded-md transition-colors"
                                                        title="Delete skill"
                                                    >
                                                        <TrashIcon class="h-4 w-4" />
                                                    </button>
                                                </div>
                                            </div>
                                            
                                            <div class="space-y-2">
                                                <div class="flex items-center justify-between">
                                                    <span class="text-sm text-gray-600 dark:text-gray-400">Proficiency</span>
                                                    <span class="text-sm font-medium text-gray-900 dark:text-white">{{ skill.percentage }}%</span>
                                                </div>
                                                <div class="h-3 bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden">
                                                    <div 
                                                        class="h-full bg-gradient-to-r from-green-500 to-teal-500 dark:from-green-400 dark:to-teal-400 rounded-full transition-all duration-500"
                                                        :style="{ width: skill.percentage + '%' }"
                                                    ></div>
                                                </div>
                                                <div class="flex justify-between text-xs text-gray-500 dark:text-gray-400">
                                                    <span>0%</span>
                                                    <span>50%</span>
                                                    <span>100%</span>
                                                </div>
                                            </div>
                                            
                                            <div class="mt-3 text-xs text-gray-500 dark:text-gray-400">
                                                <span class="inline-flex items-center">
                                                    <svg class="h-3 w-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                    </svg>
                                                    Drag handle to reorder
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </draggable>
                        
                        <div v-if="!rightSkills || rightSkills.length === 0" class="text-center py-10">
                            <div class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500 mb-4">
                                <svg class="h-12 w-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                                </svg>
                            </div>
                            <h3 class="text-sm font-medium text-gray-900 dark:text-white mb-1">No skills in right column</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Add skills to display in the right column</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Stats Summary -->
            <div v-if="!initialLoading" class="mt-8 grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center">
                        <div class="h-10 w-10 bg-indigo-100 dark:bg-indigo-900 rounded-lg flex items-center justify-center mr-4">
                            <svg class="h-5 w-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Skills</p>
                            <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ skills.length }}</p>
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
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Left Column</p>
                            <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ leftSkills.length }}</p>
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
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Right Column</p>
                            <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ rightSkills.length }}</p>
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
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Active Skills</p>
                            <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ activeSkillsCount }}</p>
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
import { PencilIcon, TrashIcon } from '@heroicons/vue/24/outline';

const showForm = ref(false);
const loading = ref(false);
const initialLoading = ref(true);
const editingId = ref(null);
const skills = ref([]);
const form = reactive({
    name: '',
    percentage: 50,
    column: 'left',
    order: 0,
    is_active: true
});

// Notification state
const notification = reactive({
    show: false,
    message: '',
    type: 'success'
});

// Safeguard computed properties with fallbacks
const leftSkills = computed(() => {
    if (!Array.isArray(skills.value)) return [];
    return skills.value
        .filter(skill => skill && skill.column === 'left')
        .sort((a, b) => (a.order || 0) - (b.order || 0));
});

const rightSkills = computed(() => {
    if (!Array.isArray(skills.value)) return [];
    return skills.value
        .filter(skill => skill && skill.column === 'right')
        .sort((a, b) => (a.order || 0) - (b.order || 0));
});

const activeSkillsCount = computed(() => {
    if (!Array.isArray(skills.value)) return 0;
    return skills.value.filter(skill => skill && skill.is_active).length;
});

onMounted(() => {
    fetchSkills();
});

// Notification function
const showNotification = (message, type = 'success') => {
    notification.show = true;
    notification.message = message;
    notification.type = type;
    
    // Auto hide after 3 seconds
    setTimeout(() => {
        notification.show = false;
    }, 3000);
};

const fetchSkills = async () => {
    try {
        initialLoading.value = true;
        const response = await axios.get('/api/skills');
        
        // Handle different response formats
        let skillsData = [];
        
        if (Array.isArray(response.data)) {
            skillsData = response.data;
        } else if (response.data && Array.isArray(response.data.data)) {
            skillsData = response.data.data;
        } else if (response.data && typeof response.data === 'object') {
            // Convert object values to array if needed
            skillsData = Object.values(response.data);
        }
        
        // Ensure all skills have required properties
        skillsData = skillsData.map(skill => ({
            id: skill.id || 0,
            name: skill.name || '',
            percentage: skill.percentage || 0,
            column: skill.column || 'left',
            order: skill.order || 0,
            is_active: skill.is_active !== undefined ? skill.is_active : true
        }));
        
        skills.value = skillsData;
        
    } catch (error) {
        console.error('Error fetching skills:', error);
        skills.value = [];
        showNotification('Failed to load skills. Please try again.', 'error');
    } finally {
        initialLoading.value = false;
    }
};

const editSkill = (skill) => {
    editingId.value = skill.id;
    Object.assign(form, {
        name: skill.name,
        percentage: skill.percentage,
        column: skill.column,
        order: skill.order,
        is_active: skill.is_active
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
        name: '',
        percentage: 50,
        column: 'left',
        order: 0,
        is_active: true
    });
};

const saveSkill = async () => {
    loading.value = true;
    
    try {
        let response;
        if (editingId.value) {
            response = await axios.put(`/api/skills/${editingId.value}`, form);
        } else {
            response = await axios.post('/api/skills', form);
        }

        if (response.status === 200 || response.status === 201) {
            await fetchSkills();
            cancelEdit();
            
            // Show success notification
            showNotification(
                editingId.value ? 'Skill updated successfully!' : 'Skill added successfully!',
                'success'
            );
        }
    } catch (error) {
        console.error('Error saving skill:', error);
        
        // Show error message - handle different error formats
        let errorMessage = 'Failed to save skill. Please try again.';
        
        if (error.response?.data?.message) {
            errorMessage = error.response.data.message;
        } else if (error.response?.data?.errors) {
            // Handle Laravel validation errors
            const errors = Object.values(error.response.data.errors).flat();
            errorMessage = errors.join(', ');
        } else if (error.message) {
            errorMessage = error.message;
        }
        
        showNotification(errorMessage, 'error');
    } finally {
        loading.value = false;
    }
};

const deleteSkill = async (id) => {
    if (!confirm('Are you sure you want to delete this skill? This action cannot be undone.')) return;
    
    try {
        await axios.delete(`/api/skills/${id}`);
        await fetchSkills();
        
        // Show success notification
        showNotification('Skill deleted successfully!', 'success');
        
    } catch (error) {
        console.error('Error deleting skill:', error);
        
        // Show error message
        const errorMessage = error.response?.data?.message || 'Failed to delete skill. Please try again.';
        showNotification(errorMessage, 'error');
    }
};

const updateOrder = async () => {
    const allSkills = [...leftSkills.value, ...rightSkills.value];
    const skillsWithOrder = allSkills.map((skill, index) => ({
        id: skill.id,
        order: index,
        column: skill.column
    }));
    
    try {
        await axios.post('/api/skills/order', { skills: skillsWithOrder });
        await fetchSkills();
        
        // Show success notification
        showNotification('Skills order updated successfully!', 'success');
    } catch (error) {
        console.error('Error updating order:', error);
        
        // Show error message
        const errorMessage = error.response?.data?.message || 'Failed to update skill order. Please try again.';
        showNotification(errorMessage, 'error');
        
        // Revert local changes on error
        await fetchSkills();
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

/* Custom range slider styles */
input[type="range"]::-webkit-slider-thumb {
    -webkit-appearance: none;
    appearance: none;
    height: 1rem;
    width: 1rem;
    border-radius: 50%;
    background: #4f46e5;
    cursor: pointer;
    border: 2px solid white;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.dark input[type="range"]::-webkit-slider-thumb {
    background: #6366f1;
    border-color: #1f2937;
}

input[type="range"]::-moz-range-thumb {
    height: 1rem;
    width: 1rem;
    border-radius: 50%;
    background: #4f46e5;
    cursor: pointer;
    border: 2px solid white;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.dark input[type="range"]::-moz-range-thumb {
    background: #6366f1;
    border-color: #1f2937;
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

/* Progress bar animation */
.bg-gradient-to-r {
    transition: width 1s cubic-bezier(0.4, 0, 0.2, 1);
}

/* Empty state styling */
.text-center.py-10 {
    border: 2px dashed #e5e7eb;
    border-radius: 0.5rem;
}

.dark .text-center.py-10 {
    border-color: #374151;
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

/* Notification styles */
.notification {
    position: fixed;
    top: 1rem;
    right: 1rem;
    padding: 1rem;
    border-radius: 0.5rem;
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    z-index: 50;
    animation: slideIn 0.3s ease-out;
}

.notification.success {
    background-color: #10b981;
    color: white;
}

.notification.error {
    background-color: #ef4444;
    color: white;
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

