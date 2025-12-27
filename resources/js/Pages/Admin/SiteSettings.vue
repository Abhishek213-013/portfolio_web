<template>
    <AdminLayout>
        <div class="max-w-4xl mx-auto">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Site Settings</h2>

            <!-- Site Settings Form -->
            <div class="bg-white rounded-lg shadow p-6">
                <form @submit.prevent="saveSettings" class="space-y-6">
                    <!-- Basic Information -->
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Basic Information</h3>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Site Name</label>
                                <input 
                                    v-model="form.site_name" 
                                    type="text" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                    required
                                />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Site Description</label>
                                <textarea 
                                    v-model="form.site_description" 
                                    rows="3"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                    required
                                ></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Copyright Information -->
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Copyright Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Copyright Name</label>
                                <input 
                                    v-model="form.copyright_name" 
                                    type="text" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                    required
                                />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Copyright Text</label>
                                <input 
                                    v-model="form.copyright_text" 
                                    type="text" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                    required
                                />
                            </div>
                        </div>
                    </div>

                    <!-- Footer Credits -->
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Footer Credits</h3>
                        <div class="space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Designer Name</label>
                                    <input 
                                        v-model="form.designer_name" 
                                        type="text" 
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                        required
                                    />
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Designer URL</label>
                                    <input 
                                        v-model="form.designer_url" 
                                        type="url" 
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                        required
                                    />
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Distributor Name</label>
                                    <input 
                                        v-model="form.distributor_name" 
                                        type="text" 
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                        required
                                    />
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Distributor URL</label>
                                    <input 
                                        v-model="form.distributor_url" 
                                        type="url" 
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                        required
                                    />
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Distributed By Text</label>
                                <input 
                                    v-model="form.distributed_by_text" 
                                    type="text" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                    required
                                />
                            </div>
                        </div>
                    </div>

                    <!-- Social Links -->
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Footer Social Links</h3>
                        <div v-for="(link, index) in form.footer_social_links" :key="index" class="grid grid-cols-3 gap-2 mb-2">
                            <input 
                                v-model="link.platform" 
                                type="text" 
                                placeholder="Platform"
                                class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            />
                            <input 
                                v-model="link.url" 
                                type="url" 
                                placeholder="URL"
                                class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            />
                            <div class="flex">
                                <input 
                                    v-model="link.icon" 
                                    type="text" 
                                    placeholder="Icon class"
                                    class="flex-1 px-3 py-2 border border-gray-300 rounded-l-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                />
                                <button 
                                    type="button" 
                                    @click="removeSocialLink(index)"
                                    class="px-3 py-2 bg-red-100 text-red-600 rounded-r-md hover:bg-red-200"
                                >
                                    Remove
                                </button>
                            </div>
                        </div>
                        <button 
                            type="button" 
                            @click="addSocialLink"
                            class="mt-2 px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200"
                        >
                            Add Social Link
                        </button>
                    </div>

                    <div class="flex justify-end">
                        <button 
                            type="submit" 
                            :disabled="loading"
                            class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-md disabled:opacity-50"
                        >
                            {{ loading ? 'Saving...' : 'Save Settings' }}
                        </button>
                    </div>
                </form>
            </div>

            <!-- Settings Preview -->
            <div v-if="siteSettings" class="mt-8 bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Preview</h3>
                <div class="border rounded-lg p-6">
                    <div class="text-center mb-6">
                        <h4 class="text-2xl font-bold text-gray-900 mb-2">{{ form.site_name }}</h4>
                        <p class="text-gray-600">{{ form.site_description }}</p>
                    </div>
                    
                    <div class="flex justify-center space-x-4 mb-6">
                        <a 
                            v-for="social in form.footer_social_links" 
                            :key="social.platform"
                            :href="social.url"
                            class="text-gray-600 hover:text-indigo-600"
                        >
                            <i :class="social.icon" class="text-xl"></i>
                        </a>
                    </div>
                    
                    <div class="text-center text-gray-500 text-sm">
                        <p class="mb-2">
                            <span>Copyright</span> 
                            <strong class="mx-1">{{ form.copyright_name }}</strong> 
                            <span>{{ form.copyright_text }}</span>
                        </p>
                        <p>
                            <span>{{ form.distributed_by_text }}</span>
                            <a :href="form.designer_url" class="text-indigo-600 hover:text-indigo-800 mx-1">
                                {{ form.designer_name }}
                            </a>
                            <span class="mx-1">{{ form.distributor_name }}</span>
                            <a :href="form.distributor_url" class="text-indigo-600 hover:text-indigo-800">
                                {{ form.distributor_name }}
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue';
import { router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const loading = ref(false);
const siteSettings = ref(null);
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
        if (response.data) {
            siteSettings.value = response.data;
            Object.assign(form, {
                site_name: response.data.site_name,
                site_description: response.data.site_description,
                footer_social_links: response.data.footer_social_links || [],
                copyright_name: response.data.copyright_name,
                copyright_text: response.data.copyright_text,
                designer_name: response.data.designer_name,
                designer_url: response.data.designer_url,
                distributor_name: response.data.distributor_name,
                distributor_url: response.data.distributor_url,
                distributed_by_text: response.data.distributed_by_text
            });
        }
    } catch (error) {
        console.error('Error fetching site settings:', error);
    }
};

const addSocialLink = () => {
    form.footer_social_links.push({ platform: '', url: '', icon: '' });
};

const removeSocialLink = (index) => {
    form.footer_social_links.splice(index, 1);
};

const saveSettings = async () => {
    loading.value = true;
    
    try {
        let response;
        if (siteSettings.value) {
            response = await axios.put(`/api/site-settings/${siteSettings.value.id}`, form);
        } else {
            response = await axios.post('/api/site-settings', form);
        }
        
        siteSettings.value = response.data;
        router.reload({ only: ['flash'] });
    } catch (error) {
        console.error('Error saving settings:', error);
    } finally {
        loading.value = false;
    }
};
</script>