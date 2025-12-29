<template>
    <AdminLayout>
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 space-y-4 sm:space-y-0">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Site Settings</h2>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Configure your website's basic information, footer, and social links</p>
                </div>
            </div>

            <!-- Success/Error Messages -->
            <div v-if="message" :class="[
                'mb-6 p-4 rounded-lg border',
                messageType === 'success' 
                    ? 'bg-green-50 dark:bg-green-900/20 border-green-200 dark:border-green-800' 
                    : 'bg-red-50 dark:bg-red-900/20 border-red-200 dark:border-red-800'
            ]">
                <div class="flex items-center">
                    <svg :class="[
                        'h-5 w-5 mr-3',
                        messageType === 'success' 
                            ? 'text-green-400 dark:text-green-300' 
                            : 'text-red-400 dark:text-red-300'
                    ]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path v-if="messageType === 'success'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span :class="[
                        'text-sm font-medium',
                        messageType === 'success' 
                            ? 'text-green-800 dark:text-green-300' 
                            : 'text-red-800 dark:text-red-300'
                    ]">{{ message }}</span>
                </div>
            </div>

            <!-- Site Settings Form -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-4 sm:p-6 border border-gray-200 dark:border-gray-700">
                <form @submit.prevent="saveSettings" class="space-y-8">
                    <!-- Basic Information -->
                    <div>
                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Basic Information</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Set up your website's name and description</p>
                        </div>
                        <div class="space-y-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Site Name
                                    <span class="text-red-500 ml-1">*</span>
                                </label>
                                <input 
                                    v-model="form.site_name" 
                                    type="text" 
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400"
                                    required
                                    placeholder="Enter your website name"
                                />
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">This will appear in browser tabs and search results</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Site Description
                                    <span class="text-red-500 ml-1">*</span>
                                </label>
                                <textarea 
                                    v-model="form.site_description" 
                                    rows="3"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400"
                                    required
                                    placeholder="Brief description of your website"
                                ></textarea>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">A brief summary of your website for search engines</p>
                            </div>
                        </div>
                    </div>

                    <!-- Copyright Information -->
                    <div class="pt-6 border-t border-gray-200 dark:border-gray-700">
                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Copyright Information</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Configure copyright text displayed in the footer</p>
                        </div>
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Copyright Name
                                    <span class="text-red-500 ml-1">*</span>
                                </label>
                                <input 
                                    v-model="form.copyright_name" 
                                    type="text" 
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400"
                                    required
                                    placeholder="Your name or company"
                                />
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Name that appears in copyright notice</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Copyright Text
                                    <span class="text-red-500 ml-1">*</span>
                                </label>
                                <input 
                                    v-model="form.copyright_text" 
                                    type="text" 
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400"
                                    required
                                    placeholder="e.g., All rights reserved"
                                />
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Additional copyright information</p>
                            </div>
                        </div>
                    </div>

                    <!-- Footer Credits -->
                    <div class="pt-6 border-t border-gray-200 dark:border-gray-700">
                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Footer Credits</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Credit the designer and template distributor</p>
                        </div>
                        <div class="space-y-6">
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Designer Name
                                        <span class="text-red-500 ml-1">*</span>
                                    </label>
                                    <input 
                                        v-model="form.designer_name" 
                                        type="text" 
                                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400"
                                        required
                                        placeholder="Designer or agency name"
                                    />
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Designer URL
                                        <span class="text-red-500 ml-1">*</span>
                                    </label>
                                    <input 
                                        v-model="form.designer_url" 
                                        type="url" 
                                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400"
                                        required
                                        placeholder="https://example.com"
                                    />
                                </div>
                            </div>
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Distributor Name
                                        <span class="text-red-500 ml-1">*</span>
                                    </label>
                                    <input 
                                        v-model="form.distributor_name" 
                                        type="text" 
                                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400"
                                        required
                                        placeholder="Template distributor name"
                                    />
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Distributor URL
                                        <span class="text-red-500 ml-1">*</span>
                                    </label>
                                    <input 
                                        v-model="form.distributor_url" 
                                        type="url" 
                                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400"
                                        required
                                        placeholder="https://example.com"
                                    />
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Distributed By Text
                                    <span class="text-red-500 ml-1">*</span>
                                </label>
                                <input 
                                    v-model="form.distributed_by_text" 
                                    type="text" 
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400"
                                    required
                                    placeholder="e.g., Distributed by"
                                />
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Text that appears before the distributor credit</p>
                            </div>
                        </div>
                    </div>

                    <!-- Footer Social Links -->
                    <div class="pt-6 border-t border-gray-200 dark:border-gray-700">
                        <div class="mb-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Footer Social Links</h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Add social media links to display in the footer</p>
                                </div>
                                <button 
                                    type="button" 
                                    @click="addSocialLink"
                                    class="px-4 py-2 bg-indigo-100 dark:bg-indigo-900 text-indigo-700 dark:text-indigo-300 rounded-md hover:bg-indigo-200 dark:hover:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 transition-colors text-sm"
                                >
                                    + Add Social Link
                                </button>
                            </div>
                        </div>
                        
                        <div v-if="form.footer_social_links && form.footer_social_links.length > 0" class="space-y-4">
                            <div v-for="(link, index) in form.footer_social_links" :key="index" 
                                 class="grid grid-cols-1 lg:grid-cols-12 gap-3 p-4 bg-gray-50 dark:bg-gray-900 rounded-lg border border-gray-200 dark:border-gray-700">
                                <div class="lg:col-span-4">
                                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Platform</label>
                                    <input 
                                        v-model="link.platform" 
                                        type="text" 
                                        placeholder="e.g., Twitter, GitHub"
                                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:border-transparent bg-white dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 text-sm"
                                    />
                                </div>
                                <div class="lg:col-span-5">
                                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">URL</label>
                                    <input 
                                        v-model="link.url" 
                                        type="url" 
                                        placeholder="https://example.com/username"
                                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:border-transparent bg-white dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 text-sm"
                                    />
                                </div>
                                <div class="lg:col-span-2">
                                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Icon Class</label>
                                    <input 
                                        v-model="link.icon" 
                                        type="text" 
                                        placeholder="e.g., fab fa-twitter"
                                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:border-transparent bg-white dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 text-sm"
                                    />
                                </div>
                                <div class="lg:col-span-1 flex items-end">
                                    <button 
                                        type="button" 
                                        @click="removeSocialLink(index)"
                                        class="w-full px-3 py-2 bg-red-100 dark:bg-red-900 text-red-600 dark:text-red-300 rounded-md hover:bg-red-200 dark:hover:bg-red-800 focus:outline-none focus:ring-2 focus:ring-red-500 dark:focus:ring-red-400 transition-colors text-sm"
                                        :title="`Remove ${link.platform || 'social link'}`"
                                    >
                                        Remove
                                    </button>
                                </div>
                            </div>
                            
                            <div class="mt-4 text-xs text-gray-500 dark:text-gray-400">
                                <p><strong>Icon Class Examples:</strong></p>
                                <ul class="list-disc list-inside space-y-1 mt-1">
                                    <li>Font Awesome: <code class="bg-gray-200 dark:bg-gray-700 px-1 rounded">fab fa-twitter</code></li>
                                    <li>Heroicons: <code class="bg-gray-200 dark:bg-gray-700 px-1 rounded">h-5 w-5</code></li>
                                    <li>Custom: <code class="bg-gray-200 dark:bg-gray-700 px-1 rounded">icon-twitter</code></li>
                                </ul>
                            </div>
                        </div>
                        <div v-else class="text-center py-8 border-2 border-dashed border-gray-300 dark:border-gray-700 rounded-lg">
                            <svg class="h-12 w-12 text-gray-400 dark:text-gray-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path>
                            </svg>
                            <p class="text-sm text-gray-500 dark:text-gray-400">No social links added yet</p>
                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Add social media links to display in the footer</p>
                        </div>
                    </div>

                    <div class="pt-6 border-t border-gray-200 dark:border-gray-700">
                        <div class="flex justify-end">
                            <button 
                                type="submit" 
                                :disabled="loading"
                                :class="[
                                    'px-6 py-3 text-sm font-medium text-white rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800',
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
                                <span v-else>Save All Settings</span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Settings Preview -->
            <div v-if="siteSettings" class="mt-8 bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 border border-gray-200 dark:border-gray-700">
                <div class="mb-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Live Preview</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">See how your settings will appear on the website</p>
                </div>
                
                <div class="border-2 border-gray-300 dark:border-gray-700 rounded-xl p-8 bg-gradient-to-br from-gray-50 to-white dark:from-gray-900 dark:to-gray-800">
                    <!-- Site Header Preview -->
                    <div class="text-center mb-8 pb-8 border-b border-gray-200 dark:border-gray-700">
                        <div class="h-10 w-10 bg-indigo-500 rounded-lg flex items-center justify-center mx-auto mb-4">
                            <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h4 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">{{ form.site_name || 'Your Site Name' }}</h4>
                        <p class="text-gray-600 dark:text-gray-400 max-w-md mx-auto">{{ form.site_description || 'Your site description will appear here' }}</p>
                    </div>
                    
                    <!-- Social Links Preview -->
                    <div v-if="form.footer_social_links && form.footer_social_links.length > 0" class="flex justify-center space-x-6 mb-8">
                        <div v-for="social in form.footer_social_links" :key="social.platform" 
                             class="group relative">
                            <a :href="social.url" 
                               class="h-12 w-12 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center text-gray-600 dark:text-gray-400 hover:bg-indigo-100 dark:hover:bg-indigo-900 hover:text-indigo-600 dark:hover:text-indigo-400 transition-all duration-300">
                                <template v-if="social.icon && social.icon.includes('fa-')">
                                    <i :class="social.icon" class="text-lg"></i>
                                </template>
                                <template v-else-if="social.platform">
                                    <span class="text-sm font-medium">{{ social.platform.charAt(0) }}</span>
                                </template>
                                <template v-else>
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                                    </svg>
                                </template>
                            </a>
                            <span v-if="social.platform" 
                                  class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-2 py-1 text-xs font-medium bg-gray-900 dark:bg-gray-700 text-white dark:text-gray-300 rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap">
                                {{ social.platform }}
                            </span>
                        </div>
                    </div>
                    <div v-else class="text-center mb-8">
                        <div class="h-12 w-12 border-2 border-dashed border-gray-300 dark:border-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="h-5 w-5 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path>
                            </svg>
                        </div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Social links will appear here</p>
                    </div>
                    
                    <!-- Footer Preview -->
                    <div class="text-center">
                        <div class="mb-4">
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                <span>© {{ new Date().getFullYear() }} </span>
                                <strong class="text-gray-900 dark:text-white font-medium">{{ form.copyright_name || 'Your Name' }}</strong>
                                <span> {{ form.copyright_text || 'All rights reserved' }}</span>
                            </p>
                        </div>
                        
                        <div class="text-xs text-gray-500 dark:text-gray-400">
                            <p>
                                <span>{{ form.distributed_by_text || 'Distributed by' }} </span>
                                <a :href="form.designer_url || '#'" 
                                   class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 transition-colors font-medium">
                                    {{ form.designer_name || 'Designer' }}
                                </a>
                                <span class="mx-1">•</span>
                                <a :href="form.distributor_url || '#'" 
                                   class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 transition-colors font-medium">
                                    {{ form.distributor_name || 'Distributor' }}
                                </a>
                            </p>
                        </div>
                    </div>
                </div>
                
                <!-- Preview Notes -->
                <div class="mt-6 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-100 dark:border-blue-800">
                    <div class="flex">
                        <svg class="h-5 w-5 text-blue-400 dark:text-blue-300 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div>
                            <h4 class="text-sm font-medium text-blue-900 dark:text-blue-300">Preview Information</h4>
                            <p class="text-sm text-blue-700 dark:text-blue-400 mt-1">
                                This preview shows how your settings will appear on the live website. Social icons require Font Awesome or similar icon libraries to display properly.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const loading = ref(false);
const siteSettings = ref(null);
const message = ref('');
const messageType = ref(''); // 'success' or 'error'

const form = reactive({
    site_name: '',
    site_description: '',
    footer_social_links: [],
    copyright_name: '',
    copyright_text: '',
    designer_name: '',
    designer_url: '',
    distributor_name: '',
    distributor_url: '',
    distributed_by_text: ''
});

onMounted(() => {
    fetchSiteSettings();
});

const fetchSiteSettings = async () => {
    try {
        const response = await axios.get('/api/site-settings');
        if (response.data && response.data.data) {
            siteSettings.value = response.data.data;
            Object.assign(form, {
                site_name: response.data.data.site_name || '',
                site_description: response.data.data.site_description || '',
                footer_social_links: response.data.data.footer_social_links || [],
                copyright_name: response.data.data.copyright_name || '',
                copyright_text: response.data.data.copyright_text || '',
                designer_name: response.data.data.designer_name || '',
                designer_url: response.data.data.designer_url || '',
                distributor_name: response.data.data.distributor_name || '',
                distributor_url: response.data.data.distributor_url || '',
                distributed_by_text: response.data.data.distributed_by_text || ''
            });
        } else if (response.data) {
            // Handle direct response (without data wrapper)
            siteSettings.value = response.data;
            Object.assign(form, {
                site_name: response.data.site_name || '',
                site_description: response.data.site_description || '',
                footer_social_links: response.data.footer_social_links || [],
                copyright_name: response.data.copyright_name || '',
                copyright_text: response.data.copyright_text || '',
                designer_name: response.data.designer_name || '',
                designer_url: response.data.designer_url || '',
                distributor_name: response.data.distributor_name || '',
                distributor_url: response.data.distributor_url || '',
                distributed_by_text: response.data.distributed_by_text || ''
            });
        }
    } catch (error) {
        console.error('Error fetching site settings:', error);
        showMessage('Error fetching site settings. Please try again.', 'error');
    }
};

const addSocialLink = () => {
    form.footer_social_links.push({ platform: '', url: '', icon: '' });
};

const removeSocialLink = (index) => {
    if (confirm('Are you sure you want to remove this social link?')) {
        form.footer_social_links.splice(index, 1);
    }
};

const saveSettings = async () => {
    loading.value = true;
    message.value = ''; // Clear previous messages
    
    try {
        const data = { ...form };
        
        // Filter out empty social links
        data.footer_social_links = data.footer_social_links.filter(link => 
            link.platform.trim() && link.url.trim()
        );

        let response;
        if (siteSettings.value && siteSettings.value.id) {
            // Update existing
            response = await axios.put(`/api/site-settings/${siteSettings.value.id}`, data);
        } else {
            // Create new
            response = await axios.post('/api/site-settings', data);
        }

        if (response.data) {
            // Handle both wrapped and direct responses
            if (response.data.success && response.data.data) {
                siteSettings.value = response.data.data;
                showMessage(response.data.message || 'Settings saved successfully!', 'success');
            } else if (response.data.id) {
                // Direct response (no wrapper)
                siteSettings.value = response.data;
                showMessage('Settings saved successfully!', 'success');
            }
            
            // Refresh data
            await fetchSiteSettings();
        }
    } catch (error) {
        console.error('Error saving settings:', error);
        
        let errorMsg = 'Failed to save settings. Please try again.';
        
        if (error.response?.data?.errors) {
            // Format validation errors
            const errors = error.response.data.errors;
            errorMsg = Object.values(errors).flat().join('\n');
        } else if (error.response?.data?.message) {
            errorMsg = error.response.data.message;
        }
        
        showMessage(errorMsg, 'error');
    } finally {
        loading.value = false;
    }
};

const showMessage = (msg, type) => {
    message.value = msg;
    messageType.value = type;
    
    // Auto-hide success messages after 5 seconds
    if (type === 'success') {
        setTimeout(() => {
            message.value = '';
        }, 5000);
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

/* Social link hover tooltip */
.group:hover .group-hover\:opacity-100 {
    opacity: 1;
}

/* Form section transitions */
.border-t {
    transition: border-color 200ms ease;
}

/* Responsive adjustments */
@media (max-width: 640px) {
    .lg\:col-span-4,
    .lg\:col-span-5,
    .lg\:col-span-2,
    .lg\:col-span-1 {
        grid-column: span 1;
    }
}

/* Button focus states */
button:focus {
    outline: none;
    ring-width: 2px;
    ring-offset-width: 2px;
}

/* Preview gradient animation */
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

/* Required field indicator */
.text-red-500 {
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% {
        opacity: 0.5;
    }
    50% {
        opacity: 1;
    }
    100% {
        opacity: 0.5;
    }
}

/* Message fade in/out */
.fade-enter-active, .fade-leave-active {
    transition: opacity 0.3s ease;
}

.fade-enter-from, .fade-leave-to {
    opacity: 0;
}
</style>