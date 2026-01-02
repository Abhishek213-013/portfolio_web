<template>
    <div class="min-h-screen bg-white dark:bg-gray-900 transition-colors duration-300">
        <!-- Sidebar Navigation -->
        <nav class="fixed left-0 top-0 h-full w-16 md:w-20 bg-white dark:bg-gray-900 border-r border-gray-200 dark:border-gray-800 z-50 flex flex-col items-center py-6 transition-all duration-300 hover:w-64 md:hover:w-72 group"
             :class="{
                 'w-64 md:w-72': isSidebarOpen,
                 'translate-x-0': isSidebarOpen,
                 '-translate-x-full md:translate-x-0': !isSidebarOpen
             }"
             @mouseenter="isSidebarOpen = true"
             @mouseleave="isSidebarOpen = false">
            
            <!-- Close Button (Mobile Only) -->
            <button @click="isSidebarOpen = false" 
                    class="absolute top-4 right-4 p-2 text-gray-600 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 md:hidden">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>

            <!-- Logo/Profile -->
            <div class="mb-6 flex flex-col items-center">
                <div class="h-10 w-10 md:h-12 md:w-12 rounded-full overflow-hidden border-2 border-indigo-500 dark:border-indigo-400 mb-4">
                    <img v-if="heroData?.background_image" 
                         :src="heroData.background_image" 
                         alt="Profile"
                         class="w-full h-full object-cover">
                    <div v-else 
                         class="w-full h-full bg-gradient-to-br from-indigo-500 to-purple-500 flex items-center justify-center">
                        <span class="text-white font-bold text-lg">
                            {{ heroData?.name?.charAt(0) || 'Y' }}
                        </span>
                    </div>
                </div>
                <div class="text-center overflow-hidden transition-all duration-300 opacity-0 group-hover:opacity-100"
                     :class="{'opacity-100': isSidebarOpen}">
                    <h3 class="text-sm font-bold text-gray-900 dark:text-white whitespace-nowrap">
                        {{ heroData?.name || 'Your' }}
                    </h3>
                </div>
            </div>

            <!-- Navigation Links -->
            <ul class="flex-1 space-y-0 w-full">
                <li v-for="(nav, index) in navigation" 
                    :key="index"
                    class="relative">
                    <a :href="nav.href"
                       @click.prevent="scrollToSection(nav.id)"
                       class="flex items-center h-12 px-4 md:px-6 text-gray-600 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors group"
                       :class="{'text-indigo-600 dark:text-indigo-400': activeSection === nav.id}">
                        <div class="w-6 flex-shrink-0 flex items-center justify-center">
                            <i class="text-lg" :class="getNavIcon(nav.name)"></i>
                        </div>
                        <span class="ml-4 text-sm font-medium whitespace-nowrap transition-all duration-300 opacity-0 group-hover:opacity-100"
                              :class="{'opacity-100': isSidebarOpen}">
                            {{ nav.name }}
                        </span>
                        <div v-if="activeSection === nav.id" 
                             class="absolute right-0 top-1/2 transform -translate-y-1/2 h-6 w-1 bg-indigo-600 dark:bg-indigo-400 rounded-l-full">
                        </div>
                    </a>
                </li>
            </ul>
        </nav>

        <!-- Mobile Overlay -->
        <div v-if="isSidebarOpen && windowWidth < 768" 
             @click="isSidebarOpen = false"
             class="fixed inset-0 bg-black/50 z-40 md:hidden transition-opacity duration-300"></div>

        <!-- Main Content -->
        <div class="transition-all duration-300 ml-0 md:ml-16 lg:ml-20"
             :class="{'md:ml-64 lg:ml-72': isSidebarOpen}">
            
            <!-- Floating Theme Toggle for Desktop (Top Right) -->
            <button @click="toggleTheme" 
                    class="fixed top-8 right-8 z-40 hidden md:flex items-center p-3 bg-white/80 dark:bg-gray-800/80 backdrop-blur-lg border border-gray-200 dark:border-gray-700 rounded-full shadow-lg hover:shadow-xl transition-all hover:-translate-y-1 text-gray-600 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400"
                    :title="isDark ? 'Switch to Light Mode' : 'Switch to Dark Mode'">
                <svg v-if="isDark" class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" clip-rule="evenodd" />
                </svg>
                <svg v-else class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z" />
                </svg>
            </button>

            <!-- Loading State -->
            <div v-if="loading" class="flex items-center justify-center min-h-screen">
                <div class="text-center">
                    <div class="inline-block animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-indigo-600 dark:border-indigo-400 mb-4"></div>
                    <p class="text-gray-600 dark:text-gray-400">Loading portfolio...</p>
                </div>
            </div>

            <!-- Content -->
            <div v-else>
                <!-- Hero Section -->
                <section id="home" class="pt-16 md:pt-24 pb-16 md:pb-24 relative overflow-hidden mb-0">
                    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-start">
                            <!-- Left Content - Name and Role -->
                            <div class="flex flex-col justify-center text-left">
                                <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-gray-900 dark:text-white mb-6">
                                    {{ heroData?.name || 'Your Name' }}
                                </h1>
                                <div class="text-2xl md:text-3xl text-gray-600 dark:text-gray-300 mb-8">
                                    I'm a
                                    <span class="text-indigo-600 dark:text-indigo-400 font-semibold typewriter-text">
                                        {{ currentRole }}
                                        <span class="typewriter-cursor">|</span>
                                    </span>
                                </div>
                                <div class="flex space-x-6">
                                    <a v-for="social in heroData?.social_links || []" 
                                       :key="social.platform"
                                       :href="social.url"
                                       target="_blank"
                                       class="h-12 w-12 bg-white dark:bg-gray-800 rounded-full flex items-center justify-center shadow-lg hover:shadow-xl transition-all hover:-translate-y-1"
                                       :title="social.platform">
                                        <i v-if="social.icon" :class="social.icon" class="text-xl text-gray-700 dark:text-gray-300"></i>
                                        <span v-else class="text-sm font-medium">{{ social.platform?.charAt(0) }}</span>
                                    </a>
                                </div>
                            </div>
                            
                            <!-- Right Image with Lighting Effect -->
                            <div class="relative flex justify-center lg:justify-end items-center">
                                <div class="relative w-64 h-64 md:w-80 md:h-80">
                                    <!-- Outer glow effect -->
                                    <div class="absolute inset-0 bg-gradient-to-r from-indigo-400 via-purple-400 to-pink-400 dark:from-indigo-500 dark:via-purple-500 dark:to-pink-500 rounded-full transform rotate-6 blur-xl opacity-70 animate-pulse"></div>
                                    
                                    <!-- Middle glow layer -->
                                    <div class="absolute inset-0 bg-gradient-to-r from-indigo-300 via-purple-300 to-pink-300 dark:from-indigo-400 dark:via-purple-400 dark:to-pink-400 rounded-full transform rotate-3 blur-md opacity-50"></div>
                                    
                                    <!-- Image container with metallic border -->
                                    <div v-if="heroData?.background_image" 
                                         class="relative w-full h-full rounded-full overflow-hidden border-4 border-white/80 dark:border-gray-800/80 shadow-2xl backdrop-blur-sm">
                                        <!-- Inner glow border -->
                                        <div class="absolute inset-0 rounded-full border-2 border-white/50 dark:border-white/30 shadow-inner"></div>
                                        <img :src="heroData.background_image" 
                                             alt="Profile"
                                             class="w-full h-full object-cover relative z-10">
                                    </div>
                                    <div v-else 
                                         class="relative w-full h-full rounded-full overflow-hidden border-4 border-white/80 dark:border-gray-800/80 shadow-2xl backdrop-blur-sm bg-gradient-to-br from-gray-200 to-gray-300 dark:from-gray-700 dark:to-gray-800 flex items-center justify-center">
                                        <!-- Inner glow border -->
                                        <div class="absolute inset-0 rounded-full border-2 border-white/50 dark:border-white/30 shadow-inner"></div>
                                        <span class="text-6xl font-bold text-gray-600 dark:text-gray-400 relative z-10">
                                            {{ heroData?.name?.charAt(0) || 'Y' }}
                                        </span>
                                    </div>
                                    
                                    <!-- Floating particles/light spots -->
                                    <div class="absolute -top-2 -right-2 h-6 w-6 bg-white rounded-full blur-sm opacity-60 animate-bounce"></div>
                                    <div class="absolute -bottom-2 -left-2 h-4 w-4 bg-blue-300 rounded-full blur-sm opacity-60 animate-bounce delay-300"></div>
                                    <div class="absolute top-1/2 -right-3 h-3 w-3 bg-purple-300 rounded-full blur-sm opacity-60 animate-ping"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Scroll Indicator -->
                    <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2">
                        <a href="#about" 
                           @click.prevent="scrollToSection('about')"
                           class="animate-bounce">
                            <svg class="h-8 w-8 text-gray-400 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                            </svg>
                        </a>
                    </div>
                </section>

                <!-- About Section -->
                <section id="about" class="py-16 md:py-24">
                    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div class="text-center mb-16">
                            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-4">About Me</h2>
                            <p class="text-lg text-gray-600 dark:text-gray-400 max-w-3xl mx-auto">
                                {{ aboutData?.description || 'A brief introduction about yourself and your expertise' }}
                            </p>
                        </div>
                        
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-start">
                            <!-- Profile Image -->
                            <div class="relative flex justify-center lg:justify-end items-center">
                                <div class="relative w-64 h-64 md:w-96 md:h-96">
                                    <div class="absolute inset-0 bg-gradient-to-r from-blue-400 to-cyan-400 dark:from-blue-500 dark:to-cyan-500 rounded-lg transform rotate-3"></div>
                                    <div v-if="aboutData?.profile_image" 
                                         class="relative w-full h-full rounded-lg overflow-hidden border-8 border-white dark:border-gray-800 shadow-2xl">
                                        <img :src="aboutData.profile_image" 
                                             alt="About"
                                             class="w-full h-full object-cover">
                                    </div>
                                    <div v-else 
                                         class="relative w-full h-full rounded-lg overflow-hidden border-8 border-white dark:border-gray-800 shadow-2xl bg-gradient-to-br from-gray-200 to-gray-300 dark:from-gray-700 dark:to-gray-800 flex items-center justify-center">
                                        <svg class="h-32 w-32 text-gray-400 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- About Content -->
                            <div class="flex flex-col justify-center text-left">
                                <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">{{ aboutData?.title || 'About Me' }}</h3>
                                <p class="text-lg text-gray-600 dark:text-gray-300 mb-6 italic">
                                    "{{ aboutData?.bio || 'Your professional bio or tagline' }}"
                                </p>
                                <p class="text-gray-700 dark:text-gray-400 mb-8 leading-relaxed">
                                    {{ aboutData?.extended_bio || 'Detailed description about your experience, skills, and achievements...' }}
                                </p>
                                
                                <div v-if="aboutData?.personal_details" class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-8">
                                    <div v-for="(side, sideName) in aboutData.personal_details" :key="sideName">
                                        <div v-for="detail in side" :key="detail.label" class="mb-4">
                                            <div class="grid grid-cols-3 gap-4 mb-4">
                                                <span class="font-medium text-gray-900 dark:text-white col-span-1">{{ detail.label }}:</span>
                                                <span class="text-gray-600 dark:text-gray-400 col-span-2 break-words">{{ detail.value }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="flex space-x-4">
                                    <a href="#contact" 
                                       @click.prevent="scrollToSection('contact')"
                                       class="px-6 py-3 bg-indigo-600 dark:bg-indigo-500 text-white rounded-lg hover:bg-indigo-700 dark:hover:bg-indigo-600 transition-colors font-medium">
                                        Contact Me
                                    </a>
                                    <a href="#portfolio" 
                                       @click.prevent="scrollToSection('portfolio')"
                                       class="px-6 py-3 bg-white dark:bg-gray-800 text-gray-900 dark:text-white border border-gray-300 dark:border-gray-700 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors font-medium">
                                        View Portfolio
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Stats Section -->
                <section id="stats" class="py-12 md:py-5 bg-gray-50 dark:bg-gray-800/50" ref="statsSection">
                    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div class="text-center mb-16">
                            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-4">Statistics</h2>
                            <p class="text-lg text-gray-600 dark:text-gray-400 max-w-3xl mx-auto">
                                {{ 'My Works So Far' }}
                            </p>
                        </div>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                            <div v-for="stat in statistics" :key="stat.id" class="text-center">
                                <div class="mb-4 flex justify-center">
                                    <div class="relative">
                                        <!-- Blue circle background -->
                                        <div class="absolute inset-0 bg-blue-500 rounded-full transform scale-110"></div>
                                        <!-- Icon container with blue circle -->
                                        <div class="relative w-16 h-16 bg-blue-500 rounded-full flex items-center justify-center">
                                            <i :class="stat.icon" class="text-2xl text-white"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-2xl md:text-2xl font-bold text-black dark:text-white mb-2">
                                    {{ animatedStats[stat.id]?.toLocaleString('en-US') || 0 }}
                                </div>
                                <div class="text-lg text-black/90 dark:text-white">{{ stat.label }}</div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Skills Section -->
                <section id="skills" class="py-16 md:py-24">
                    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div class="text-center mb-16">
                            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-4">Skills</h2>
                        </div>
                        
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                            <!-- Left Column -->
                            <div>
                                <div v-for="skill in leftSkills" :key="skill.id" class="mb-8">
                                    <div class="flex justify-between items-center mb-2">
                                        <span class="font-medium text-gray-900 dark:text-white">{{ skill.name }}</span>
                                        <span class="text-sm font-medium text-indigo-600 dark:text-indigo-400">{{ skill.percentage }}%</span>
                                    </div>
                                    <div class="h-2 bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden">
                                        <div class="h-full bg-gradient-to-r from-indigo-500 to-purple-500 dark:from-indigo-400 dark:to-purple-400 rounded-full transition-all duration-1000"
                                             :style="{ width: skill.percentage + '%' }">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Right Column -->
                            <div>
                                <div v-for="skill in rightSkills" :key="skill.id" class="mb-8">
                                    <div class="flex justify-between items-center mb-2">
                                        <span class="font-medium text-gray-900 dark:text-white">{{ skill.name }}</span>
                                        <span class="text-sm font-medium text-green-600 dark:text-green-400">{{ skill.percentage }}%</span>
                                    </div>
                                    <div class="h-2 bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden">
                                        <div class="h-full bg-gradient-to-r from-green-500 to-teal-500 dark:from-green-400 dark:to-teal-400 rounded-full transition-all duration-1000"
                                             :style="{ width: skill.percentage + '%' }">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Resume Section -->
                <section id="resume" class="py-16 md:py-24 bg-gray-50 dark:bg-gray-800/50">
                    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div class="text-center mb-16">
                            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-4">Resume</h2>
                        </div>
                        
                        <!-- Summary -->
                        <div v-if="summarySections.length > 0" class="mb-12">
                            <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-8 text-center">Sumary</h3>
                            <div class="max-w-4xl mx-auto">
                                <div v-for="section in summarySections" :key="section.id" 
                                     class="bg-white dark:bg-gray-800 rounded-xl p-8 shadow-lg">
                                    <h4 class="text-xl font-bold text-gray-900 dark:text-white mb-4">{{ section.title || heroData?.name }}</h4>
                                    <p class="text-gray-700 dark:text-gray-300 mb-6 italic">{{ section.description }}</p>
                                    <ul v-if="section.details && section.details.length > 0" class="space-y-2">
                                        <li v-for="(detail, index) in section.details" :key="index" 
                                            class="flex items-start text-gray-600 dark:text-gray-400">
                                            <svg class="h-5 w-5 text-indigo-500 dark:text-indigo-400 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                            </svg>
                                            {{ detail }}
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                            <!-- Education -->
                            <div>
                                <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-8">Education</h3>
                                <div class="relative">
                                    <div class="absolute left-4 top-0 bottom-0 w-0.5 bg-gray-200 dark:bg-gray-700"></div>
                                    <div v-for="(edu, index) in educationSections" :key="edu.id" 
                                         :class="['relative pl-12 pb-8', index === educationSections.length - 1 ? '' : 'border-b border-gray-200 dark:border-gray-700']">
                                        <div class="absolute left-0 top-0 h-8 w-8 rounded-full bg-indigo-500 dark:bg-indigo-400 flex items-center justify-center">
                                            <svg class="h-4 w-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z M12 14l9-5-9-5-9 5 9 5z M12 14v6l9-5M12 14l-9-5v10l9 5"></path>
                                            </svg>
                                        </div>
                                        <div class="mb-2">
                                            <h4 class="text-lg font-bold text-gray-900 dark:text-white">{{ edu.title }}</h4>
                                            <p class="text-gray-600 dark:text-gray-400">{{ edu.subtitle || edu.institution }}</p>
                                        </div>
                                        <p class="text-sm text-indigo-600 dark:text-indigo-400 mb-3">{{ edu.period }}</p>
                                        <p class="text-gray-700 dark:text-gray-300 mb-3">{{ edu.description }}</p>
                                        <ul v-if="edu.details && edu.details.length > 0" class="space-y-1">
                                            <li v-for="(detail, detailIndex) in edu.details" :key="detailIndex" 
                                                class="text-sm text-gray-600 dark:text-gray-400 flex items-start">
                                                <span class="text-indigo-500 dark:text-indigo-400 mr-2">•</span>
                                                {{ detail }}
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Experience -->
                            <div>
                                <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-8">Experience</h3>
                                <div class="relative">
                                    <div class="absolute left-4 top-0 bottom-0 w-0.5 bg-gray-200 dark:bg-gray-700"></div>
                                    <div v-for="(exp, index) in experienceSections" :key="exp.id" 
                                         :class="['relative pl-12 pb-8', index === experienceSections.length - 1 ? '' : 'border-b border-gray-200 dark:border-gray-700']">
                                        <div class="absolute left-0 top-0 h-8 w-8 rounded-full bg-green-500 dark:bg-green-400 flex items-center justify-center">
                                            <svg class="h-4 w-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                        <div class="mb-2">
                                            <h4 class="text-lg font-bold text-gray-900 dark:text-white">{{ exp.title }}</h4>
                                            <p class="text-gray-600 dark:text-gray-400">{{ exp.subtitle || exp.institution }}</p>
                                        </div>
                                        <p class="text-sm text-green-600 dark:text-green-400 mb-3">{{ exp.period }}</p>
                                        <p class="text-gray-700 dark:text-gray-300 mb-3">{{ exp.description }}</p>
                                        <ul v-if="exp.details && exp.details.length > 0" class="space-y-1">
                                            <li v-for="(detail, detailIndex) in exp.details" :key="detailIndex" 
                                                class="text-sm text-gray-600 dark:text-gray-400 flex items-start">
                                                <span class="text-green-500 dark:text-green-400 mr-2">•</span>
                                                {{ detail }}
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Portfolio Section -->
                <section id="portfolio" class="py-16 md:py-24">
                    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div class="text-center mb-16">
                            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-4">Portfolio</h2>
                        </div>
                        
                        <!-- Filter Buttons -->
                        <div class="flex flex-wrap justify-center gap-4 mb-12">
                            <button v-for="category in portfolioCategories" 
                                    :key="category.value"
                                    @click="activePortfolioCategory = category.value"
                                    :class="[
                                        'px-6 py-2 rounded-full text-sm font-medium transition-all',
                                        activePortfolioCategory === category.value
                                            ? 'bg-indigo-600 text-white dark:bg-indigo-500'
                                            : 'bg-gray-100 text-gray-700 hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700'
                                    ]">
                                {{ category.label }}
                            </button>
                        </div>
                        
                        <!-- Portfolio Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                            <div v-for="item in filteredPortfolio" :key="item.id"
                                 class="group bg-white dark:bg-gray-800 rounded-xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-300 hover:-translate-y-2">
                                <div class="relative overflow-hidden h-56">
                                    <img :src="item.image" 
                                         :alt="item.title"
                                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                    <div class="absolute bottom-4 left-4">
                                        <span :class="[
                                            'px-3 py-1 text-xs font-medium rounded-full',
                                            getCategoryColor(item.category)
                                        ]">
                                            {{ getCategoryLabel(item.category) }}
                                        </span>
                                    </div>
                                    <div class="absolute top-4 right-4 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                        <a v-if="item.details_url" 
                                           :href="item.details_url" 
                                           target="_blank"
                                           class="h-10 w-10 bg-white dark:bg-gray-900 rounded-full flex items-center justify-center shadow-lg">
                                            <svg class="h-5 w-5 text-gray-700 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                                <div class="p-6">
                                    <h4 class="text-xl font-bold text-gray-900 dark:text-white mb-3">{{ item.title }}</h4>
                                    <p class="text-gray-600 dark:text-gray-400 mb-4 line-clamp-2">{{ item.description }}</p>
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm text-gray-500 dark:text-gray-400">{{ formatDate(item.created_at) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Empty State -->
                        <div v-if="filteredPortfolio.length === 0" class="text-center py-12">
                            <div class="h-16 w-16 mx-auto bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center mb-4">
                                <svg class="h-8 w-8 text-gray-400 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                                </svg>
                            </div>
                            <p class="text-gray-500 dark:text-gray-400">No projects found in this category</p>
                        </div>
                    </div>
                </section>

                <!-- Services Section -->
                <section id="services" class="py-16 md:py-24 bg-gray-50 dark:bg-gray-800/50">
                    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div class="text-center mb-16">
                            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-4">Services</h2>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                            <div v-for="service in services" :key="service.id"
                                 :class="[
                                     'group bg-white dark:bg-gray-800 rounded-xl p-8 shadow-lg hover:shadow-2xl transition-all duration-300 hover:-translate-y-2',
                                     getServiceColor(service.color).bg
                                 ]">
                                <div class="mb-6">
                                    <div :class="[
                                        'inline-flex h-16 w-16 items-center justify-center rounded-2xl mb-4',
                                        getServiceColor(service.color).iconBg
                                    ]">
                                        <i :class="[service.icon, getServiceColor(service.color).icon]" class="text-2xl"></i>
                                    </div>
                                </div>
                                <h4 class="text-xl font-bold text-gray-900 dark:text-white mb-4">{{ service.title }}</h4>
                                <p class="text-gray-600 dark:text-gray-300 mb-6">{{ service.description }}</p>
                                
                                <!-- Learn More Link with Mailto -->
                                <div class="flex items-center text-indigo-600 dark:text-indigo-400 font-medium cursor-pointer group/link"
                                     @click="sendServiceInquiry(service.title)"
                                     @keyup.enter="sendServiceInquiry(service.title)"
                                     tabindex="0"
                                     role="button">
                                    <span class="group-hover/link:underline">Learn more</span>
                                    <svg class="h-5 w-5 ml-2 transform group-hover/link:translate-x-2 transition-transform" 
                                         fill="none" 
                                         stroke="currentColor" 
                                         viewBox="0 0 24 24">
                                        <path stroke-linecap="round" 
                                              stroke-linejoin="round" 
                                              stroke-width="2" 
                                              d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Testimonials Section -->
                <section id="testimonials" class="py-16 md:py-24">
                    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div class="text-center mb-16">
                            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-4">Testimonials</h2>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                            <div v-for="testimonial in testimonials" :key="testimonial.id"
                                 class="bg-white dark:bg-gray-800 rounded-xl p-8 shadow-lg hover:shadow-xl transition-all duration-300">
                                <div class="flex items-center mb-6">
                                    <div v-if="testimonial.image" class="h-12 w-12 rounded-full overflow-hidden mr-4">
                                        <img :src="testimonial.image" 
                                             :alt="testimonial.name"
                                             class="w-full h-full object-cover">
                                    </div>
                                    <div v-else 
                                         class="h-12 w-12 bg-gradient-to-br from-indigo-500 to-purple-500 dark:from-indigo-600 dark:to-purple-600 rounded-full flex items-center justify-center mr-4">
                                        <span class="text-white font-bold">{{ testimonial.name?.charAt(0) }}</span>
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-gray-900 dark:text-white">{{ testimonial.name }}</h4>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ testimonial.position }}</p>
                                    </div>
                                </div>
                                
                                <div class="mb-4">
                                    <div class="flex">
                                        <svg v-for="star in 5" :key="star"
                                             :class="[
                                                 'h-5 w-5',
                                                 star <= testimonial.rating 
                                                     ? 'text-yellow-400 fill-current' 
                                                     : 'text-gray-300 dark:text-gray-600'
                                             ]">
                                            <use xlink:href="#star" />
                                        </svg>
                                    </div>
                                </div>
                                
                                <p class="text-gray-700 dark:text-gray-300 italic">
                                    "{{ testimonial.content }}"
                                </p>
                            </div>
                        </div>
                        
                        <!-- Hidden SVG for stars -->
                        <svg class="hidden">
                            <symbol id="star" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </symbol>
                        </svg>
                    </div>
                </section>

                <!-- Contact Section -->
                <section id="contact" class="py-16 md:py-24 bg-gray-50 dark:bg-gray-800/50">
                    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div class="text-center mb-16">
                            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-4">Contact</h2>
                        </div>
                        
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                            <!-- Contact Information -->
                            <div class="lg:col-span-1">
                                <div class="grid grid-cols-1 gap-6">
                                    <div v-for="contact in contactInfo" :key="contact.id"
                                         class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-lg hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                                        <div class="flex items-start">
                                            <div :class="[
                                                'h-12 w-12 rounded-lg flex items-center justify-center mr-4 flex-shrink-0',
                                                getContactColor(contact.type)
                                            ]">
                                                <i :class="[contact.icon, 'text-white text-lg']"></i>
                                            </div>
                                            <div class="min-w-0 flex-1 overflow-hidden">
                                                <h4 class="font-bold text-gray-900 dark:text-white mb-2 truncate">{{ contact.title }}</h4>
                                                
                                                <!-- Make email clickable with mailto -->
                                                <a v-if="contact.type === 'email'"
                                                   :href="`mailto:${contact.value}`"
                                                   class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 transition-colors break-words word-break-break-word hover:underline"
                                                   target="_blank"
                                                   rel="noopener noreferrer">
                                                    {{ contact.value }}
                                                </a>
                                                
                                                <!-- Regular text for other contact types -->
                                                <p v-else class="text-gray-600 dark:text-gray-400 break-words word-break-break-word">
                                                    {{ contact.value }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Contact Form -->
                            <div class="lg:col-span-2">
                                <div class="bg-white dark:bg-gray-800 rounded-xl p-8 shadow-lg">
                                    <form @submit.prevent="submitContactForm" class="space-y-6">
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Your Name *</label>
                                                <input v-model="contactForm.name"
                                                       type="text"
                                                       required
                                                       class="w-full px-4 py-3 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-900 dark:text-white transition-colors"
                                                       placeholder="John Doe"
                                                       :class="{'border-red-500': contactForm.errors.name}">
                                                <p v-if="contactForm.errors.name" class="text-red-500 text-xs mt-1">{{ contactForm.errors.name }}</p>
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Your Email *</label>
                                                <input v-model="contactForm.email"
                                                       type="email"
                                                       required
                                                       class="w-full px-4 py-3 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-900 dark:text-white transition-colors"
                                                       placeholder="john@example.com"
                                                       :class="{'border-red-500': contactForm.errors.email}">
                                                <p v-if="contactForm.errors.email" class="text-red-500 text-xs mt-1">{{ contactForm.errors.email }}</p>
                                            </div>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Subject *</label>
                                            <input v-model="contactForm.subject"
                                                   type="text"
                                                   required
                                                   class="w-full px-4 py-3 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-900 dark:text-white transition-colors"
                                                   placeholder="Project Inquiry"
                                                   :class="{'border-red-500': contactForm.errors.subject}">
                                            <p v-if="contactForm.errors.subject" class="text-red-500 text-xs mt-1">{{ contactForm.errors.subject }}</p>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Message *</label>
                                            <textarea v-model="contactForm.message"
                                                      rows="5"
                                                      required
                                                      class="w-full px-4 py-3 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-900 dark:text-white transition-colors resize-none"
                                                      placeholder="Tell me about your project..."
                                                      :class="{'border-red-500': contactForm.errors.message}"></textarea>
                                            <p v-if="contactForm.errors.message" class="text-red-500 text-xs mt-1">{{ contactForm.errors.message }}</p>
                                        </div>
                                        <button type="submit"
                                                :disabled="contactForm.loading"
                                                :class="[
                                                    'w-full px-6 py-3 rounded-lg font-medium transition-all duration-300',
                                                    contactForm.loading
                                                        ? 'bg-indigo-400 dark:bg-indigo-500 cursor-not-allowed'
                                                        : 'bg-indigo-600 dark:bg-indigo-500 hover:bg-indigo-700 dark:hover:bg-indigo-600 hover:shadow-lg'
                                                ]">
                                            <span v-if="contactForm.loading" class="flex items-center justify-center">
                                                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                </svg>
                                                Preparing Email...
                                            </span>
                                            <span v-else class="flex items-center justify-center">
                                                <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                                </svg>
                                                Send Message
                                            </span>
                                        </button>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 text-center">
                                            Clicking "Send Message" will open your email client with a pre-filled message to abhishekchowdhury054@gmail.com
                                        </p>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Footer -->
                <footer class="bg-gray-900 dark:bg-black pt-8 pb-6">
                    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div class="text-center mb-6">
                            <a href="#" class="text-xl font-bold text-white mb-3 inline-block">
                                <span class="text-indigo-400">{{ siteSettings?.site_name || 'Portfolio' }}</span>
                            </a>
                            <p class="text-gray-400 max-w-2xl mx-auto mb-6 text-sm">
                                {{ siteSettings?.site_description || 'Et aut eum quis fuga eos sunt ipsa nihil. Labore corporis magni eligendi fuga maxime saepe commodi placeat.' }}
                            </p>
                            
                            <!-- Social Links -->
                            <div v-if="siteSettings?.footer_social_links?.length > 0" class="flex justify-center space-x-4 mb-6">
                                <a v-for="social in siteSettings.footer_social_links" 
                                   :key="social.platform"
                                   :href="social.url"
                                   target="_blank"
                                   class="h-10 w-10 bg-gray-800 rounded-full flex items-center justify-center hover:bg-indigo-600 transition-colors"
                                   :title="social.platform">
                                    <i v-if="social.icon" :class="social.icon" class="text-sm text-gray-300"></i>
                                    <span v-else class="text-sm font-medium text-gray-300">{{ social.platform?.charAt(0) }}</span>
                                </a>
                            </div>
                        </div>
                        
                        <!-- Copyright -->
                        <div class="border-t border-gray-800 pt-6">
                            <div class="flex flex-col md:flex-row justify-between items-center">
                                <p class="text-gray-400 text-xs mb-3 md:mb-0">
                                    © {{ new Date().getFullYear() }} {{ siteSettings?.copyright_name || 'Your Name' }}. {{ siteSettings?.copyright_text || 'All Rights Reserved' }}
                                </p>
                                <div class="text-gray-400 text-xs">
                                    <span>{{ siteSettings?.distributed_by_text || 'Designed by' }} </span>
                                    <a :href="siteSettings?.designer_url || '#'" 
                                       class="text-indigo-400 hover:text-indigo-300 transition-colors">
                                        {{ siteSettings?.designer_name || 'Designer' }}
                                    </a>
                                    <span class="mx-2">•</span>
                                    <a :href="siteSettings?.distributor_url || '#'" 
                                       class="text-indigo-400 hover:text-indigo-300 transition-colors">
                                        {{ siteSettings?.distributor_name || 'Distributor' }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </footer>

                <!-- Back to Top -->
                <button v-show="showBackToTop"
                        @click="scrollToTop"
                        class="fixed bottom-8 right-8 h-12 w-12 bg-indigo-600 dark:bg-indigo-500 text-white rounded-full shadow-lg hover:bg-indigo-700 dark:hover:bg-indigo-600 transition-all hover:shadow-xl flex items-center justify-center z-40">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m7-7v18"></path>
                    </svg>
                </button>

                <!-- Success Toast -->
                <div v-if="contactForm.success" 
                     class="fixed top-4 right-4 z-50 px-6 py-4 bg-green-500 text-white rounded-lg shadow-lg transform transition-all duration-300 translate-x-0">
                    <div class="flex items-center">
                        <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <div>
                            <span class="font-medium">Email ready to send!</span>
                            <p class="text-sm opacity-90 mt-1">Your email client has opened with a pre-filled message.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, reactive, computed, onMounted, onUnmounted, watch, nextTick } from 'vue';
import axios from 'axios';

// Theme Management
const isDark = ref(false);
const isSidebarOpen = ref(false);
const windowWidth = ref(window.innerWidth);

const toggleTheme = () => {
    isDark.value = !isDark.value;
    if (isDark.value) {
        document.documentElement.classList.add('dark');
        localStorage.setItem('theme', 'dark');
    } else {
        document.documentElement.classList.remove('dark');
        localStorage.setItem('theme', 'light');
    }
};

// Typewriter Variables
const currentRole = ref('');
const currentRoleIndex = ref(0);
const isDeleting = ref(false);
const isWaiting = ref(false);
const typingSpeed = ref(100);
const deletingSpeed = ref(50);
const waitTime = ref(1500);
let typewriterTimer = null;
const statsSection = ref(null);
const animatedStats = reactive({});
const hasAnimatedStats = ref(false);

const animateStats = () => {
    if (hasAnimatedStats.value || !statsSection.value) return;
    
    const sectionTop = statsSection.value.getBoundingClientRect().top;
    const windowHeight = window.innerHeight;
    
    // Start animation when section is in view
    if (sectionTop < windowHeight - 100) {
        hasAnimatedStats.value = true;
        
        statistics.value.forEach(stat => {
            const startValue = 0;
            const endValue = Number(stat.value);
            const duration = 500; // 2 seconds
            const startTime = Date.now();
            
            const updateCounter = () => {
                const currentTime = Date.now();
                const elapsed = currentTime - startTime;
                
                if (elapsed < duration) {
                    const progress = elapsed / duration;
                    // Use easing function for smooth animation
                    const easeOutQuart = 1 - Math.pow(1 - progress, 4);
                    const currentValue = Math.floor(easeOutQuart * endValue);
                    
                    animatedStats[stat.id] = currentValue;
                    requestAnimationFrame(updateCounter);
                } else {
                    animatedStats[stat.id] = endValue;
                }
            };
            
            updateCounter();
        });
    }
};

// Typewriter Function
const typeWriter = () => {
    if (!heroData.value?.roles || heroData.value.roles.length === 0) {
        currentRole.value = 'Your Profession';
        return;
    }

    const roles = heroData.value.roles;
    const fullText = roles[currentRoleIndex.value];
    
    if (isDeleting.value) {
        // Deleting text
        currentRole.value = fullText.substring(0, currentRole.value.length - 1);
        typingSpeed.value = deletingSpeed.value;
        
        if (currentRole.value === '') {
            isDeleting.value = false;
            currentRoleIndex.value = (currentRoleIndex.value + 1) % roles.length;
            // Add a small pause before typing next role
            setTimeout(() => {
                typeWriter();
            }, 500);
            return;
        }
    } else {
        // Typing text
        currentRole.value = fullText.substring(0, currentRole.value.length + 1);
        typingSpeed.value = 100;
        
        if (currentRole.value === fullText) {
            // Text completed, wait then start deleting
            isWaiting.value = true;
            setTimeout(() => {
                isWaiting.value = false;
                isDeleting.value = true;
                typeWriter();
            }, waitTime.value);
            return;
        }
    }
    
    // Continue typing/deleting
    typewriterTimer = setTimeout(typeWriter, typingSpeed.value);
};

// Start typewriter effect
const startTypewriter = () => {
    if (typewriterTimer) {
        clearTimeout(typewriterTimer);
    }
    currentRole.value = '';
    currentRoleIndex.value = 0;
    isDeleting.value = false;
    isWaiting.value = false;
    typingSpeed.value = 100;
    typeWriter();
};

// Handle window resize
const handleResize = () => {
    windowWidth.value = window.innerWidth;
    // Auto-close sidebar on mobile when switching to desktop
    if (windowWidth.value >= 768) {
        isSidebarOpen.value = true;
    } else {
        isSidebarOpen.value = false;
    }
};

// Navigation
const navigation = [
    { name: 'Home', href: '#home', id: 'home' },
    { name: 'About', href: '#about', id: 'about' },
    { name: 'Skills', href: '#skills', id: 'skills' },
    { name: 'Resume', href: '#resume', id: 'resume' },
    { name: 'Portfolio', href: '#portfolio', id: 'portfolio' },
    { name: 'Services', href: '#services', id: 'services' },
    { name: 'Testimonials', href: '#testimonials', id: 'testimonials' },
    { name: 'Contact', href: '#contact', id: 'contact' }
];

// Helper function to get navigation icons
const getNavIcon = (navName) => {
    const icons = {
        'Home': 'bi bi-house',
        'About': 'bi bi-person',
        'Skills': 'bi bi-file-bar-graph',
        'Resume': 'bi bi-file-earmark-text',
        'Portfolio': 'bi bi-images',
        'Services': 'bi bi-hdd-stack',
        'Testimonials': 'bi bi-chat-square-quote',
        'Contact': 'bi bi-envelope'
    };
    return icons[navName] || 'bi bi-circle';
};

// Initialize reactive data with empty objects/arrays
const heroData = ref({});
const aboutData = ref({});
const skills = ref([]);
const statistics = ref([]);
const resumeSections = ref([]);
const portfolioItems = ref([]);
const services = ref([]);
const testimonials = ref([]);
const contactInfo = ref([]);
const siteSettings = ref({});
const loading = ref(true);
const activeSection = ref('home');
const showBackToTop = ref(false);
const activePortfolioCategory = ref('all');

// Portfolio Categories
const portfolioCategories = [
    { label: 'All', value: 'all' },
    { label: 'Web Apps', value: 'app' },
    { label: 'Mobile Apps', value: 'mobile' },
    { label: 'Websites', value: 'website' },
    { label: 'UI/UX Design', value: 'design' },
    { label: 'Branding', value: 'branding' },
    { label: 'Graphic Design', value: 'graphic' },
    { label: 'E-commerce', value: 'ecommerce' },
    { label: 'Other', value: 'other' }
];

// Contact Form - Updated with error handling
const contactForm = reactive({
    name: '',
    email: '',
    subject: '',
    message: '',
    loading: false,
    success: false,
    errors: {}
});

// Computed Properties
const leftSkills = computed(() => {
    return skills.value.filter(skill => skill.column === 'left');
});

const rightSkills = computed(() => {
    return skills.value.filter(skill => skill.column === 'right');
});

const summarySections = computed(() => {
    return resumeSections.value.filter(section => section.type === 'summary');
});

const educationSections = computed(() => {
    return resumeSections.value.filter(section => section.type === 'education');
});

const experienceSections = computed(() => {
    return resumeSections.value.filter(section => section.type === 'experience');
});

const filteredPortfolio = computed(() => {
    if (activePortfolioCategory.value === 'all') {
        return portfolioItems.value;
    }
    return portfolioItems.value.filter(item => item.category === activePortfolioCategory.value);
});

// Helper Functions
const formatNumber = (num) => {
    return Number(num).toLocaleString('en-US');
};

const formatDate = (dateString) => {
    try {
        const date = new Date(dateString);
        return date.toLocaleDateString('en-US', { 
            year: 'numeric', 
            month: 'short' 
        });
    } catch {
        return 'Recently';
    }
};

const getCategoryColor = (category) => {
    const colors = {
        app: 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
        mobile: 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
        website: 'bg-teal-100 text-teal-800 dark:bg-teal-900 dark:text-teal-200',
        design: 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200',
        branding: 'bg-pink-100 text-pink-800 dark:bg-pink-900 dark:text-pink-200',
        graphic: 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
        ecommerce: 'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200',
        other: 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200'
    };
    return colors[category] || colors.other;
};

const getCategoryLabel = (category) => {
    const labels = {
        app: 'Web App',
        mobile: 'Mobile App',
        website: 'Website',
        design: 'UI/UX Design',
        branding: 'Branding',
        graphic: 'Graphic Design',
        ecommerce: 'E-commerce',
        other: 'Other'
    };
    return labels[category] || category;
};

const getServiceColor = (color) => {
    const colors = {
        cyan: {
            bg: 'hover:border-cyan-100 dark:hover:border-cyan-900/30',
            iconBg: 'bg-cyan-100 dark:bg-cyan-900/30',
            icon: 'text-cyan-600 dark:text-cyan-400'
        },
        orange: {
            bg: 'hover:border-orange-100 dark:hover:border-orange-900/30',
            iconBg: 'bg-orange-100 dark:bg-orange-900/30',
            icon: 'text-orange-600 dark:text-orange-400'
        },
        teal: {
            bg: 'hover:border-teal-100 dark:hover:border-teal-900/30',
            iconBg: 'bg-teal-100 dark:bg-teal-900/30',
            icon: 'text-teal-600 dark:text-teal-400'
        },
        red: {
            bg: 'hover:border-red-100 dark:hover:border-red-900/30',
            iconBg: 'bg-red-100 dark:bg-red-900/30',
            icon: 'text-red-600 dark:text-red-400'
        },
        indigo: {
            bg: 'hover:border-indigo-100 dark:hover:border-indigo-900/30',
            iconBg: 'bg-indigo-100 dark:bg-indigo-900/30',
            icon: 'text-indigo-600 dark:text-indigo-400'
        },
        pink: {
            bg: 'hover:border-pink-100 dark:hover:border-pink-900/30',
            iconBg: 'bg-pink-100 dark:bg-pink-900/30',
            icon: 'text-pink-600 dark:text-pink-400'
        },
        purple: {
            bg: 'hover:border-purple-100 dark:hover:border-purple-900/30',
            iconBg: 'bg-purple-100 dark:bg-purple-900/30',
            icon: 'text-purple-600 dark:text-purple-400'
        },
        green: {
            bg: 'hover:border-green-100 dark:hover:border-green-900/30',
            iconBg: 'bg-green-100 dark:bg-green-900/30',
            icon: 'text-green-600 dark:text-green-400'
        }
    };
    return colors[color] || colors.indigo;
};

const getContactColor = (type) => {
    const colors = {
        address: 'bg-blue-500',
        phone: 'bg-green-500',
        email: 'bg-purple-500'
    };
    return colors[type] || 'bg-indigo-500';
};

// Scroll Functions
const scrollToSection = (sectionId) => {
    const element = document.getElementById(sectionId);
    if (element) {
        const offset = 80;
        const elementPosition = element.offsetTop - offset;
        window.scrollTo({
            top: elementPosition,
            behavior: 'smooth'
        });
    }
    // Close sidebar on mobile after clicking
    if (window.innerWidth < 768) {
        isSidebarOpen.value = false;
    }
};

const scrollToTop = () => {
    window.scrollTo({
        top: 0,
        behavior: 'smooth'
    });
};

const handleScroll = () => {
    // Show/hide back to top button
    showBackToTop.value = window.scrollY > 500;

    // Update active section based on scroll position
    const sections = navigation.map(nav => ({
        id: nav.id,
        element: document.getElementById(nav.id)
    })).filter(section => section.element);

    const scrollPosition = window.scrollY + 100;

    for (let i = sections.length - 1; i >= 0; i--) {
        const section = sections[i];
        if (scrollPosition >= section.element.offsetTop) {
            activeSection.value = section.id;
            break;
        }
    }
    
    // Check if stats section should animate
    if (!hasAnimatedStats.value && statistics.value.length > 0) {
        animateStats();
    }
};

// EMAIL FUNCTIONALITY - MAILTO APPROACH

/**
 * Send inquiry about a specific service using mailto
 * @param {string} serviceName - Name of the service user is interested in
 */
const sendServiceInquiry = (serviceName) => {
    const recipientEmail = 'abhishekchowdhury054@gmail.com';
    const subject = `Inquiry about ${serviceName} Services - Portfolio Website`;
    
    // Create a professional email body template
    const body = `Dear Abhishek,

I visited your portfolio website and I'm very interested in your "${serviceName}" services.

Could you please provide me with more information about:

1. Your process and approach for ${serviceName}
2. Pricing structure and packages available
3. Estimated timelines for delivery
4. Portfolio examples related to ${serviceName}
5. Your current availability

Looking forward to learning more about how we can work together!

Best regards,
[Your Name]

---
This inquiry was sent from your portfolio website.`;
    
    try {
        // Encode the mailto link
        const mailtoLink = `mailto:${recipientEmail}?subject=${encodeURIComponent(subject)}&body=${encodeURIComponent(body)}`;
        
        // Open mail client in new tab
        const mailWindow = window.open(mailtoLink, '_blank');
        
        // If popup blocked, fallback to current window
        if (!mailWindow || mailWindow.closed || typeof mailWindow.closed == 'undefined') {
            window.location.href = mailtoLink;
        }
        
        // Optional: Track the interaction
        console.log(`Service inquiry sent for: ${serviceName}`);
        
    } catch (error) {
        console.error('Error opening email client:', error);
        // Fallback: Show email information
        alert(`Please send an email to ${recipientEmail} with subject: "${subject}"`);
    }
};

/**
 * Validate contact form
 */
const validateContactForm = () => {
    const errors = {};
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    
    // Clear previous errors
    contactForm.errors = {};
    
    // Name validation
    if (!contactForm.name.trim()) {
        errors.name = 'Name is required';
    } else if (contactForm.name.trim().length < 2) {
        errors.name = 'Name must be at least 2 characters';
    }
    
    // Email validation
    if (!contactForm.email.trim()) {
        errors.email = 'Email is required';
    } else if (!emailRegex.test(contactForm.email)) {
        errors.email = 'Please enter a valid email address';
    }
    
    // Subject validation
    if (!contactForm.subject.trim()) {
        errors.subject = 'Subject is required';
    } else if (contactForm.subject.trim().length < 3) {
        errors.subject = 'Subject must be at least 3 characters';
    }
    
    // Message validation
    if (!contactForm.message.trim()) {
        errors.message = 'Message is required';
    } else if (contactForm.message.trim().length < 10) {
        errors.message = 'Message must be at least 10 characters';
    }
    
    // Set errors if any
    if (Object.keys(errors).length > 0) {
        contactForm.errors = errors;
        return false;
    }
    
    return true;
};

/**
 * Submit contact form using mailto
 */
const submitContactForm = async () => {
    // Validate form
    if (!validateContactForm()) {
        return;
    }
    
    contactForm.loading = true;
    contactForm.success = false;
    
    try {
        // Prepare email content
        const recipientEmail = 'abhishekchowdhury054@gmail.com';
        const subject = `Portfolio Contact: ${contactForm.subject}`;
        const currentDate = new Date().toLocaleDateString('en-US', {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
        
        const body = `Dear Abhishek,

You have received a new message from your portfolio website contact form:

─────────────────────
CONTACT INFORMATION
─────────────────────
👤 Name: ${contactForm.name}
📧 Email: ${contactForm.email}
📋 Subject: ${contactForm.subject}
📅 Date: ${currentDate}

─────────────────────
MESSAGE
─────────────────────
${contactForm.message}
─────────────────────

This message was sent from your portfolio contact form.
The sender can be reached at: ${contactForm.email}

---
To reply to this message, simply reply to this email.
Note: This is an automated message from your portfolio website.`;
        
        // Create mailto link
        const mailtoLink = `mailto:${recipientEmail}?subject=${encodeURIComponent(subject)}&body=${encodeURIComponent(body)}`;
        
        // Open mail client in new tab
        const mailWindow = window.open(mailtoLink, '_blank');
        
        // If popup blocked, fallback to current window
        if (!mailWindow || mailWindow.closed || typeof mailWindow.closed == 'undefined') {
            window.location.href = mailtoLink;
        }
        
        // Show success message
        contactForm.success = true;
        
        // Reset form after a short delay
        setTimeout(() => {
            contactForm.name = '';
            contactForm.email = '';
            contactForm.subject = '';
            contactForm.message = '';
            contactForm.errors = {};
            contactForm.success = false;
        }, 5000);
        
        // Optional: Track the submission
        console.log('Contact form submitted successfully');
        
    } catch (error) {
        console.error('Error sending email:', error);
        
        // Show fallback option
        const fallbackMessage = `There was an error opening your email client. 
        
Please send your message manually to:
Email: abhishekchowdhury054@gmail.com
Subject: ${contactForm.subject}

Your message:
${contactForm.message}`;
        
        alert(fallbackMessage);
    } finally {
        contactForm.loading = false;
    }
};

// Fetch Data Functions
const fetchData = async () => {
    try {
        loading.value = true;
        hasAnimatedStats.value = false; // Reset animation flag
        
        // Initialize demo data first to prevent undefined errors
        setDemoData();
        
        // Then try to fetch from API
        try {
            const [
                heroResponse,
                aboutResponse,
                skillsResponse,
                statsResponse,
                resumeResponse,
                portfolioResponse,
                servicesResponse,
                testimonialsResponse,
                contactResponse,
                settingsResponse
            ] = await Promise.all([
                axios.get('/api/hero').catch(() => ({ data: { data: heroData.value } })),
                axios.get('/api/about').catch(() => ({ data: { data: aboutData.value } })),
                axios.get('/api/skills').catch(() => ({ data: { data: skills.value } })),
                axios.get('/api/statistics').catch(() => ({ data: { data: statistics.value } })),
                axios.get('/api/resume-sections').catch(() => ({ data: { data: resumeSections.value } })),
                axios.get('/api/portfolio-items').catch(() => ({ data: { data: portfolioItems.value } })),
                axios.get('/api/services').catch(() => ({ data: { data: services.value } })),
                axios.get('/api/testimonials').catch(() => ({ data: { data: testimonials.value } })),
                axios.get('/api/contact-info').catch(() => ({ data: { data: contactInfo.value } })),
                axios.get('/api/site-settings').catch(() => ({ data: { data: siteSettings.value } }))
            ]);

            // Update with API data if available
            if (heroResponse.data?.data) heroData.value = heroResponse.data.data;
            if (aboutResponse.data?.data) aboutData.value = aboutResponse.data.data;
            if (skillsResponse.data?.data) skills.value = skillsResponse.data.data;
            if (statsResponse.data?.data) statistics.value = statsResponse.data.data;
            if (resumeResponse.data?.data) resumeSections.value = resumeResponse.data.data;
            if (portfolioResponse.data?.data) portfolioItems.value = portfolioResponse.data.data;
            if (servicesResponse.data?.data) services.value = servicesResponse.data.data;
            if (testimonialsResponse.data?.data) testimonials.value = testimonialsResponse.data.data;
            if (contactResponse.data?.data) contactInfo.value = contactResponse.data.data;
            if (settingsResponse.data?.data) siteSettings.value = settingsResponse.data.data;

            // Initialize animatedStats with new data
            statistics.value.forEach(stat => {
                if (!animatedStats[stat.id]) {
                    animatedStats[stat.id] = 0;
                }
            });

        } catch (apiError) {
            console.error('API Error:', apiError);
            // Keep demo data if API fails
            statistics.value.forEach(stat => {
                if (!animatedStats[stat.id]) {
                    animatedStats[stat.id] = 0;
                }
            });
        }

    } catch (error) {
        console.error('Error in fetchData:', error);
    } finally {
        loading.value = false;
        
        // Trigger animation check after data loads
        nextTick(() => {
            setTimeout(() => {
                animateStats();
            }, 500);
        });
    }
};

// Demo data for fallback
const setDemoData = () => {
    heroData.value = {
        name: 'John Doe',
        roles: ['Full Stack Developer', 'UI/UX Designer', 'Freelancer', 'Photographer'],
        social_links: [
            { platform: 'GitHub', url: 'https://github.com', icon: 'fab fa-github' },
            { platform: 'LinkedIn', url: 'https://linkedin.com', icon: 'fab fa-linkedin' },
            { platform: 'Twitter', url: 'https://twitter.com', icon: 'fab fa-twitter' }
        ]
    };

    aboutData.value = {
        title: 'About Me',
        description: 'A passionate developer with expertise in modern web technologies',
        bio: 'Creating beautiful and functional web experiences',
        extended_bio: 'Experienced full-stack developer specializing in modern JavaScript frameworks...',
        personal_details: {
            left: [
                { label: 'Age', value: '28' },
                { label: 'Residence', value: 'USA' }
            ],
            right: [
                { label: 'Freelance', value: 'Available' },
                { label: 'Address', value: 'San Francisco, CA' }
            ]
        }
    };

    skills.value = [
        { id: 1, name: 'JavaScript', percentage: 90, column: 'left' },
        { id: 2, name: 'Vue.js', percentage: 85, column: 'left' },
        { id: 3, name: 'React', percentage: 80, column: 'left' },
        { id: 4, name: 'Node.js', percentage: 75, column: 'right' },
        { id: 5, name: 'UI/UX Design', percentage: 70, column: 'right' },
        { id: 6, name: 'Graphic Design', percentage: 65, column: 'right' }
    ];

    statistics.value = [
        { id: 1, icon: 'fas fa-code', value: 50, label: 'Projects Completed' },
        { id: 2, icon: 'fas fa-users', value: 30, label: 'Happy Clients' },
        { id: 3, icon: 'fas fa-award', value: 5, label: 'Awards Won' },
        { id: 4, icon: 'fas fa-clock', value: 2000, label: 'Hours Worked' }
    ];

    resumeSections.value = [
        { 
            id: 1, 
            type: 'summary', 
            title: 'Professional Summary',
            description: 'Innovative and deadline-driven Full Stack Developer with 3+ years of experience designing and developing user-centered web applications.',
            details: ['San Francisco, CA', '(123) 456-7891', 'john.doe@example.com']
        },
        { 
            id: 2, 
            type: 'education', 
            title: 'Master of Computer Science',
            institution: 'Stanford University',
            period: '2015 - 2017',
            description: 'Specialized in Web Technologies and User Experience'
        }
    ];

    portfolioItems.value = [
        {
            id: 1,
            title: 'E-commerce Platform',
            category: 'app',
            image: 'https://images.unsplash.com/photo-1556742049-0cfed4f6a45d?w=800&auto=format&fit=crop',
            description: 'Full-featured online shopping platform',
            created_at: new Date().toISOString()
        },
        {
            id: 2,
            title: 'Mobile Banking App',
            category: 'mobile',
            image: 'https://images.unsplash.com/photo-1563013544-824ae1b704d3?w=800&auto=format&fit=crop',
            description: 'Secure banking application for iOS and Android',
            created_at: new Date().toISOString()
        }
    ];

    services.value = [
        {
            id: 1,
            title: 'Web Development',
            icon: 'fas fa-code',
            color: 'indigo',
            description: 'Custom web applications using modern technologies'
        },
        {
            id: 2,
            title: 'UI/UX Design',
            icon: 'fas fa-paint-brush',
            color: 'purple',
            description: 'User-centered design for optimal user experience'
        },
        {
            id: 3,
            title: 'Mobile App Development',
            icon: 'fas fa-mobile-alt',
            color: 'green',
            description: 'Native and cross-platform mobile applications'
        },
        {
            id: 4,
            title: 'E-commerce Solutions',
            icon: 'fas fa-shopping-cart',
            color: 'orange',
            description: 'Online stores and payment integrations'
        },
        {
            id: 5,
            title: 'SEO Optimization',
            icon: 'fas fa-search',
            color: 'teal',
            description: 'Search engine optimization and digital marketing'
        },
        {
            id: 6,
            title: 'Branding & Graphics',
            icon: 'fas fa-palette',
            color: 'pink',
            description: 'Logo design and brand identity development'
        }
    ];

    testimonials.value = [
        {
            id: 1,
            name: 'Sarah Johnson',
            position: 'CEO at TechCorp',
            rating: 5,
            content: 'Excellent work! The project was delivered on time and exceeded expectations.'
        },
        {
            id: 2,
            name: 'Michael Chen',
            position: 'Product Manager at StartupX',
            rating: 5,
            content: 'Professional and highly skilled developer. Highly recommended!'
        },
        {
            id: 3,
            name: 'Emily Rodriguez',
            position: 'Marketing Director',
            rating: 5,
            content: 'Great attention to detail and fantastic communication throughout the project.'
        }
    ];

    contactInfo.value = [
        {
            id: 1,
            type: 'address',
            icon: 'fas fa-map-marker-alt',
            title: 'Location',
            value: 'San Francisco, California'
        },
        {
            id: 2,
            type: 'email',
            icon: 'fas fa-envelope',
            title: 'Email',
            value: 'abhishekchowdhury054@gmail.com'
        },
        {
            id: 3,
            type: 'phone',
            icon: 'fas fa-phone',
            title: 'Phone',
            value: '+1 (234) 567-8900'
        }
    ];

    siteSettings.value = {
        site_name: 'My Portfolio',
        site_description: 'A professional portfolio showcasing my work',
        copyright_name: 'John Doe',
        copyright_text: 'All rights reserved'
    };
};

// Lifecycle Hooks
onMounted(() => {
    // Initialize theme
    const savedTheme = localStorage.getItem('theme');
    const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
    isDark.value = savedTheme === 'dark' || (!savedTheme && prefersDark);
    if (isDark.value) {
        document.documentElement.classList.add('dark');
    }

    // Initialize sidebar based on screen size
    if (window.innerWidth >= 768) {
        isSidebarOpen.value = true;
    }

    // Add window resize listener
    window.addEventListener('resize', handleResize);

    // Fetch data
    fetchData().then(() => {
        // Initialize animatedStats with 0 after data is fetched
        statistics.value.forEach(stat => {
            animatedStats[stat.id] = 0;
        });
    });

    // Add scroll listener
    window.addEventListener('scroll', handleScroll);

    // Initial scroll position check
    handleScroll();
    
    // Start typewriter effect after a short delay
    setTimeout(() => {
        startTypewriter();
    }, 1000);
    
    // Initial stats animation check
    setTimeout(() => {
        animateStats();
    }, 1500);
});

onUnmounted(() => {
    window.removeEventListener('scroll', handleScroll);
    window.removeEventListener('resize', handleResize);
    if (typewriterTimer) {
        clearTimeout(typewriterTimer);
    }
});

// Watch for heroData changes to restart typewriter
watch(() => heroData.value?.roles, (newRoles) => {
    if (newRoles && newRoles.length > 0) {
        startTypewriter();
    }
}, { immediate: true });

watch(() => statistics.value, (newStats) => {
    if (newStats && newStats.length > 0) {
        // Reset animation when stats data changes
        hasAnimatedStats.value = false;
        newStats.forEach(stat => {
            animatedStats[stat.id] = 0;
        });
        
        // Trigger animation check after stats update
        nextTick(() => {
            setTimeout(() => {
                animateStats();
            }, 500);
        });
    }
}, { deep: true });
</script>

<style scoped>
/* Custom scrollbar */
::-webkit-scrollbar {
    width: 10px;
}

::-webkit-scrollbar-track {
    background: #f1f1f1;
}

.dark ::-webkit-scrollbar-track {
    background: #1f2937;
}

::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 5px;
}

::-webkit-scrollbar-thumb:hover {
    background: #555;
}

/* Smooth scrolling */
html {
    scroll-behavior: smooth;
}

/* Line clamp for portfolio descriptions */
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Typewriter Styles */
.typewriter-text {
    display: inline-block;
    position: relative;
    padding-right: 8px;
    min-height: 1.2em;
}

.typewriter-cursor {
    display: inline-block;
    color: currentColor;
    animation: blink 1s infinite;
    font-weight: normal;
    margin-left: 2px;
}

@keyframes blink {
    0%, 50% {
        opacity: 1;
    }
    51%, 100% {
        opacity: 0;
    }
}

/* Animation classes */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-in {
    animation: fadeIn 0.6s ease-out;
}

/* Sidebar animations */
nav {
    transition: transform 0.3s ease, width 0.3s ease;
}

nav span, nav h3, nav p {
    transition: opacity 0.3s ease;
}

/* Mobile sidebar behavior */
@media (max-width: 767px) {
    nav {
        width: 280px !important;
    }
    
    .ml-16, .ml-64, .ml-20, .ml-72 {
        margin-left: 0 !important;
    }
}

/* Desktop sidebar behavior */
@media (min-width: 768px) {
    nav {
        transform: translateX(0) !important;
    }
}

/* Ensure sidebar text is visible when expanded */
nav span {
    white-space: nowrap;
}

/* Mobile overlay */
.overlay {
    backdrop-filter: blur(2px);
}

/* Loading spinner */
@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.animate-spin {
    animation: spin 1s linear infinite;
}

/* Smooth transitions */
.transition-all {
    transition: all 0.3s ease;
}

/* Custom focus styles */
:focus {
    outline: 2px solid #4f46e5;
    outline-offset: 2px;
}

.dark :focus {
    outline-color: #6366f1;
}

/* Custom animations for lighting effects */
@keyframes gradient-xy {
    0%, 100% {
        background-position: 0% 50%;
    }
    50% {
        background-position: 100% 50%;
    }
}

@keyframes spin-slow {
    from {
        transform: rotate(0deg);
    }
    to {
        transform: rotate(360deg);
    }
}

@keyframes gradient-x {
    0%, 100% {
        background-position: 0% 50%;
    }
    50% {
        background-position: 100% 50%;
    }
}

.animate-gradient-xy {
    background-size: 400% 400%;
    animation: gradient-xy 3s ease infinite;
}

.animate-spin-slow {
    animation: spin-slow 20s linear infinite;
}

.animate-gradient-x {
    background-size: 200% auto;
    animation: gradient-x 2s ease infinite;
}

/* Smooth animations */
.animate-bounce {
    animation: bounce 2s infinite;
}

.animate-pulse {
    animation: pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

.animate-ping {
    animation: ping 2s cubic-bezier(0, 0, 0.2, 1) infinite;
}

@keyframes bounce {
    0%, 100% {
        transform: translateY(0);
    }
    50% {
        transform: translateY(-10px);
    }
}

@keyframes pulse {
    0%, 100% {
        opacity: 0.7;
    }
    50% {
        opacity: 0.3;
    }
}

@keyframes ping {
    0% {
        transform: scale(1);
        opacity: 0.6;
    }
    75%, 100% {
        transform: scale(1.5);
        opacity: 0;
    }
}

/* Responsive adjustments */
@media (max-width: 640px) {
    .text-4xl {
        font-size: 2.25rem;
    }
    
    .text-3xl {
        font-size: 1.875rem;
    }
    
    .text-2xl {
        font-size: 1.5rem;
    }
}

/* Ensure images don't overflow */
img {
    max-width: 100%;
    height: auto;
}

/* Profile picture lighting effect enhancements */
.backdrop-blur-sm {
    backdrop-filter: blur(4px);
}

.blur-xl {
    filter: blur(24px);
}

.blur-md {
    filter: blur(12px);
}

.blur-sm {
    filter: blur(4px);
}

.delay-300 {
    animation-delay: 300ms;
}

.delay-500 {
    animation-delay: 500ms;
}

.shadow-inner {
    box-shadow: inset 0 2px 4px 0 rgba(0, 0, 0, 0.06);
}

/* Hero section specific adjustments */
.relative.z-10 {
    position: relative;
    z-index: 10;
}

/* Better text overflow handling */
.word-break-break-word {
    word-break: break-word;
}

/* Ensure proper text wrapping */
.break-words {
    overflow-wrap: break-word;
    word-wrap: break-word;
}

/* Prevent text overflow in contact info */
.truncate {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

/* Smooth section transitions */
section {
    scroll-margin-top: 80px; /* For better scroll positioning */
}

/* Improve form input text handling */
input, textarea {
    max-width: 100%;
}

/* Fix for About section alignment */
.mb-0 {
    margin-bottom: 0 !important;
}

/* Ensure contact info items don't shrink */
.flex-shrink-0 {
    flex-shrink: 0;
}

/* Allow flex items to shrink properly */
.min-w-0 {
    min-width: 0;
}

/* Ensure proper overflow handling */
.overflow-hidden {
    overflow: hidden;
}

/* Alignment fixes for Hero and About sections */
.text-left {
    text-align: left !important;
}

.flex.justify-start {
    justify-content: flex-start !important;
}

.flex.justify-end {
    justify-content: flex-end !important;
}

.flex.justify-center {
    justify-content: center !important;
}

/* Grid alignment adjustments */
.grid.grid-cols-1.lg\:grid-cols-2.gap-12.items-start {
    align-items: start;
}

.grid.grid-cols-1.lg\:grid-cols-2.gap-12.items-center {
    align-items: center;
}

.flex.flex-col.justify-center {
    justify-content: center;
}

/* Responsive alignment for mobile */
@media (max-width: 1024px) {
    .text-left,
    .flex.flex-col.justify-center {
        text-align: center;
    }
    
    .flex.justify-center.lg\:justify-end,
    .flex.justify-center.lg\:justify-end {
        justify-content: center !important;
    }
    
    .relative.w-64.h-64.md\:w-96.md\:h-96,
    .relative.w-64.h-64.md\:w-80.md\:h-80 {
        margin-left: auto;
        margin-right: auto;
    }
}

/* Ensure consistent spacing */
.gap-12 {
    gap: 3rem;
}

/* Better vertical centering */
.items-center {
    align-items: center;
}

.items-start {
    align-items: flex-start;
}

/* Fix for flex containers */
.flex {
    display: flex;
}

.flex-col {
    flex-direction: column;
}

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

.text-2xl.font-bold.text-black.dark\:text-white.mb-2 {
    animation: countUp 0.5s ease-out forwards;
}

/* Smooth transitions for number changes */
.text-2xl.font-bold {
    transition: all 0.3s ease;
}

/* Optional: Add a subtle scale effect on completion */
@keyframes pulseComplete {
    0% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.05);
    }
    100% {
        transform: scale(1);
    }
}

.text-2xl.font-bold.text-black.dark\:text-white.mb-2.complete {
    animation: pulseComplete 0.3s ease;
}

/* Enhanced styles for service inquiry links */
.group\/link {
    transition: all 0.3s ease;
    cursor: pointer;
}

.group\/link:hover {
    transform: translateX(4px);
}

.group\/link:focus {
    outline: 2px solid #4f46e5;
    outline-offset: 2px;
    border-radius: 0.25rem;
}

.dark .group\/link:focus {
    outline-color: #6366f1;
}

/* Contact form validation styles */
input:invalid, textarea:invalid {
    border-color: #f87171 !important;
}

input:valid, textarea:valid {
    border-color: #4ade80 !important;
}

/* Success message animation */
@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.fixed.top-4.right-4.z-50 {
    animation: slideIn 0.3s ease-out;
}

/* Loading state styles */
button[disabled] {
    cursor: not-allowed !important;
    opacity: 0.7 !important;
}

/* Service card hover effects */
.group:hover .transform {
    transform: scale(1.05);
}

/* Responsive adjustments for mailto buttons */
@media (max-width: 640px) {
    .group\/link {
        padding: 0.5rem 0;
    }
    
    .fixed.top-4.right-4.z-50 {
        left: 50%;
        transform: translateX(-50%);
        right: auto;
        width: 90%;
        max-width: 400px;
    }
}

/* Accessibility improvements */
[role="button"] {
    cursor: pointer;
}

[role="button"]:focus-visible {
    outline: 2px solid #4f46e5;
    outline-offset: 2px;
}

/* Smooth transitions for all interactive elements */
button, 
[role="button"],
a {
    transition: all 0.2s ease-in-out;
}

/* Prevent text selection on buttons */
button, 
[role="button"] {
    user-select: none;
    -webkit-user-select: none;
}

/* Better focus states for dark mode */
.dark input:focus,
.dark textarea:focus {
    background-color: rgba(255, 255, 255, 0.05);
}

/* Contact info cards hover effect */
.bg-white.dark\:bg-gray-800.rounded-xl.p-6.shadow-lg {
    transition: transform 0.2s ease;
}

.bg-white.dark\:bg-gray-800.rounded-xl.p-6.shadow-lg:hover {
    transform: translateY(-2px);
}

/* Email link styles in contact info */
a[href^="mailto:"] {
    text-decoration: none;
    transition: color 0.2s ease;
}

a[href^="mailto:"]:hover {
    text-decoration: underline;
}

/* Form error message styles */
.text-red-500.text-xs {
    animation: fadeIn 0.3s ease-out;
}

/* Resize prevention for textarea */
.resize-none {
    resize: none;
}

/* Contact form button icon */
button svg {
    transition: transform 0.2s ease;
}

button:hover svg {
    transform: translateX(2px);
}

/* Stats animation enhancement */
@keyframes statPulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.05); }
    100% { transform: scale(1); }
}

.relative.w-16.h-16.bg-blue-500.rounded-full {
    animation: statPulse 2s infinite;
}

/* Service section link accessibility */
.group\/link span {
    position: relative;
}

.group\/link span::after {
    content: '';
    position: absolute;
    width: 100%;
    height: 2px;
    bottom: -2px;
    left: 0;
    background-color: currentColor;
    transform: scaleX(0);
    transform-origin: bottom right;
    transition: transform 0.3s ease;
}

.group\/link:hover span::after {
    transform: scaleX(1);
    transform-origin: bottom left;
}

/* Toast notification styles */
.bg-green-500.text-white.rounded-lg.shadow-lg {
    box-shadow: 0 10px 25px rgba(34, 197, 94, 0.3);
}

/* Mobile optimizations */
@media (max-width: 768px) {
    .grid.grid-cols-1.lg\:grid-cols-3.gap-8 {
        gap: 1.5rem;
    }
    
    .p-8 {
        padding: 1.5rem;
    }
    
    .text-3xl {
        font-size: 1.75rem;
    }
}

/* Print styles for email content */
@media print {
    .fixed {
        display: none !important;
    }
    
    nav {
        display: none !important;
    }
}
</style>