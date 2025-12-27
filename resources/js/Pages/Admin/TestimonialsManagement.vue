<template>
    <AdminLayout>
        <div class="max-w-7xl mx-auto">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Testimonials Management</h2>
                <button 
                    @click="showForm = !showForm" 
                    class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors"
                >
                    {{ showForm ? 'Cancel' : 'Add New Testimonial' }}
                </button>
            </div>

            <!-- Add/Edit Testimonial Form -->
            <div v-if="showForm" class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 mb-8">
                <form @submit.prevent="saveTestimonial" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Name</label>
                            <input 
                                v-model="form.name" 
                                type="text" 
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white"
                                required
                            />
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Position</label>
                            <input 
                                v-model="form.position" 
                                type="text" 
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white"
                                required
                            />
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Image</label>
                        <div class="mt-1 flex items-center">
                            <input 
                                type="file" 
                                @change="handleImageUpload"
                                accept="image/*"
                                class="w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 dark:file:bg-gray-700 dark:file:text-gray-300"
                            />
                        </div>
                        <div v-if="form.image" class="mt-2">
                            <img :src="form.image instanceof File ? URL.createObjectURL(form.image) : form.image" 
                                 class="h-32 w-32 object-cover rounded-full">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Testimonial Content</label>
                        <textarea 
                            v-model="form.content" 
                            rows="4"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white"
                            required
                        ></textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Rating</label>
                            <div class="flex items-center space-x-1">
                                <button 
                                    v-for="star in 5" 
                                    :key="star"
                                    type="button"
                                    @click="form.rating = star"
                                    class="focus:outline-none"
                                >
                                    <StarIcon 
                                        :class="[
                                            'h-8 w-8',
                                            star <= form.rating ? 'text-yellow-400 fill-current' : 'text-gray-300 dark:text-gray-600'
                                        ]" 
                                    />
                                </button>
                                <span class="ml-2 text-gray-700 dark:text-gray-300">{{ form.rating }}/5</span>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Order</label>
                            <input 
                                v-model="form.order" 
                                type="number" 
                                min="0"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white"
                            />
                        </div>
                    </div>

                    <div class="flex items-center">
                        <input 
                            v-model="form.is_active" 
                            type="checkbox" 
                            id="is_active"
                            class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 dark:border-gray-600 rounded dark:bg-gray-700"
                        />
                        <label for="is_active" class="ml-2 block text-sm text-gray-900 dark:text-gray-300">Active</label>
                    </div>

                    <div class="flex justify-end space-x-4">
                        <button 
                            type="button" 
                            @click="cancelEdit"
                            class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-md"
                        >
                            Cancel
                        </button>
                        <button 
                            type="submit" 
                            :disabled="loading"
                            class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-md disabled:opacity-50"
                        >
                            {{ loading ? 'Saving...' : (editingId ? 'Update Testimonial' : 'Add Testimonial') }}
                        </button>
                    </div>
                </form>
            </div>

            <!-- Testimonials List -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div 
                    v-for="testimonial in testimonials" 
                    :key="testimonial.id"
                    class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden"
                >
                    <div class="p-6">
                        <div class="flex items-start space-x-4 mb-4">
                            <img 
                                v-if="testimonial.image" 
                                :src="testimonial.image" 
                                :alt="testimonial.name"
                                class="h-16 w-16 object-cover rounded-full"
                            />
                            <div class="flex-1">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h4 class="text-lg font-medium text-gray-900 dark:text-white">{{ testimonial.name }}</h4>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ testimonial.position }}</p>
                                    </div>
                                    <div class="flex space-x-1">
                                        <button 
                                            @click="editTestimonial(testimonial)"
                                            class="p-1 text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300"
                                        >
                                            <PencilIcon class="h-4 w-4" />
                                        </button>
                                        <button 
                                            @click="deleteTestimonial(testimonial.id)"
                                            class="p-1 text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300"
                                        >
                                            <TrashIcon class="h-4 w-4" />
                                        </button>
                                    </div>
                                </div>
                                <div class="flex mt-2">
                                    <StarIcon 
                                        v-for="star in 5" 
                                        :key="star"
                                        :class="[
                                            'h-4 w-4',
                                            star <= testimonial.rating ? 'text-yellow-400 fill-current' : 'text-gray-300 dark:text-gray-600'
                                        ]" 
                                    />
                                </div>
                            </div>
                        </div>
                        <div class="border-l-4 border-indigo-500 dark:border-indigo-400 pl-4 py-2">
                            <p class="text-gray-600 dark:text-gray-400 italic">"{{ testimonial.content }}"</p>
                        </div>
                        <div class="mt-4 flex justify-between items-center text-sm text-gray-500 dark:text-gray-400">
                            <span :class="[
                                'px-2 py-1 rounded-full',
                                testimonial.is_active ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300'
                            ]">
                                {{ testimonial.is_active ? 'Active' : 'Inactive' }}
                            </span>
                            <span>Order: {{ testimonial.order }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div v-if="testimonials.length === 0 && !showForm" class="text-center bg-white dark:bg-gray-800 rounded-lg shadow p-12">
                <ChatBubbleLeftRightIcon class="h-16 w-16 text-gray-400 dark:text-gray-600 mx-auto mb-4" />
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No Testimonials</h3>
                <p class="text-gray-500 dark:text-gray-400 mb-4">Add testimonials from your clients to build trust.</p>
                <button 
                    @click="showForm = true" 
                    class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors"
                >
                    Add Testimonial
                </button>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue';
import { router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { PencilIcon, TrashIcon, ChatBubbleLeftRightIcon, StarIcon } from '@heroicons/vue/24/outline';

const showForm = ref(false);
const loading = ref(false);
const editingId = ref(null);
const testimonials = ref([]);
const form = reactive({
    name: '',
    position: '',
    image: null,
    content: '',
    rating: 5,
    order: 0,
    is_active: true
});

onMounted(() => {
    fetchTestimonials();
});

const fetchTestimonials = async () => {
    try {
        const response = await axios.get('/api/testimonials');
        testimonials.value = response.data;
    } catch (error) {
        console.error('Error fetching testimonials:', error);
        // Fallback to empty array
        testimonials.value = [];
    }
};

const handleImageUpload = (event) => {
    const file = event.target.files[0];
    if (file) {
        form.image = file;
    }
};

const editTestimonial = (testimonial) => {
    editingId.value = testimonial.id;
    Object.assign(form, {
        name: testimonial.name,
        position: testimonial.position,
        image: testimonial.image,
        content: testimonial.content,
        rating: testimonial.rating,
        order: testimonial.order,
        is_active: testimonial.is_active
    });
    showForm.value = true;
};

const cancelEdit = () => {
    editingId.value = null;
    resetForm();
    showForm.value = false;
};

const resetForm = () => {
    Object.assign(form, {
        name: '',
        position: '',
        image: null,
        content: '',
        rating: 5,
        order: 0,
        is_active: true
    });
};

const saveTestimonial = async () => {
    loading.value = true;
    
    try {
        const formData = new FormData();
        formData.append('name', form.name);
        formData.append('position', form.position);
        formData.append('content', form.content);
        formData.append('rating', form.rating);
        formData.append('order', form.order);
        formData.append('is_active', form.is_active);
        
        if (form.image instanceof File) {
            formData.append('image', form.image);
        } else if (form.image && !form.image.startsWith('http')) {
            // Only append if it's a new file path (not a URL)
            formData.append('image', form.image);
        }

        let response;
        if (editingId.value) {
            response = await axios.post(`/api/testimonials/${editingId.value}?_method=PUT`, formData, {
                headers: { 'Content-Type': 'multipart/form-data' }
            });
        } else {
            response = await axios.post('/api/testimonials', formData, {
                headers: { 'Content-Type': 'multipart/form-data' }
            });
        }
        
        testimonials.value = await fetchTestimonials();
        cancelEdit();
        
        // Show success message
        router.reload({ 
            only: [], 
            onSuccess: () => {
                // Force a re-fetch
                fetchTestimonials();
            }
        });
    } catch (error) {
        console.error('Error saving testimonial:', error);
        alert('Error saving testimonial. Please try again.');
    } finally {
        loading.value = false;
    }
};

const deleteTestimonial = async (id) => {
    if (!confirm('Are you sure you want to delete this testimonial?')) return;
    
    try {
        await axios.delete(`/api/testimonials/${id}`);
        testimonials.value = testimonials.value.filter(t => t.id !== id);
        
        // Show success message
        router.reload({ 
            only: [], 
            onSuccess: () => {
                // Force a re-fetch
                fetchTestimonials();
            }
        });
    } catch (error) {
        console.error('Error deleting testimonial:', error);
        alert('Error deleting testimonial. Please try again.');
    }
};
</script>