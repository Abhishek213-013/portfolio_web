<template>
    <AdminLayout>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 space-y-4 sm:space-y-0">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Statistics Management</h2>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Manage your achievement statistics and metrics</p>
                </div>
                <button 
                    @click="showForm = !showForm" 
                    class="px-4 py-2 bg-indigo-600 dark:bg-indigo-500 text-white rounded-lg hover:bg-indigo-700 dark:hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900 transition-colors"
                >
                    {{ showForm ? 'Cancel' : 'Add New Statistic' }}
                </button>
            </div>

            <!-- Add/Edit Statistic Form -->
            <div v-if="showForm" class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-4 sm:p-6 mb-8 border border-gray-200 dark:border-gray-700">
                <form @submit.prevent="saveStatistic" class="space-y-6">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Icon Class</label>
                            <div class="flex flex-col space-y-4">
                                <div class="flex items-center space-x-4">
                                    <input 
                                        v-model="form.icon" 
                                        type="text" 
                                        placeholder="e.g., bi bi-emoji-smile or fas fa-smile"
                                        class="flex-1 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400"
                                        required
                                    />
                                    <div class="shrink-0">
                                        <div :class="[
                                            'h-10 w-10 rounded-full flex items-center justify-center',
                                            getColorClass(form.color, 'bg')
                                        ]">
                                            <!-- Preview Icon -->
                                            <i v-if="form.icon" :class="[form.icon, getColorClass(form.color, 'text')]"></i>
                                            <svg v-else class="h-5 w-5" :class="getColorClass(form.color, 'text')" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-2">
                                    <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Common Icons:</p>
                                    <div class="flex flex-wrap gap-2">
                                        <button 
                                            type="button" 
                                            @click="form.icon = 'bi bi-emoji-smile'"
                                            class="text-xs px-3 py-2 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors flex items-center"
                                        >
                                            <i class="bi bi-emoji-smile mr-1"></i>
                                            Smile
                                        </button>
                                        <button 
                                            type="button" 
                                            @click="form.icon = 'fas fa-users'"
                                            class="text-xs px-3 py-2 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors flex items-center"
                                        >
                                            <i class="fas fa-users mr-1"></i>
                                            Users
                                        </button>
                                        <button 
                                            type="button" 
                                            @click="form.icon = 'bi bi-award'"
                                            class="text-xs px-3 py-2 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors flex items-center"
                                        >
                                            <i class="bi bi-award mr-1"></i>
                                            Award
                                        </button>
                                        <button 
                                            type="button" 
                                            @click="form.icon = 'fas fa-code'"
                                            class="text-xs px-3 py-2 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors flex items-center"
                                        >
                                            <i class="fas fa-code mr-1"></i>
                                            Code
                                        </button>
                                        <button 
                                            type="button" 
                                            @click="form.icon = 'bi bi-clock'"
                                            class="text-xs px-3 py-2 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors flex items-center"
                                        >
                                            <i class="bi bi-clock mr-1"></i>
                                            Clock
                                        </button>
                                        <button 
                                            type="button" 
                                            @click="form.icon = 'fas fa-project-diagram'"
                                            class="text-xs px-3 py-2 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors flex items-center"
                                        >
                                            <i class="fas fa-project-diagram mr-1"></i>
                                            Project
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                Use Bootstrap Icons (bi-*) or Font Awesome (fas-*) class names.
                                <span v-if="!hasIconLibrary" class="text-amber-600 dark:text-amber-400 font-medium">
                                    Note: Icon libraries need to be loaded in your layout file.
                                </span>
                            </p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Label</label>
                            <input 
                                v-model="form.label" 
                                type="text" 
                                placeholder="e.g., Happy Clients, Projects Completed"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400"
                                required
                            />
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">The text displayed below the statistic value</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Value</label>
                            <div class="relative">
                                <input 
                                    v-model="form.value" 
                                    type="number" 
                                    min="0"
                                    step="1"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                                    required
                                />
                                <div class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400">
                                    <span v-if="form.value > 999" class="text-sm">+</span>
                                </div>
                            </div>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">The numeric value to display</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Color Theme</label>
                            <div class="grid grid-cols-6 gap-2 mt-2">
                                <label 
                                    v-for="colorOption in colorOptions" 
                                    :key="colorOption.value"
                                    :class="[
                                        'relative flex flex-col items-center justify-center p-3 rounded-lg cursor-pointer border-2',
                                        form.color === colorOption.value 
                                            ? 'border-indigo-500 dark:border-indigo-400' 
                                            : 'border-gray-200 dark:border-gray-700 hover:border-gray-300 dark:hover:border-gray-600'
                                    ]"
                                >
                                    <input 
                                        type="radio" 
                                        v-model="form.color" 
                                        :value="colorOption.value" 
                                        class="sr-only"
                                    />
                                    <div :class="['h-6 w-6 rounded-full mb-1', colorOption.bgColor]"></div>
                                    <span class="text-xs text-gray-700 dark:text-gray-300">{{ colorOption.label }}</span>
                                </label>
                            </div>
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
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Lower numbers appear first. Drag & drop to reorder.</p>
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
                            <span v-else>{{ editingId ? 'Update Statistic' : 'Add Statistic' }}</span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Loading State -->
            <div v-if="initialLoading && statisticsList.length === 0" class="text-center py-12">
                <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-600 dark:border-indigo-400"></div>
                <p class="mt-4 text-sm text-gray-600 dark:text-gray-400">Loading statistics...</p>
            </div>

            <!-- Statistics Grid -->
            <div v-else-if="!initialLoading || statisticsList.length > 0" class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden border border-gray-200 dark:border-gray-700">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Statistics List</h3>
                        <div class="flex items-center space-x-4">
                            <span class="px-3 py-1 text-xs font-medium rounded-full bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200">
                                {{ statisticsList.length }} statistic{{ statisticsList.length !== 1 ? 's' : '' }}
                            </span>
                            <span class="text-sm text-gray-500 dark:text-gray-400">Drag cards to reorder</span>
                        </div>
                    </div>
                </div>
                
                <div class="p-6">
                    <!-- Simple grid without draggable for now -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        <div 
                            v-for="(stat, index) in statisticsList" 
                            :key="stat.id"
                            :class="[
                                'group relative border rounded-xl p-6 text-center transition-all duration-300 hover:shadow-xl',
                                stat.is_active 
                                    ? 'border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900' 
                                    : 'border-gray-100 dark:border-gray-800 bg-gray-50 dark:bg-gray-800/50 opacity-75'
                            ]"
                        >
                            <!-- Drag Handle -->
                            <div class="drag-handle absolute top-3 left-3 cursor-move text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"></path>
                                </svg>
                            </div>
                            
                            <!-- Reorder Buttons -->
                            <div class="absolute left-3 top-10 flex flex-col space-y-2">
                                <button 
                                    v-if="index > 0"
                                    @click="moveStatisticUp(index)"
                                    class="p-1 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded"
                                    title="Move up"
                                >
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                    </svg>
                                </button>
                                <button 
                                    v-if="index < statisticsList.length - 1"
                                    @click="moveStatisticDown(index)"
                                    class="p-1 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded"
                                    title="Move down"
                                >
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>
                            </div>
                            
                            <!-- Action Buttons -->
                            <div class="absolute top-3 right-3 flex space-x-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                <button 
                                    @click="editStatistic(stat)"
                                    class="p-1.5 text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-300 hover:bg-blue-50 dark:hover:bg-blue-900/30 rounded-md transition-colors"
                                    title="Edit statistic"
                                >
                                    <!-- Pencil Icon -->
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </button>
                                <button 
                                    @click="deleteStatistic(stat.id)"
                                    class="p-1.5 text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300 hover:bg-red-50 dark:hover:bg-red-900/30 rounded-md transition-colors"
                                    title="Delete statistic"
                                >
                                    <!-- Trash Icon -->
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </div>
                            
                            <!-- Statistic Content -->
                            <div class="mb-4">
                                <div :class="[
                                    'inline-flex items-center justify-center h-16 w-16 rounded-full mb-4',
                                    getColorClass(stat.color, 'bg')
                                ]">
                                    <!-- Use actual icon class from database -->
                                    <i v-if="stat.icon" :class="[stat.icon, getColorClass(stat.color, 'text'), 'text-2xl']"></i>
                                    <!-- Fallback SVG if no icon class or icon library not loaded -->
                                    <svg 
                                        v-else 
                                        class="h-8 w-8" 
                                        :class="getColorClass(stat.color, 'text')" 
                                        fill="none" 
                                        stroke="currentColor" 
                                        viewBox="0 0 24 24"
                                    >
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                    </svg>
                                </div>
                                <div :class="[
                                    'text-4xl font-bold mb-2',
                                    getColorClass(stat.color, 'text')
                                ]">
                                    {{ formatNumber(stat.value) }}
                                </div>
                                <div class="text-gray-700 dark:text-gray-300 font-medium">{{ stat.label }}</div>
                            </div>
                            
                            <!-- Footer Info -->
                            <div class="mt-6 pt-4 border-t border-gray-100 dark:border-gray-700 flex justify-between items-center text-xs">
                                <span :class="[
                                    'px-2 py-1 rounded-full font-medium',
                                    stat.is_active 
                                        ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' 
                                        : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200'
                                ]">
                                    {{ stat.is_active ? 'Active' : 'Inactive' }}
                                </span>
                                <div class="text-gray-500 dark:text-gray-400 flex items-center">
                                    <svg class="h-3 w-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path>
                                    </svg>
                                    Order: {{ stat.order }}
                                </div>
                            </div>
                            
                            <!-- Color Indicator -->
                            <div class="absolute bottom-0 left-0 right-0 h-1 rounded-b-xl" :class="getColorClass(stat.color, 'bg')"></div>
                        </div>
                    </div>
                    
                    <!-- Empty State -->
                    <div v-if="!statisticsList || statisticsList.length === 0" class="text-center py-16 border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-xl">
                        <div class="mx-auto h-16 w-16 text-gray-400 dark:text-gray-500 mb-4">
                            <svg class="h-16 w-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No Statistics Added</h3>
                        <p class="text-gray-600 dark:text-gray-400 mb-6 max-w-md mx-auto">
                            Statistics showcase your achievements and metrics. Add your first statistic to display important numbers.
                        </p>
                        <button 
                            @click="showForm = true" 
                            class="px-6 py-3 bg-indigo-600 dark:bg-indigo-500 text-white font-medium rounded-lg hover:bg-indigo-700 dark:hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition-colors"
                        >
                            <svg class="h-5 w-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Add Your First Statistic
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Stats Summary -->
            <div v-if="!initialLoading" class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center">
                        <div class="h-12 w-12 bg-indigo-100 dark:bg-indigo-900 rounded-lg flex items-center justify-center mr-4">
                            <svg class="h-6 w-6 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Statistics</p>
                            <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ statisticsList.length }}</p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center">
                        <div class="h-12 w-12 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center mr-4">
                            <svg class="h-6 w-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Active Statistics</p>
                            <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ activeStatsCount }}</p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center">
                        <div class="h-12 w-12 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center mr-4">
                            <svg class="h-6 w-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Value</p>
                            <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ formatNumber(totalValue) }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { ref, reactive, computed, onMounted, watch } from 'vue';
import { router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const showForm = ref(false);
const loading = ref(false);
const initialLoading = ref(true);
const editingId = ref(null);
const statisticsList = ref([]);
const hasIconLibrary = ref(false); // Track if icon libraries are loaded

const form = reactive({
    icon: '',
    label: '',
    value: 0,
    color: 'indigo',
    order: 0,
    is_active: true
});

// Color options with proper Tailwind classes
const colorOptions = [
    { value: 'blue', label: 'Blue', bgColor: 'bg-blue-500' },
    { value: 'green', label: 'Green', bgColor: 'bg-green-500' },
    { value: 'yellow', label: 'Yellow', bgColor: 'bg-yellow-500' },
    { value: 'red', label: 'Red', bgColor: 'bg-red-500' },
    { value: 'purple', label: 'Purple', bgColor: 'bg-purple-500' },
    { value: 'indigo', label: 'Indigo', bgColor: 'bg-indigo-500' },
];

// Check if icon libraries are loaded
const checkIconLibraries = () => {
    // Check for Font Awesome
    const hasFontAwesome = typeof window.FontAwesome !== 'undefined' || 
                          document.querySelector('link[href*="font-awesome"]') || 
                          document.querySelector('link[href*="fontawesome"]');
    
    // Check for Bootstrap Icons
    const hasBootstrapIcons = document.querySelector('link[href*="bootstrap-icons"]');
    
    hasIconLibrary.value = hasFontAwesome || hasBootstrapIcons;
    console.log('Icon libraries loaded:', hasIconLibrary.value);
};

// Safeguard computed properties with fallbacks
const activeStatsCount = computed(() => {
    if (!Array.isArray(statisticsList.value)) return 0;
    return statisticsList.value.filter(stat => stat && stat.is_active).length;
});

const totalValue = computed(() => {
    if (!Array.isArray(statisticsList.value)) return 0;
    return statisticsList.value.reduce((sum, stat) => {
        if (stat && stat.value) {
            return sum + Number(stat.value);
        }
        return sum;
    }, 0);
});

onMounted(() => {
    console.log('Component mounted, fetching statistics...');
    checkIconLibraries();
    fetchStatistics();
});

// Watch for changes
watch(statisticsList, (newValue) => {
    console.log('statisticsList changed:', newValue);
}, { deep: true });

const fetchStatistics = async () => {
    try {
        initialLoading.value = true;
        const response = await axios.get('/api/statistics');
        
        // Handle different response formats
        let statsData = [];
        
        console.log('API Response:', response.data);
        
        // If response.data is already an array
        if (Array.isArray(response.data)) {
            statsData = response.data;
        } 
        // If response has data property with array
        else if (response.data && Array.isArray(response.data.data)) {
            statsData = response.data.data;
        }
        // If response.data is an object
        else if (response.data && typeof response.data === 'object') {
            // Check if it's Laravel Resource response
            if (response.data.data && Array.isArray(response.data.data)) {
                statsData = response.data.data;
            } else {
                // Fallback: convert object to array
                statsData = Object.values(response.data);
            }
        }
        
        // Ensure all statistics have required properties
        statsData = statsData.map(stat => ({
            id: stat.id || 0,
            icon: stat.icon || '',
            label: stat.label || '',
            value: stat.value || 0,
            color: stat.color || 'indigo',
            order: stat.order || 0,
            is_active: stat.is_active !== undefined ? stat.is_active : true
        }))
        .sort((a, b) => (a.order || 0) - (b.order || 0));
        
        statisticsList.value = statsData;
        
        console.log('Processed statistics:', statisticsList.value);
        
    } catch (error) {
        console.error('Error fetching statistics:', error);
        statisticsList.value = [];
        
        // Show error to user
        alert('Failed to load statistics. Please try again.');
    } finally {
        initialLoading.value = false;
    }
};

// Helper function to get color classes
const getColorClass = (color, type = 'text') => {
    const colorMap = {
        blue: {
            text: 'text-blue-600 dark:text-blue-400',
            bg: 'bg-blue-100 dark:bg-blue-900',
            bgLight: 'bg-blue-50 dark:bg-blue-900/50'
        },
        green: {
            text: 'text-green-600 dark:text-green-400',
            bg: 'bg-green-100 dark:bg-green-900',
            bgLight: 'bg-green-50 dark:bg-green-900/50'
        },
        yellow: {
            text: 'text-yellow-600 dark:text-yellow-400',
            bg: 'bg-yellow-100 dark:bg-yellow-900',
            bgLight: 'bg-yellow-50 dark:bg-yellow-900/50'
        },
        red: {
            text: 'text-red-600 dark:text-red-400',
            bg: 'bg-red-100 dark:bg-red-900',
            bgLight: 'bg-red-50 dark:bg-red-900/50'
        },
        purple: {
            text: 'text-purple-600 dark:text-purple-400',
            bg: 'bg-purple-100 dark:bg-purple-900',
            bgLight: 'bg-purple-50 dark:bg-purple-900/50'
        },
        indigo: {
            text: 'text-indigo-600 dark:text-indigo-400',
            bg: 'bg-indigo-100 dark:bg-indigo-900',
            bgLight: 'bg-indigo-50 dark:bg-indigo-900/50'
        }
    };
    
    return colorMap[color]?.[type] || colorMap.indigo[type];
};

// Format large numbers with commas
const formatNumber = (num) => {
    return Number(num).toLocaleString('en-US');
};

const editStatistic = (stat) => {
    editingId.value = stat.id;
    Object.assign(form, {
        icon: stat.icon,
        label: stat.label,
        value: stat.value,
        color: stat.color || 'indigo',
        order: stat.order,
        is_active: stat.is_active
    });
    showForm.value = true;
    // Scroll to form
    setTimeout(() => {
        const formElement = document.querySelector('.bg-white\\ dark\\:bg-gray-800');
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
        icon: '',
        label: '',
        value: 0,
        color: 'indigo',
        order: 0,
        is_active: true
    });
};

const saveStatistic = async () => {
    loading.value = true;
    
    try {
        let response;
        if (editingId.value) {
            response = await axios.put(`/api/statistics/${editingId.value}`, form);
        } else {
            // Set order to last if not specified
            if (!form.order && statisticsList.value.length > 0) {
                form.order = Math.max(...statisticsList.value.map(s => s.order || 0)) + 1;
            }
            response = await axios.post('/api/statistics', form);
        }

        if (response.status === 200 || response.status === 201) {
            await fetchStatistics();
            cancelEdit();
            
            // Show success message
            const message = editingId.value 
                ? 'Statistic updated successfully!' 
                : 'Statistic added successfully!';
            
            alert(message);
        }
    } catch (error) {
        console.error('Error saving statistic:', error);
        
        // Show error message
        const errorMessage = error.response?.data?.message || 
                           (error.response?.data?.errors ? 
                            Object.values(error.response.data.errors).flat().join(', ') : 
                            'Failed to save statistic. Please try again.');
        
        alert(errorMessage);
    } finally {
        loading.value = false;
    }
};

const deleteStatistic = async (id) => {
    if (!confirm('Are you sure you want to delete this statistic? This action cannot be undone.')) return;
    
    try {
        await axios.delete(`/api/statistics/${id}`);
        await fetchStatistics();
        
        alert('Statistic deleted successfully!');
        
    } catch (error) {
        console.error('Error deleting statistic:', error);
        
        // Show error message
        const errorMessage = error.response?.data?.message || 'Failed to delete statistic. Please try again.';
        alert(errorMessage);
    }
};

// Manual reorder functions
const moveStatisticUp = async (index) => {
    if (index <= 0) return;
    
    const temp = statisticsList.value[index];
    statisticsList.value[index] = statisticsList.value[index - 1];
    statisticsList.value[index - 1] = temp;
    
    // Update order in database
    await updateOrder();
};

const moveStatisticDown = async (index) => {
    if (index >= statisticsList.value.length - 1) return;
    
    const temp = statisticsList.value[index];
    statisticsList.value[index] = statisticsList.value[index + 1];
    statisticsList.value[index + 1] = temp;
    
    // Update order in database
    await updateOrder();
};

const updateOrder = async () => {
    if (!Array.isArray(statisticsList.value)) return;
    
    // Update local order based on position
    const statsWithOrder = statisticsList.value.map((stat, index) => ({
        id: stat.id,
        order: index
    }));
    
    try {
        await axios.post('/api/statistics/order', { statistics: statsWithOrder });
        await fetchStatistics(); // Refresh to get updated order from server
        console.log('Statistic order updated successfully!');
    } catch (error) {
        console.error('Error updating order:', error);
        
        // Show error message
        const errorMessage = error.response?.data?.message || 'Failed to update statistic order. Please try again.';
        alert(errorMessage);
        
        // Revert local changes on error
        await fetchStatistics();
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

/* Card hover effects */
.group:hover .group-hover\:opacity-100 {
    opacity: 1;
}

.hover\:shadow-xl:hover {
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    transform: translateY(-2px);
}

/* Color radio button selection */
input[type="radio"]:checked + div {
    border-color: #4f46e5;
}

.dark input[type="radio"]:checked + div {
    border-color: #6366f1;
}

/* Responsive adjustments */
@media (max-width: 640px) {
    .grid-cols-6 {
        grid-template-columns: repeat(3, minmax(0, 1fr));
    }
    
    .lg\:grid-cols-4 {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
}

@media (max-width: 480px) {
    .grid-cols-6 {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
    
    .lg\:grid-cols-4 {
        grid-template-columns: 1fr;
    }
    
    .md\:grid-cols-3 {
        grid-template-columns: 1fr;
    }
}

/* Number formatting animation */
@keyframes countUp {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.text-4xl {
    animation: countUp 0.5s ease-out;
}

/* Button focus states */
button:focus {
    outline: none;
    ring-width: 2px;
    ring-offset-width: 2px;
}

/* Color swatch hover effect */
.bg-blue-500, .bg-green-500, .bg-yellow-500, .bg-red-500, .bg-purple-500, .bg-indigo-500 {
    transition: transform 0.2s ease;
}

label:hover .bg-blue-500,
label:hover .bg-green-500,
label:hover .bg-yellow-500,
label:hover .bg-red-500,
label:hover .bg-purple-500,
label:hover .bg-indigo-500 {
    transform: scale(1.1);
}

/* Drag handle styling */
.drag-handle {
    cursor: grab;
}

.drag-handle:active {
    cursor: grabbing;
}

/* Icon styling for better visibility */
i[class^="bi-"], i[class^="fas-"], i[class^="fa-"] {
    font-size: 1.75rem;
    line-height: 1;
}

/* Ensure icons display properly */
i {
    display: inline-block;
    font-style: normal;
    font-variant: normal;
    text-rendering: auto;
    line-height: 1;
}
</style>