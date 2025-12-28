<template>
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900 flex transition-colors duration-200">
        <!-- Mobile Sidebar Overlay -->
        <Transition
            enter-active-class="transition-opacity ease-linear duration-300"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="transition-opacity ease-linear duration-300"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div 
                v-show="mobileSidebarOpen"
                @click="mobileSidebarOpen = false"
                class="fixed inset-0 bg-gray-600 bg-opacity-75 lg:hidden z-40"
            ></div>
        </Transition>

        <!-- Sidebar -->
        <aside 
            :class="[
                'flex flex-col fixed h-screen overflow-y-auto transition-all duration-300 ease-in-out z-50',
                isSidebarCollapsed ? 'w-16' : 'w-64',
                mobileSidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0',
                isDarkMode 
                    ? 'bg-gray-800 text-white' 
                    : 'bg-gray-900 text-white'
            ]"
        >
            <!-- Mobile Header with Hamburger -->
            <div 
                :class="[
                    'lg:hidden flex items-center justify-between p-4 border-b',
                    isDarkMode ? 'border-gray-700' : 'border-gray-800'
                ]"
            >
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 bg-indigo-500 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h1 class="text-xl font-bold">Portfolio Admin</h1>
                </div>
                <button @click="mobileSidebarOpen = false" class="text-gray-400 hover:text-white p-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Desktop Logo (Expanded View) -->
            <div v-if="!isSidebarCollapsed" 
                 :class="[
                     'p-4 border-b',
                     isDarkMode ? 'border-gray-700' : 'border-gray-800'
                 ]"
                 class="hidden lg:block"
            >
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-indigo-500 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h1 class="text-xl font-bold">Portfolio Admin</h1>
                    </div>
                </div>
            </div>
            
            <!-- Collapsed Logo Icon -->
            <div v-else 
                 :class="[
                     'p-4 border-b',
                     isDarkMode ? 'border-gray-700' : 'border-gray-800'
                 ]"
                 class="hidden lg:block"
            >
                <div class="flex items-center justify-center">
                    <div class="w-8 h-8 bg-indigo-500 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Navigation - Expanded View -->
            <nav v-if="!isSidebarCollapsed" class="flex-1 p-4 overflow-y-auto">
                <div class="space-y-1">
                    <Link 
                        v-for="item in navigation" 
                        :key="item.name"
                        :href="route(item.route)"
                        @click="handleMenuClick"
                        :class="[
                            'flex items-center space-x-3 px-3 py-2 rounded-lg transition-colors text-sm',
                            $page.component === item.component
                                ? 'bg-indigo-600 text-white' 
                                : isDarkMode 
                                    ? 'text-gray-300 hover:bg-gray-700 hover:text-white' 
                                    : 'text-gray-300 hover:bg-gray-800 hover:text-white'
                        ]"
                    >
                        <component :is="item.icon" class="w-5 h-5" />
                        <span>{{ item.name }}</span>
                    </Link>
                </div>

                <!-- Sidebar Toggle Button (Bottom) -->
                <div class="mt-8 pt-4 border-t border-gray-700">
                    <button 
                        @click="toggleSidebar"
                        :class="[
                            'flex items-center justify-center w-full px-3 py-2 rounded-lg transition-colors text-sm',
                            isDarkMode 
                                ? 'text-gray-400 hover:bg-gray-700 hover:text-white' 
                                : 'text-gray-400 hover:bg-gray-800 hover:text-white'
                        ]"
                    >
                        <svg 
                            :class="['w-5 h-5 transition-transform', isSidebarCollapsed ? 'rotate-180' : '']" 
                            fill="none" 
                            stroke="currentColor" 
                            viewBox="0 0 24 24"
                        >
                            <path v-if="!isSidebarCollapsed" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
                            <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7" />
                        </svg>
                        <span v-if="!isSidebarCollapsed" class="ml-2">Collapse Sidebar</span>
                    </button>
                </div>
            </nav>

            <!-- Navigation - Collapsed View -->
            <nav v-else class="flex-1 flex flex-col items-center space-y-3 py-4">
                <div v-for="item in navigation" :key="item.name" class="relative group">
                    <Link 
                        :href="route(item.route)"
                        @click="handleMenuClick"
                        :class="[
                            'p-2 rounded-lg transition-colors block relative',
                            $page.component === item.component
                                ? 'bg-indigo-600 text-white' 
                                : isDarkMode 
                                    ? 'text-gray-300 hover:bg-gray-700 hover:text-white' 
                                    : 'text-gray-300 hover:bg-gray-800 hover:text-white'
                        ]"
                    >
                        <component :is="item.icon" class="w-5 h-5" />
                        <div :class="[
                            'absolute left-full ml-2 px-2 py-1 text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap z-10',
                            isDarkMode ? 'bg-gray-800 text-white' : 'bg-gray-900 text-white'
                        ]">
                            {{ item.name }}
                        </div>
                    </Link>
                </div>

                <!-- Collapsed Sidebar Toggle Button -->
                <div class="relative group mt-auto pt-4 border-t border-gray-700">
                    <button 
                        @click="toggleSidebar"
                        :class="[
                            'p-2 rounded-lg transition-colors block relative',
                            isDarkMode 
                                ? 'text-gray-400 hover:bg-gray-700 hover:text-white' 
                                : 'text-gray-400 hover:bg-gray-800 hover:text-white'
                        ]"
                    >
                        <svg 
                            :class="['w-5 h-5 transition-transform', isSidebarCollapsed ? 'rotate-180' : '']" 
                            fill="none" 
                            stroke="currentColor" 
                            viewBox="0 0 24 24"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7" />
                        </svg>
                        <div :class="[
                            'absolute left-full ml-2 px-2 py-1 text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap z-10',
                            isDarkMode ? 'bg-gray-800 text-white' : 'bg-gray-900 text-white'
                        ]">
                            Expand Sidebar
                        </div>
                    </button>
                </div>
            </nav>
        </aside>

        <!-- Main Content Area -->
        <div :class="['flex-1 flex flex-col min-w-0 transition-all duration-300', isSidebarCollapsed ? 'lg:ml-16' : 'lg:ml-64']">
            <!-- Top Navigation Bar -->
            <nav :class="[
                'shadow-sm border-b',
                isDarkMode 
                    ? 'bg-gray-800 border-gray-700' 
                    : 'bg-white border-gray-200'
            ]">
                <div class="px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <!-- Left side: Mobile menu button and breadcrumb -->
                        <div class="flex items-center">
                            <!-- Desktop Sidebar Toggle (Hamburger) -->
                            <button 
                                @click="toggleSidebar"
                                :class="[
                                    'hidden lg:block mr-4 -ml-2 p-2 rounded-md hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500',
                                    isDarkMode 
                                        ? 'text-gray-400 hover:text-gray-300' 
                                        : 'text-gray-500 hover:text-gray-600'
                                ]"
                                :title="isSidebarCollapsed ? 'Expand Sidebar' : 'Collapse Sidebar'"
                            >
                                <span class="sr-only">Toggle sidebar</span>
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                                </svg>
                            </button>
                            
                            <!-- Mobile menu button -->
                            <button 
                                @click="mobileSidebarOpen = true"
                                :class="[
                                    'lg:hidden mr-4 -ml-2 p-2 rounded-md hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500',
                                    isDarkMode 
                                        ? 'text-gray-400 hover:text-gray-300' 
                                        : 'text-gray-500 hover:text-gray-600'
                                ]"
                            >
                                <span class="sr-only">Open sidebar</span>
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path>
                                </svg>
                            </button>

                            <!-- Breadcrumb -->
                            <div :class="[
                                'text-sm',
                                isDarkMode ? 'text-gray-400' : 'text-gray-600'
                            ]">
                                <span :class="[
                                    'font-medium',
                                    isDarkMode ? 'text-white' : 'text-gray-900'
                                ]">Portfolio Admin</span>
                                <span class="mx-2">/</span>
                                <span class="capitalize">{{ title }}</span>
                            </div>
                        </div>

                        <!-- Right side: Theme toggle and Logout button -->
                        <div class="flex items-center space-x-4">
                            <!-- Theme Toggle Button -->
                            <button 
                                @click="toggleTheme"
                                :class="[
                                    'p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors',
                                    isDarkMode ? 'text-gray-400 hover:text-gray-300' : 'text-gray-500 hover:text-gray-600'
                                ]"
                                :title="isDarkMode ? 'Switch to light mode' : 'Switch to dark mode'"
                            >
                                <span class="sr-only">Toggle theme</span>
                                <!-- Sun icon shows when in dark mode -->
                                <svg 
                                    v-if="isDarkMode" 
                                    class="w-5 h-5" 
                                    fill="none" 
                                    stroke="currentColor" 
                                    viewBox="0 0 24 24"
                                >
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                </svg>
                                <!-- Moon icon shows when in light mode -->
                                <svg 
                                    v-else 
                                    class="w-5 h-5" 
                                    fill="none" 
                                    stroke="currentColor" 
                                    viewBox="0 0 24 24"
                                >
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                                </svg>
                            </button>

                            <!-- Logout Button -->
                            <button 
                                @click="logout"
                                :class="[
                                    'px-4 py-2 text-sm font-medium rounded-md transition-colors',
                                    isDarkMode
                                        ? 'bg-red-600 hover:bg-red-700 text-white'
                                        : 'bg-red-600 hover:bg-red-700 text-white'
                                ]"
                            >
                                Logout
                            </button>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Main Content Area -->
            <main class="flex-1 py-6" :class="[
                isDarkMode ? 'bg-gray-900' : 'bg-gray-100'
            ]">
                <div class="px-4 sm:px-6 lg:px-8">
                    <!-- Flash Messages -->
                    <div v-if="$page.props.flash.success" class="mb-4 p-4 bg-green-50 border border-green-200 rounded-md dark:bg-green-900 dark:border-green-700">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <CheckCircleIcon class="h-5 w-5 text-green-400 dark:text-green-300" />
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-green-800 dark:text-green-200">{{ $page.props.flash.success }}</p>
                            </div>
                        </div>
                    </div>

                    <slot />
                </div>
            </main>

            <!-- Footer -->
            <footer :class="[
                'border-t',
                isDarkMode 
                    ? 'bg-gray-800 border-gray-700' 
                    : 'bg-white border-gray-200'
            ]">
                <div class="px-4 py-6 sm:px-6 lg:px-8">
                    <div class="text-center text-sm text-gray-600 dark:text-gray-400">
                        © {{ new Date().getFullYear() }} Portfolio Admin. All rights reserved.
                    </div>
                </div>
            </footer>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { usePage, router, Link } from '@inertiajs/vue3';
import { 
    HomeIcon, 
    UserIcon, 
    AcademicCapIcon, 
    BriefcaseIcon, 
    PhotoIcon as PhotographIcon,
    Cog6ToothIcon as CogIcon,
    ChartBarIcon,
    ChatBubbleLeftRightIcon,
    EnvelopeIcon,
    CheckCircleIcon
} from '@heroicons/vue/24/outline';

const page = usePage();

// Reactive states
const mobileSidebarOpen = ref(false);
const isDarkMode = ref(false);
const isSidebarCollapsed = ref(false);

// Navigation items - Updated to match actual component names
const navigation = [
    { name: 'Dashboard', route: 'admin.dashboard', component: 'Admin/Dashboard', icon: HomeIcon },
    { name: 'Hero Section', route: 'admin.hero', component: 'Admin/HeroSection', icon: PhotographIcon },
    { name: 'About Section', route: 'admin.about', component: 'Admin/AboutSection', icon: UserIcon },
    { name: 'Skills', route: 'admin.skills', component: 'Admin/SkillsManagement', icon: ChartBarIcon },
    { name: 'Statistics', route: 'admin.statistics', component: 'Admin/StatisticsManagement', icon: AcademicCapIcon },
    { name: 'Resume', route: 'admin.resume', component: 'Admin/ResumeManagement', icon: BriefcaseIcon },
    { name: 'Portfolio', route: 'admin.portfolio', component: 'Admin/PortfolioManagement', icon: PhotographIcon },
    { name: 'Services', route: 'admin.services', component: 'Admin/ServicesManagement', icon: CogIcon },
    { name: 'Testimonials', route: 'admin.testimonials', component: 'Admin/TestimonialsManagement', icon: ChatBubbleLeftRightIcon },
    { name: 'Contact Info', route: 'admin.contact', component: 'Admin/ContactManagement', icon: EnvelopeIcon },
    { name: 'Site Settings', route: 'admin.settings', component: 'Admin/SiteSettings', icon: CogIcon },
];

// Computed title for breadcrumb
const title = computed(() => {
    const currentComponent = page.component;
    const currentNav = navigation.find(item => item.component === currentComponent);
    return currentNav ? currentNav.name : 'Dashboard';
});

// Theme management
const toggleTheme = () => {
    isDarkMode.value = !isDarkMode.value;
    
    if (isDarkMode.value) {
        document.documentElement.classList.add('dark');
        document.body.classList.add('dark');
        localStorage.setItem('theme', 'dark');
    } else {
        document.documentElement.classList.remove('dark');
        document.body.classList.remove('dark');
        localStorage.setItem('theme', 'light');
    }
};

// Initialize theme
const initTheme = () => {
    const savedTheme = localStorage.getItem('theme');
    
    if (savedTheme === 'dark' || (!savedTheme && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
        isDarkMode.value = true;
        document.documentElement.classList.add('dark');
        document.body.classList.add('dark');
    } else {
        isDarkMode.value = false;
        document.documentElement.classList.remove('dark');
        document.body.classList.remove('dark');
    }
};

// Sidebar toggle function
const toggleSidebar = () => {
    isSidebarCollapsed.value = !isSidebarCollapsed.value;
    localStorage.setItem('sidebarCollapsed', isSidebarCollapsed.value);
};

// Initialize sidebar state
const initSidebarState = () => {
    const savedSidebarState = localStorage.getItem('sidebarCollapsed');
    if (savedSidebarState !== null) {
        isSidebarCollapsed.value = savedSidebarState === 'true';
    }
};

// Handle menu clicks in mobile
const handleMenuClick = () => {
    mobileSidebarOpen.value = false;
};

// Logout function
const logout = () => {
    router.post('/admin/logout');
};

// Lifecycle hooks
onMounted(() => {
    initTheme();
    initSidebarState();
    
    // Watch for system theme changes
    const darkModeMediaQuery = window.matchMedia('(prefers-color-scheme: dark)');
    darkModeMediaQuery.addEventListener('change', (e) => {
        if (!localStorage.getItem('theme')) {
            if (e.matches) {
                isDarkMode.value = true;
                document.documentElement.classList.add('dark');
            } else {
                isDarkMode.value = false;
                document.documentElement.classList.remove('dark');
            }
        }
    });
});
</script>

<style scoped>
/* Hide scrollbar but allow scrolling */
.h-screen {
    overflow-y: auto;
    scrollbar-width: thin;
    scrollbar-color: rgba(156, 163, 175, 0.5) transparent;
}

.h-screen::-webkit-scrollbar {
    width: 6px;
}

.h-screen::-webkit-scrollbar-track {
    background: transparent;
}

.h-screen::-webkit-scrollbar-thumb {
    background-color: rgba(156, 163, 175, 0.5);
    border-radius: 3px;
}

/* Dark mode scrollbar */
.dark .h-screen::-webkit-scrollbar-thumb {
    background-color: rgba(75, 85, 99, 0.5);
}

.dark .h-screen::-webkit-scrollbar-thumb:hover {
    background-color: rgba(75, 85, 99, 0.7);
}

/* Smooth transitions for all elements */
* {
    transition-property: background-color, border-color, color, fill, stroke;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
    transition-duration: 300ms;
}

/* Ensure proper tooltip positioning */
.relative .absolute {
    pointer-events: none;
}

.group:hover .absolute {
    pointer-events: auto;
}
</style>