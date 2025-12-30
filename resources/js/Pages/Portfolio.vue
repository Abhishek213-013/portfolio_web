<template>
    <div class="min-h-screen bg-white dark:bg-gray-900 transition-colors duration-300">
        <!-- Navigation -->
        <nav class="fixed w-full z-50 bg-white/90 dark:bg-gray-900/90 backdrop-blur-lg border-b border-gray-200 dark:border-gray-800">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <div class="flex items-center">
                        <a href="#" class="text-2xl font-bold text-gray-900 dark:text-white">
                            <span class="text-indigo-600 dark:text-indigo-400">{{ siteSettings.site_name || 'Portfolio' }}</span>
                        </a>
                    </div>
                    <div class="hidden md:block">
                        <div class="flex items-center space-x-8">
                            <a v-for="(nav, index) in navigation" 
                               :key="index"
                               :href="nav.href"
                               @click.prevent="scrollToSection(nav.id)"
                               class="text-gray-700 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors font-medium"
                               :class="{ 'text-indigo-600 dark:text-indigo-400': activeSection === nav.id }">
                                {{ nav.name }}
                            </a>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <button @click="toggleTheme" class="p-2 text-gray-600 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400">
                            <svg v-if="isDark" class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" clip-rule="evenodd" />
                            </svg>
                            <svg v-else class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z" />
                            </svg>
                        </button>
                        <a href="/admin/dashboard" class="px-4 py-2 bg-indigo-600 dark:bg-indigo-500 text-white rounded-lg hover:bg-indigo-700 dark:hover:bg-indigo-600 transition-colors text-sm font-medium">
                            Admin
                        </a>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <section id="home" class="pt-24 pb-16 md:pt-32 md:pb-24 relative overflow-hidden">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                    <!-- Left Content -->
                    <div class="text-center lg:text-left">
                        <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-gray-900 dark:text-white mb-6">
                            {{ heroData.name || 'Your Name' }}
                        </h1>
                        <div class="text-2xl md:text-3xl text-gray-600 dark:text-gray-300 mb-8">
                            I'm a <span class="text-indigo-600 dark:text-indigo-400 font-semibold">
                                {{ heroData.roles && heroData.roles.length > 0 ? heroData.roles[0] : 'Your Profession' }}
                            </span>
                        </div>
                        <div v-if="heroData.roles && heroData.roles.length > 1" class="flex flex-wrap gap-3 justify-center lg:justify-start mb-8">
                            <span v-for="(role, index) in heroData.roles.slice(1)" 
                                  :key="index"
                                  class="px-4 py-2 bg-indigo-100 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 rounded-full text-sm font-medium">
                                {{ role }}
                            </span>
                        </div>
                        <div class="flex justify-center lg:justify-start space-x-6">
                            <a v-for="social in heroData.social_links" 
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
                    
                    <!-- Right Image -->
                    <div class="relative">
                        <div class="relative w-64 h-64 md:w-80 md:h-80 mx-auto">
                            <div class="absolute inset-0 bg-gradient-to-r from-indigo-400 to-purple-400 dark:from-indigo-500 dark:to-purple-500 rounded-full transform rotate-6"></div>
                            <div v-if="heroData.background_image" 
                                 class="relative w-full h-full rounded-full overflow-hidden border-8 border-white dark:border-gray-800 shadow-2xl">
                                <img :src="heroData.background_image" 
                                     alt="Profile"
                                     class="w-full h-full object-cover">
                            </div>
                            <div v-else 
                                 class="relative w-full h-full rounded-full overflow-hidden border-8 border-white dark:border-gray-800 shadow-2xl bg-gradient-to-br from-gray-200 to-gray-300 dark:from-gray-700 dark:to-gray-800 flex items-center justify-center">
                                <span class="text-6xl font-bold text-gray-600 dark:text-gray-400">
                                    {{ heroData.name?.charAt(0) || 'Y' }}
                                </span>
                            </div>
                        </div>
                        
                        <!-- Floating Elements -->
                        <div class="absolute -top-4 -right-4 bg-yellow-100 dark:bg-yellow-900/30 rounded-lg p-4 shadow-xl">
                            <div class="text-2xl font-bold text-yellow-600 dark:text-yellow-400">5+</div>
                            <div class="text-sm text-yellow-800 dark:text-yellow-300">Years Exp.</div>
                        </div>
                        <div class="absolute -bottom-4 -left-4 bg-green-100 dark:bg-green-900/30 rounded-lg p-4 shadow-xl">
                            <div class="text-2xl font-bold text-green-600 dark:text-green-400">50+</div>
                            <div class="text-sm text-green-800 dark:text-green-300">Projects</div>
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
        <section id="about" class="py-16 md:py-24 bg-gray-50 dark:bg-gray-800/50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-4">About Me</h2>
                    <p class="text-lg text-gray-600 dark:text-gray-400 max-w-3xl mx-auto">
                        {{ aboutData.description || 'A brief introduction about yourself and your expertise' }}
                    </p>
                </div>
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                    <!-- Profile Image -->
                    <div class="relative">
                        <div class="relative w-64 h-64 md:w-96 md:h-96 mx-auto">
                            <div class="absolute inset-0 bg-gradient-to-r from-blue-400 to-cyan-400 dark:from-blue-500 dark:to-cyan-500 rounded-lg transform rotate-3"></div>
                            <div v-if="aboutData.profile_image" 
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
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">{{ aboutData.title || 'About Me' }}</h3>
                        <p class="text-lg text-gray-600 dark:text-gray-300 mb-6 italic">
                            "{{ aboutData.bio || 'Your professional bio or tagline' }}"
                        </p>
                        <p class="text-gray-700 dark:text-gray-400 mb-8 leading-relaxed">
                            {{ aboutData.extended_bio || 'Detailed description about your experience, skills, and achievements...' }}
                        </p>
                        
                        <div v-if="aboutData.personal_details" class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-8">
                            <div v-for="(side, sideName) in aboutData.personal_details" :key="sideName">
                                <div v-for="detail in side" :key="detail.label" class="mb-4">
                                    <div class="flex items-center">
                                        <span class="font-medium text-gray-900 dark:text-white min-w-28">{{ detail.label }}:</span>
                                        <span class="text-gray-600 dark:text-gray-400">{{ detail.value }}</span>
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

        <!-- Skills Section -->
        <section id="skills" class="py-16 md:py-24">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-4">My Skills</h2>
                    <p class="text-lg text-gray-600 dark:text-gray-400 max-w-3xl mx-auto">
                        Technologies and tools I'm proficient with
                    </p>
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

        <!-- Statistics Section -->
        <section class="py-16 md:py-24 bg-gradient-to-r from-indigo-500 to-purple-500 dark:from-indigo-600 dark:to-purple-600">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                    <div v-for="stat in statistics" :key="stat.id" class="text-center">
                        <div class="mb-4">
                            <i :class="stat.icon" class="text-4xl text-white"></i>
                        </div>
                        <div class="text-4xl md:text-5xl font-bold text-white mb-2">{{ formatNumber(stat.value) }}</div>
                        <div class="text-lg text-white/90">{{ stat.label }}</div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Resume Section -->
        <section id="resume" class="py-16 md:py-24 bg-gray-50 dark:bg-gray-800/50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-4">My Resume</h2>
                    <p class="text-lg text-gray-600 dark:text-gray-400 max-w-3xl mx-auto">
                        My professional journey and qualifications
                    </p>
                </div>
                
                <!-- Summary -->
                <div v-if="summarySections.length > 0" class="mb-12">
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-8 text-center">Professional Summary</h3>
                    <div class="max-w-4xl mx-auto">
                        <div v-for="section in summarySections" :key="section.id" 
                             class="bg-white dark:bg-gray-800 rounded-xl p-8 shadow-lg">
                            <h4 class="text-xl font-bold text-gray-900 dark:text-white mb-4">{{ section.title }}</h4>
                            <p class="text-gray-700 dark:text-gray-300 mb-6">{{ section.description }}</p>
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
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-4">My Portfolio</h2>
                    <p class="text-lg text-gray-600 dark:text-gray-400 max-w-3xl mx-auto">
                        A showcase of my recent projects and work
                    </p>
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
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-4">My Services</h2>
                    <p class="text-lg text-gray-600 dark:text-gray-400 max-w-3xl mx-auto">
                        Professional services I offer to clients
                    </p>
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
                        <div class="flex items-center text-indigo-600 dark:text-indigo-400 font-medium">
                            <span>Learn more</span>
                            <svg class="h-5 w-5 ml-2 transform group-hover:translate-x-2 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Testimonials Section -->
        <section id="testimonials" class="py-16 md:py-24">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-4">Testimonials</h2>
                    <p class="text-lg text-gray-600 dark:text-gray-400 max-w-3xl mx-auto">
                        What clients say about my work
                    </p>
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
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-4">Get In Touch</h2>
                    <p class="text-lg text-gray-600 dark:text-gray-400 max-w-3xl mx-auto">
                        Feel free to contact me for any questions or opportunities
                    </p>
                </div>
                
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Contact Information -->
                    <div class="lg:col-span-1">
                        <div class="grid grid-cols-1 gap-6">
                            <div v-for="contact in contactInfo" :key="contact.id"
                                 class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-lg">
                                <div class="flex items-start">
                                    <div :class="[
                                        'h-12 w-12 rounded-lg flex items-center justify-center mr-4',
                                        getContactColor(contact.type)
                                    ]">
                                        <i :class="[contact.icon, 'text-white text-lg']"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-gray-900 dark:text-white mb-2">{{ contact.title }}</h4>
                                        <p class="text-gray-600 dark:text-gray-400">{{ contact.value }}</p>
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
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Your Name</label>
                                        <input v-model="contactForm.name"
                                               type="text"
                                               required
                                               class="w-full px-4 py-3 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-900 dark:text-white"
                                               placeholder="John Doe">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Your Email</label>
                                        <input v-model="contactForm.email"
                                               type="email"
                                               required
                                               class="w-full px-4 py-3 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-900 dark:text-white"
                                               placeholder="john@example.com">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Subject</label>
                                    <input v-model="contactForm.subject"
                                           type="text"
                                           required
                                           class="w-full px-4 py-3 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-900 dark:text-white"
                                           placeholder="Project Inquiry">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-gray-300 mb-2">Message</label>
                                    <textarea v-model="contactForm.message"
                                              rows="5"
                                              required
                                              class="w-full px-4 py-3 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-900 dark:text-white"
                                              placeholder="Tell me about your project..."></textarea>
                                </div>
                                <button type="submit"
                                        :disabled="contactForm.loading"
                                        :class="[
                                            'w-full px-6 py-3 rounded-lg font-medium transition-colors',
                                            contactForm.loading
                                                ? 'bg-indigo-400 dark:bg-indigo-500 cursor-not-allowed'
                                                : 'bg-indigo-600 dark:bg-indigo-500 hover:bg-indigo-700 dark:hover:bg-indigo-600'
                                        ]">
                                    <span v-if="contactForm.loading" class="flex items-center justify-center">
                                        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        Sending...
                                    </span>
                                    <span v-else>Send Message</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="bg-gray-900 dark:bg-black pt-12 pb-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-8">
                    <a href="#" class="text-2xl font-bold text-white mb-4 inline-block">
                        <span class="text-indigo-400">{{ siteSettings.site_name || 'Portfolio' }}</span>
                    </a>
                    <p class="text-gray-400 max-w-2xl mx-auto mb-8">
                        {{ siteSettings.site_description || 'A professional portfolio showcasing my work and skills' }}
                    </p>
                    
                    <!-- Social Links -->
                    <div v-if="siteSettings.footer_social_links && siteSettings.footer_social_links.length > 0" class="flex justify-center space-x-6 mb-8">
                        <a v-for="social in siteSettings.footer_social_links" 
                           :key="social.platform"
                           :href="social.url"
                           target="_blank"
                           class="h-10 w-10 bg-gray-800 rounded-full flex items-center justify-center hover:bg-indigo-600 transition-colors"
                           :title="social.platform">
                            <i v-if="social.icon" :class="social.icon" class="text-lg text-gray-300"></i>
                            <span v-else class="text-sm font-medium text-gray-300">{{ social.platform?.charAt(0) }}</span>
                        </a>
                    </div>
                    
                    <!-- Navigation -->
                    <div class="flex flex-wrap justify-center gap-6 mb-8">
                        <a v-for="(nav, index) in navigation" 
                           :key="index"
                           :href="nav.href"
                           @click.prevent="scrollToSection(nav.id)"
                           class="text-gray-400 hover:text-white transition-colors">
                            {{ nav.name }}
                        </a>
                    </div>
                </div>
                
                <!-- Copyright -->
                <div class="border-t border-gray-800 pt-8">
                    <div class="flex flex-col md:flex-row justify-between items-center">
                        <p class="text-gray-400 text-sm mb-4 md:mb-0">
                            © {{ new Date().getFullYear() }} {{ siteSettings.copyright_name || 'Your Name' }}. {{ siteSettings.copyright_text || 'All rights reserved' }}
                        </p>
                        <div class="text-gray-400 text-sm">
                            <span>{{ siteSettings.distributed_by_text || 'Distributed by' }} </span>
                            <a :href="siteSettings.designer_url || '#'" 
                               class="text-indigo-400 hover:text-indigo-300 transition-colors">
                                {{ siteSettings.designer_name || 'Designer' }}
                            </a>
                            <span class="mx-2">•</span>
                            <a :href="siteSettings.distributor_url || '#'" 
                               class="text-indigo-400 hover:text-indigo-300 transition-colors">
                                {{ siteSettings.distributor_name || 'Distributor' }}
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
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
            </svg>
        </button>

        <!-- Success Toast -->
        <div v-if="contactForm.success" 
             class="fixed top-4 right-4 z-50 px-6 py-4 bg-green-500 text-white rounded-lg shadow-lg transform transition-all duration-300 translate-x-0">
            <div class="flex items-center">
                <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                <span class="font-medium">Message sent successfully!</span>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, reactive, computed, onMounted, onUnmounted } from 'vue';
import axios from 'axios';

// Theme Management
const isDark = ref(false);
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

// Reactive Data
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

// Contact Form
const contactForm = reactive({
    name: '',
    email: '',
    subject: '',
    message: '',
    loading: false,
    success: false
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
};

// Fetch Data Functions
const fetchData = async () => {
    try {
        loading.value = true;
        
        // Fetch all data in parallel
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
            axios.get('/api/hero'),
            axios.get('/api/about'),
            axios.get('/api/skills'),
            axios.get('/api/statistics'),
            axios.get('/api/resume-sections'),
            axios.get('/api/portfolio-items'),
            axios.get('/api/services'),
            axios.get('/api/testimonials'),
            axios.get('/api/contact-info'),
            axios.get('/api/site-settings')
        ]);

        // Process responses
        heroData.value = heroResponse.data?.data || {};
        aboutData.value = aboutResponse.data?.data || {};
        skills.value = skillsResponse.data?.data || skillsResponse.data || [];
        statistics.value = statsResponse.data?.data || statsResponse.data || [];
        resumeSections.value = resumeResponse.data?.data || resumeResponse.data || [];
        portfolioItems.value = portfolioResponse.data?.data || portfolioResponse.data || [];
        services.value = servicesResponse.data?.data || servicesResponse.data || [];
        testimonials.value = testimonialsResponse.data?.data || testimonialsResponse.data || [];
        contactInfo.value = contactResponse.data?.data || contactResponse.data || [];
        siteSettings.value = settingsResponse.data?.data || settingsResponse.data || {};

    } catch (error) {
        console.error('Error fetching data:', error);
        // Set default data for demonstration
        setDemoData();
    } finally {
        loading.value = false;
    }
};

// Demo data for fallback
const setDemoData = () => {
    heroData.value = {
        name: 'John Doe',
        roles: ['Full Stack Developer', 'UI/UX Designer'],
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
            image: 'https://images.unsplash.com/photo-1563013544-824ae1b704d3?w-800&auto=format&fit=crop',
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
        }
    ];

    testimonials.value = [
        {
            id: 1,
            name: 'Sarah Johnson',
            position: 'CEO at TechCorp',
            rating: 5,
            content: 'Excellent work! The project was delivered on time and exceeded expectations.'
        }
    ];

    contactInfo.value = [
        {
            id: 1,
            type: 'email',
            icon: 'fas fa-envelope',
            title: 'Email',
            value: 'hello@example.com'
        }
    ];

    siteSettings.value = {
        site_name: 'My Portfolio',
        site_description: 'A professional portfolio showcasing my work',
        copyright_name: 'John Doe',
        copyright_text: 'All rights reserved'
    };
};

// Contact Form Submission
const submitContactForm = async () => {
    contactForm.loading = true;
    contactForm.success = false;

    try {
        // In a real application, you would send this to your backend
        // For now, we'll simulate a successful submission
        await new Promise(resolve => setTimeout(resolve, 1500));
        
        contactForm.success = true;
        contactForm.name = '';
        contactForm.email = '';
        contactForm.subject = '';
        contactForm.message = '';

        // Hide success message after 5 seconds
        setTimeout(() => {
            contactForm.success = false;
        }, 5000);

    } catch (error) {
        console.error('Error submitting form:', error);
        alert('Failed to send message. Please try again.');
    } finally {
        contactForm.loading = false;
    }
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

    // Fetch data
    fetchData();

    // Add scroll listener
    window.addEventListener('scroll', handleScroll);

    // Initial scroll position check
    handleScroll();
});

onUnmounted(() => {
    window.removeEventListener('scroll', handleScroll);
});
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

/* Gradient text */
.gradient-text {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

/* Glass morphism */
.glass {
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.dark .glass {
    background: rgba(0, 0, 0, 0.2);
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .grid-cols-3 {
        grid-template-columns: repeat(1, minmax(0, 1fr));
    }
    
    .grid-cols-4 {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
}

@media (max-width: 640px) {
    .grid-cols-4 {
        grid-template-columns: repeat(1, minmax(0, 1fr));
    }
}

/* Ensure images are responsive */
img {
    max-width: 100%;
    height: auto;
}

/* Smooth transitions for dark mode */
.transition-colors {
    transition: background-color 0.3s ease, border-color 0.3s ease, color 0.3s ease;
}

/* Custom focus styles */
:focus {
    outline: 2px solid #4f46e5;
    outline-offset: 2px;
}

.dark :focus {
    outline-color: #6366f1;
}
</style>