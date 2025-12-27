<template>
  <div class="index-page">
    <!-- Loading Overlay -->
    <div v-if="loading" class="loading-overlay">
      <div class="spinner-border text-primary" role="status">
        <span class="visually-hidden">Loading Portfolio...</span>
      </div>
    </div>

    <!-- Error Message -->
    <div v-if="error" class="error-alert">
      <div class="alert alert-danger m-3" role="alert">
        {{ error }}
        <button @click="fetchAllData" class="btn btn-sm btn-light ms-2">Retry</button>
      </div>
    </div>

    <!-- Header -->
    <header id="header" class="header d-flex flex-column justify-content-center">
      <i class="header-toggle d-xl-none bi bi-list" @click="toggleNav"></i>
      <nav id="navmenu" :class="['navmenu', { active: navActive }]">
        <ul>
          <li v-for="navItem in navigation" :key="navItem.id">
            <a 
              :href="navItem.href" 
              :class="{ active: activeSection === navItem.id }"
              @click.prevent="scrollToSection(navItem.id)"
            >
              <i :class="navItem.icon"></i>
              <span>{{ navItem.text }}</span>
            </a>
          </li>
        </ul>
      </nav>
    </header>

    <!-- Main Content -->
    <main class="main" v-if="!loading">
      <!-- Hero Section -->
      <section id="hero" ref="hero" class="hero section light-background">
        <img v-if="heroData.background_image" :src="heroData.background_image" :alt="heroData.name">
        <div class="container" data-aos="zoom-out">
          <div class="row justify-content-center">
            <div class="col-lg-9">
              <h2>{{ heroData.name || 'Your Name' }}</h2>
              <p>
                I'm <span class="typed">
                  {{ heroData.roles && heroData.roles[currentRole] ? heroData.roles[currentRole] : 'Developer' }}
                </span>
                <span class="typed-cursor typed-cursor--blink" aria-hidden="true"></span>
              </p>
              <div class="social-links" v-if="heroData.social_links && heroData.social_links.length > 0">
                <a v-for="social in heroData.social_links" :key="social.platform" :href="social.url">
                  <i :class="social.icon"></i>
                </a>
              </div>
              <div class="social-links" v-else>
                <a href="#"><i class="bi bi-twitter-x"></i></a>
                <a href="#"><i class="bi bi-facebook"></i></a>
                <a href="#"><i class="bi bi-instagram"></i></a>
                <a href="#"><i class="bi bi-linkedin"></i></a>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- About Section -->
      <section id="about" ref="about" class="about section">
        <div class="container section-title" data-aos="fade-up">
          <h2>About</h2>
          <p>{{ aboutData.description || 'Learn more about me' }}</p>
        </div>

        <div class="container" data-aos="fade-up" data-aos-delay="100">
          <div class="row gy-4 justify-content-center">
            <div class="col-lg-4" v-if="aboutData.profile_image">
              <img :src="aboutData.profile_image" class="img-fluid" :alt="aboutData.title">
            </div>
            <div class="col-lg-4" v-else>
              <div class="placeholder-img">
                <i class="bi bi-person-circle"></i>
              </div>
            </div>
            <div class="col-lg-8 content">
              <h2>{{ aboutData.title || 'About Me' }}</h2>
              <p class="fst-italic py-3">
                {{ aboutData.bio || 'Your professional bio goes here' }}
              </p>
              <div class="row">
                <div class="col-lg-6">
                  <ul>
                    <li v-for="(detail, index) in aboutDetails.left" :key="index">
                      <i class="bi bi-chevron-right"></i> 
                      <strong>{{ detail.label }}:</strong> 
                      <span>{{ detail.value }}</span>
                    </li>
                    <li v-if="aboutDetails.left.length === 0">
                      <i class="bi bi-chevron-right"></i> 
                      <strong>Birthday:</strong> 
                      <span>1 May 1995</span>
                    </li>
                  </ul>
                </div>
                <div class="col-lg-6">
                  <ul>
                    <li v-for="(detail, index) in aboutDetails.right" :key="index">
                      <i class="bi bi-chevron-right"></i> 
                      <strong>{{ detail.label }}:</strong> 
                      <span>{{ detail.value }}</span>
                    </li>
                    <li v-if="aboutDetails.right.length === 0">
                      <i class="bi bi-chevron-right"></i> 
                      <strong>Email:</strong> 
                      <span>email@example.com</span>
                    </li>
                  </ul>
                </div>
              </div>
              <p class="py-3">
                {{ aboutData.extended_bio || 'More detailed information about yourself and your career journey.' }}
              </p>
            </div>
          </div>
        </div>
      </section>

      <!-- Stats Section -->
      <section id="stats" class="stats section">
        <div class="container" data-aos="fade-up" data-aos-delay="100">
          <div class="row gy-4">
            <div v-for="stat in statsData" :key="stat.id" class="col-lg-3 col-md-6 d-flex flex-column align-items-center">
              <i :class="stat.icon"></i>
              <div class="stats-item">
                <span 
                  :data-purecounter-start="0" 
                  :data-purecounter-end="stat.value" 
                  data-purecounter-duration="2"
                  class="purecounter"
                >
                  {{ stat.value }}
                </span>
                <p>{{ stat.label }}</p>
              </div>
            </div>
            <div v-if="statsData.length === 0" class="col-lg-3 col-md-6 d-flex flex-column align-items-center">
              <i class="bi bi-emoji-smile"></i>
              <div class="stats-item">
                <span class="purecounter">232</span>
                <p>Happy Clients</p>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- Skills Section -->
      <section id="skills" class="skills section">
        <div class="container section-title" data-aos="fade-up">
          <h2>Skills</h2>
          <p>{{ skillsData.description || 'My technical skills and expertise' }}</p>
        </div>

        <div class="container" data-aos="fade-up" data-aos-delay="100">
          <div class="row skills-content skills-animation">
            <div class="col-lg-6">
              <div v-for="skill in leftSkills" :key="skill.id" class="progress">
                <span class="skill">
                  <span>{{ skill.name }}</span> 
                  <i class="val">{{ skill.percentage }}%</i>
                </span>
                <div class="progress-bar-wrap">
                  <div 
                    class="progress-bar" 
                    :style="{ width: skill.percentage + '%' }"
                    role="progressbar" 
                    :aria-valuenow="skill.percentage" 
                    aria-valuemin="0" 
                    aria-valuemax="100"
                  ></div>
                </div>
              </div>
              <div v-if="leftSkills.length === 0" class="progress">
                <span class="skill">
                  <span>HTML</span> 
                  <i class="val">100%</i>
                </span>
                <div class="progress-bar-wrap">
                  <div class="progress-bar" style="width: 100%"></div>
                </div>
              </div>
            </div>
            <div class="col-lg-6">
              <div v-for="skill in rightSkills" :key="skill.id" class="progress">
                <span class="skill">
                  <span>{{ skill.name }}</span> 
                  <i class="val">{{ skill.percentage }}%</i>
                </span>
                <div class="progress-bar-wrap">
                  <div 
                    class="progress-bar" 
                    :style="{ width: skill.percentage + '%' }"
                    role="progressbar" 
                    :aria-valuenow="skill.percentage" 
                    aria-valuemin="0" 
                    aria-valuemax="100"
                  ></div>
                </div>
              </div>
              <div v-if="rightSkills.length === 0" class="progress">
                <span class="skill">
                  <span>CSS</span> 
                  <i class="val">90%</i>
                </span>
                <div class="progress-bar-wrap">
                  <div class="progress-bar" style="width: 90%"></div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- Resume Section -->
      <section id="resume" class="resume section">
        <div class="container section-title" data-aos="fade-up">
          <h2>Resume</h2>
          <p>{{ resumeData.description || 'My professional experience and education' }}</p>
        </div>

        <div class="container">
          <div class="row">
            <div class="col-lg-6" data-aos="fade-up" data-aos-delay="100">
              <h3 class="resume-title">Summary</h3>
              <div v-for="summary in resumeSummary" :key="summary.id" class="resume-item pb-0">
                <h4>{{ summary.title }}</h4>
                <p><em>{{ summary.subtitle }}</em></p>
                <p>{{ summary.description }}</p>
                <ul v-if="summary.details && summary.details.length">
                  <li v-for="(detail, index) in summary.details" :key="index">
                    {{ detail }}
                  </li>
                </ul>
              </div>
              <div v-if="resumeSummary.length === 0" class="resume-item pb-0">
                <h4>Your Name</h4>
                <p><em>Innovative and deadline-driven Graphic Designer</em></p>
                <p>Your professional summary goes here</p>
              </div>

              <h3 class="resume-title">Education</h3>
              <div v-for="education in resumeEducation" :key="education.id" class="resume-item">
                <h4>{{ education.title }}</h4>
                <h5>{{ education.period }}</h5>
                <p><em>{{ education.subtitle }}</em></p>
                <p>{{ education.description }}</p>
              </div>
              <div v-if="resumeEducation.length === 0" class="resume-item">
                <h4>Master of Fine Arts & Graphic Design</h4>
                <h5>2015 - 2016</h5>
                <p><em>University Name</em></p>
                <p>Your education details</p>
              </div>
            </div>

            <div class="col-lg-6" data-aos="fade-up" data-aos-delay="200">
              <h3 class="resume-title">Professional Experience</h3>
              <div v-for="experience in resumeExperience" :key="experience.id" class="resume-item">
                <h4>{{ experience.title }}</h4>
                <h5>{{ experience.period }}</h5>
                <p><em>{{ experience.subtitle }}</em></p>
                <ul v-if="experience.details && experience.details.length">
                  <li v-for="(responsibility, index) in experience.details" :key="index">
                    {{ responsibility }}
                  </li>
                </ul>
                <p v-else>{{ experience.description }}</p>
              </div>
              <div v-if="resumeExperience.length === 0" class="resume-item">
                <h4>Senior Graphic Design Specialist</h4>
                <h5>2019 - Present</h5>
                <p><em>Company Name</em></p>
                <ul>
                  <li>Lead in the design, development, and implementation</li>
                  <li>Delegate tasks to team members</li>
                  <li>Supervise the assessment of all graphic materials</li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- Portfolio Section -->
      <section id="portfolio" class="portfolio section">
        <div class="container section-title" data-aos="fade-up">
          <h2>Portfolio</h2>
          <p>{{ portfolioDescription || 'My recent work and projects' }}</p>
        </div>

        <div class="container">
          <div class="isotope-layout" data-default-filter="*" data-layout="masonry" data-sort="original-order">
            <ul class="portfolio-filters isotope-filters" data-aos="fade-up" data-aos-delay="100">
              <li 
                v-for="filter in portfolioFilters" 
                :key="filter.value"
                :data-filter="filter.value"
                :class="{ 'filter-active': activeFilter === filter.value }"
                @click="setActiveFilter(filter.value)"
              >
                {{ filter.label }}
              </li>
            </ul>

            <div class="row gy-4 isotope-container" data-aos="fade-up" data-aos-delay="200">
              <div 
                v-for="item in filteredPortfolioItems" 
                :key="item.id"
                class="col-lg-4 col-md-6 portfolio-item isotope-item"
                :class="'filter-' + item.category"
              >
                <img :src="item.image" class="img-fluid" :alt="item.title">
                <div class="portfolio-info">
                  <h4>{{ item.title }}</h4>
                  <p>{{ item.description }}</p>
                  <a 
                    :href="item.image" 
                    :title="item.title" 
                    :data-gallery="'portfolio-gallery-' + item.category"
                    class="glightbox preview-link"
                  >
                    <i class="bi bi-zoom-in"></i>
                  </a>
                  <a :href="item.details_url" title="More Details" class="details-link">
                    <i class="bi bi-link-45deg"></i>
                  </a>
                </div>
              </div>
              <div v-if="portfolioItems.length === 0" class="col-lg-4 col-md-6 portfolio-item isotope-item filter-app">
                <div class="placeholder-portfolio">
                  <i class="bi bi-images"></i>
                  <h4>Portfolio Item</h4>
                  <p>Add your portfolio items</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- Services Section -->
      <section id="services" class="services section">
        <div class="container section-title" data-aos="fade-up">
          <h2>Services</h2>
          <p>{{ servicesDescription || 'Services I offer' }}</p>
        </div>

        <div class="container">
          <div class="row gy-4">
            <div 
              v-for="service in services" 
              :key="service.id"
              class="col-lg-4 col-md-6"
              data-aos="fade-up"
              :data-aos-delay="100 + (index * 100)"
            >
              <div :class="['service-item', 'item-' + service.color, 'position-relative']">
                <div class="icon">
                  <svg width="100" height="100" viewBox="0 0 600 600" xmlns="http://www.w3.org/2000/svg">
                    <path stroke="none" stroke-width="0" fill="#f5f5f5" :d="service.svg_path"></path>
                  </svg>
                  <i :class="service.icon"></i>
                </div>
                <a href="#" class="stretched-link">
                  <h3>{{ service.title }}</h3>
                </a>
                <p>{{ service.description }}</p>
              </div>
            </div>
            <div v-if="services.length === 0" class="col-lg-4 col-md-6" data-aos="fade-up">
              <div class="service-item item-cyan position-relative">
                <div class="icon">
                  <svg width="100" height="100" viewBox="0 0 600 600" xmlns="http://www.w3.org/2000/svg">
                    <path stroke="none" stroke-width="0" fill="#f5f5f5"></path>
                  </svg>
                  <i class="bi bi-activity"></i>
                </div>
                <a href="#" class="stretched-link">
                  <h3>Web Design</h3>
                </a>
                <p>Professional web design services</p>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- Testimonials Section -->
      <section id="testimonials" class="testimonials section">
        <div class="container section-title" data-aos="fade-up">
          <h2>Testimonials</h2>
          <p>{{ testimonialsDescription || 'What clients say about my work' }}</p>
        </div>

        <div class="container" data-aos="fade-up" data-aos-delay="100">
          <div class="swiper init-swiper">
            <div class="swiper-wrapper">
              <div v-for="testimonial in testimonials" :key="testimonial.id" class="swiper-slide">
                <div class="testimonial-item">
                  <div class="row gy-4 justify-content-center">
                    <div class="col-lg-6">
                      <div class="testimonial-content">
                        <p>
                          <i class="bi bi-quote quote-icon-left"></i>
                          <span>{{ testimonial.content }}</span>
                          <i class="bi bi-quote quote-icon-right"></i>
                        </p>
                        <h3>{{ testimonial.name }}</h3>
                        <h4>{{ testimonial.position }}</h4>
                        <div class="stars">
                          <i v-for="n in testimonial.rating" :key="n" class="bi bi-star-fill"></i>
                        </div>
                      </div>
                    </div>
                    <div class="col-lg-2 text-center" v-if="testimonial.image">
                      <img :src="testimonial.image" class="img-fluid testimonial-img" :alt="testimonial.name">
                    </div>
                  </div>
                </div>
              </div>
              <div v-if="testimonials.length === 0" class="swiper-slide">
                <div class="testimonial-item">
                  <div class="row gy-4 justify-content-center">
                    <div class="col-lg-6">
                      <div class="testimonial-content">
                        <p>
                          <i class="bi bi-quote quote-icon-left"></i>
                          <span>Excellent work and professional service!</span>
                          <i class="bi bi-quote quote-icon-right"></i>
                        </p>
                        <h3>Client Name</h3>
                        <h4>CEO, Company</h4>
                        <div class="stars">
                          <i class="bi bi-star-fill"></i>
                          <i class="bi bi-star-fill"></i>
                          <i class="bi bi-star-fill"></i>
                          <i class="bi bi-star-fill"></i>
                          <i class="bi bi-star-fill"></i>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="swiper-pagination"></div>
          </div>
        </div>
      </section>

      <!-- Contact Section -->
      <section id="contact" class="contact section">
        <div class="container section-title" data-aos="fade-up">
          <h2>Contact</h2>
          <p>{{ contactDescription || 'Get in touch with me' }}</p>
        </div>

        <div class="container" data-aos="fade" data-aos-delay="100">
          <div class="row gy-4">
            <div class="col-lg-4">
              <div 
                v-for="(info, index) in contactInfo" 
                :key="info.id"
                class="info-item d-flex"
                data-aos="fade-up"
                :data-aos-delay="200 + (index * 100)"
              >
                <i :class="info.icon + ' flex-shrink-0'"></i>
                <div>
                  <h3>{{ info.title }}</h3>
                  <p>{{ info.value }}</p>
                </div>
              </div>
              <div v-if="contactInfo.length === 0" class="info-item d-flex" data-aos="fade-up">
                <i class="bi bi-geo-alt flex-shrink-0"></i>
                <div>
                  <h3>Address</h3>
                  <p>Your Address Here</p>
                </div>
              </div>
            </div>

            <div class="col-lg-8">
              <form @submit.prevent="submitContactForm" class="php-email-form" data-aos="fade-up" data-aos-delay="200">
                <div class="row gy-4">
                  <div class="col-md-6">
                    <input 
                      type="text" 
                      v-model="contactForm.name"
                      class="form-control" 
                      placeholder="Your Name" 
                      required
                    >
                  </div>

                  <div class="col-md-6">
                    <input 
                      type="email" 
                      v-model="contactForm.email"
                      class="form-control" 
                      placeholder="Your Email" 
                      required
                    >
                  </div>

                  <div class="col-md-12">
                    <input 
                      type="text" 
                      v-model="contactForm.subject"
                      class="form-control" 
                      placeholder="Subject" 
                      required
                    >
                  </div>

                  <div class="col-md-12">
                    <textarea 
                      v-model="contactForm.message"
                      class="form-control" 
                      rows="6" 
                      placeholder="Message" 
                      required
                    ></textarea>
                  </div>

                  <div class="col-md-12 text-center">
                    <div v-if="contactForm.loading" class="loading">Sending...</div>
                    <div v-if="contactForm.error" class="error-message">{{ contactForm.error }}</div>
                    <div v-if="contactForm.success" class="sent-message">
                      Your message has been sent. Thank you!
                    </div>

                    <button type="submit" :disabled="contactForm.loading" class="btn btn-primary">
                      <span v-if="contactForm.loading">
                        <span class="spinner-border spinner-border-sm me-2"></span>
                        Sending...
                      </span>
                      <span v-else>Send Message</span>
                    </button>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </section>
    </main>

    <!-- Footer -->
    <footer id="footer" class="footer position-relative light-background">
      <div class="container">
        <h3 class="sitename">{{ siteSettings.site_name || 'Portfolio' }}</h3>
        <p>{{ siteSettings.site_description || 'Professional portfolio website' }}</p>
        <div class="social-links d-flex justify-content-center">
          <template v-if="siteSettings.footer_social_links && siteSettings.footer_social_links.length > 0">
            <a v-for="social in siteSettings.footer_social_links" :key="social.platform" :href="social.url">
              <i :class="social.icon"></i>
            </a>
          </template>
          <template v-else>
            <a href="#"><i class="bi bi-twitter-x"></i></a>
            <a href="#"><i class="bi bi-facebook"></i></a>
            <a href="#"><i class="bi bi-instagram"></i></a>
            <a href="#"><i class="bi bi-linkedin"></i></a>
          </template>
        </div>
        <div class="container mt-4">
          <div class="copyright">
            <span>Copyright</span> 
            <strong class="px-1 sitename">{{ siteSettings.copyright_name || 'Your Name' }}</strong> 
            <span>{{ siteSettings.copyright_text || 'All Rights Reserved' }}</span>
          </div>
          <div class="credits mt-2">
            <span>{{ siteSettings.distributed_by_text || 'Designed by' }}</span> 
            <a :href="siteSettings.designer_url || '#'">{{ siteSettings.designer_name || 'Designer' }}</a>
            <span> {{ siteSettings.distributor_name || 'Distributed by' }}</span>
            <a :href="siteSettings.distributor_url || '#'">{{ siteSettings.distributor_name || 'Distributor' }}</a>
          </div>
        </div>
      </div>
    </footer>

    <!-- Scroll Top -->
    <a 
      href="#" 
      id="scroll-top" 
      class="scroll-top d-flex align-items-center justify-content-center"
      @click.prevent="scrollToTop"
      v-show="!loading"
    >
      <i class="bi bi-arrow-up-short"></i>
    </a>
  </div>
</template>

<script>
import { ref, reactive, computed, onMounted, onUnmounted } from 'vue';
import axios from 'axios';
import PureCounter from '@srexi/purecounterjs';

export default {
  name: 'Portfolio',
  setup() {
    // Reactive state
    const navActive = ref(false);
    const activeSection = ref('hero');
    const currentRole = ref(0);
    const activeFilter = ref('*');
    const loading = ref(true);
    const error = ref(null);
    
    // Animation and external library references
    let aosInstance = null;
    let swiperInstance = null;
    let glightboxInstance = null;
    let isotopeInstance = null;
    let roleInterval = null;
    let scrollObserver = null;
    
    // Contact form
    const contactForm = reactive({
      name: '',
      email: '',
      subject: '',
      message: '',
      loading: false,
      error: '',
      success: false
    });

    // Navigation data
    const navigation = ref([
      { id: 'hero', href: '#hero', icon: 'bi bi-house navicon', text: 'Home' },
      { id: 'about', href: '#about', icon: 'bi bi-person navicon', text: 'About' },
      { id: 'resume', href: '#resume', icon: 'bi bi-file-earmark-text navicon', text: 'Resume' },
      { id: 'portfolio', href: '#portfolio', icon: 'bi bi-images navicon', text: 'Portfolio' },
      { id: 'services', href: '#services', icon: 'bi bi-hdd-stack navicon', text: 'Services' },
      { id: 'testimonials', href: '#testimonials', icon: 'bi bi-chat-left-quote navicon', text: 'Testimonials' },
      { id: 'contact', href: '#contact', icon: 'bi bi-envelope navicon', text: 'Contact' }
    ]);

    // Portfolio data with default values
    const heroData = ref({
      name: '',
      roles: [],
      background_image: '',
      social_links: []
    });
    
    const aboutData = ref({
      profile_image: '',
      title: '',
      description: '',
      bio: '',
      personal_details: { left: [], right: [] },
      extended_bio: ''
    });
    
    const statsData = ref([]);
    const skills = ref([]);
    const resumeSections = ref([]);
    const portfolioItems = ref([]);
    const services = ref([]);
    const testimonials = ref([]);
    const contactInfo = ref([]);
    const siteSettings = ref({
      site_name: 'Portfolio',
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

    // Portfolio filters
    const portfolioFilters = ref([
      { value: '*', label: 'All' },
      { value: 'app', label: 'App' },
      { value: 'product', label: 'Product' },
      { value: 'branding', label: 'Branding' },
    ]);

    // Section descriptions
    const portfolioDescription = ref('My portfolio showcases my best work');
    const servicesDescription = ref('Services I offer to clients');
    const testimonialsDescription = ref('What clients say about my work');
    const contactDescription = ref('Get in touch with me');
    const skillsData = reactive({ description: 'My technical skills and expertise' });
    const resumeData = reactive({ description: 'My professional experience and education' });

    // ========== COMPUTED PROPERTIES ==========
    
    const leftSkills = computed(() => {
      if (!Array.isArray(skills.value)) return [];
      return skills.value
        .filter(skill => skill.column === 'left' && (skill.is_active === undefined || skill.is_active === true))
        .sort((a, b) => (a.order || 0) - (b.order || 0));
    });

    const rightSkills = computed(() => {
      if (!Array.isArray(skills.value)) return [];
      return skills.value
        .filter(skill => skill.column === 'right' && (skill.is_active === undefined || skill.is_active === true))
        .sort((a, b) => (a.order || 0) - (b.order || 0));
    });

    const resumeSummary = computed(() => {
      if (!Array.isArray(resumeSections.value)) return [];
      return resumeSections.value
        .filter(section => section.type === 'summary' && (section.is_active === undefined || section.is_active === true))
        .sort((a, b) => (a.order || 0) - (b.order || 0));
    });

    const resumeEducation = computed(() => {
      if (!Array.isArray(resumeSections.value)) return [];
      return resumeSections.value
        .filter(section => section.type === 'education' && (section.is_active === undefined || section.is_active === true))
        .sort((a, b) => (a.order || 0) - (b.order || 0));
    });

    const resumeExperience = computed(() => {
      if (!Array.isArray(resumeSections.value)) return [];
      return resumeSections.value
        .filter(section => section.type === 'experience' && (section.is_active === undefined || section.is_active === true))
        .sort((a, b) => (a.order || 0) - (b.order || 0));
    });

    const filteredPortfolioItems = computed(() => {
      if (activeFilter.value === '*') {
        return portfolioItems.value.filter(item => item.is_active === undefined || item.is_active === true);
      }
      return portfolioItems.value.filter(item => 
        item.category === activeFilter.value.replace('filter-', '') && 
        (item.is_active === undefined || item.is_active === true)
      );
    });

    const aboutDetails = computed(() => {
      if (!aboutData.value.personal_details) return { left: [], right: [] };
      
      const details = aboutData.value.personal_details;
      if (typeof details === 'string') {
        try {
          return JSON.parse(details);
        } catch (e) {
          return { left: [], right: [] };
        }
      }
      return details;
    });

    const showLoading = computed(() => loading.value && Object.keys(heroData.value).length === 0);

    // ========== METHODS ==========
    
    const toggleNav = () => {
      navActive.value = !navActive.value;
    };

    const scrollToSection = (sectionId) => {
      const element = document.getElementById(sectionId);
      if (element) {
        element.scrollIntoView({ behavior: 'smooth' });
        activeSection.value = sectionId;
        navActive.value = false;
      }
    };

    const scrollToTop = () => {
      window.scrollTo({ top: 0, behavior: 'smooth' });
    };

    const setActiveFilter = (filter) => {
      activeFilter.value = filter;
      // If isotope is initialized, trigger filter
      if (isotopeInstance) {
        setTimeout(() => {
          if (isotopeInstance) {
            isotopeInstance.arrange({ filter: filter === '*' ? '*' : `.filter-${filter}` });
          }
        }, 50);
      }
    };

    const submitContactForm = async () => {
      contactForm.loading = true;
      contactForm.error = '';
      contactForm.success = false;

      try {
        // Send contact form data to Laravel API
        const response = await axios.post('/api/contact', {
          name: contactForm.name,
          email: contactForm.email,
          subject: contactForm.subject,
          message: contactForm.message
        });

        if (response.data.success) {
          contactForm.success = true;
          // Reset form after successful submission
          setTimeout(() => {
            contactForm.name = '';
            contactForm.email = '';
            contactForm.subject = '';
            contactForm.message = '';
            contactForm.success = false;
          }, 3000);
        } else {
          contactForm.error = response.data.message || 'Failed to send message';
        }
      } catch (err) {
        console.error('Error sending contact form:', err);
        contactForm.error = err.response?.data?.message || 
                           err.response?.data?.errors?.email?.[0] || 
                           'Failed to send message. Please try again.';
      } finally {
        contactForm.loading = false;
      }
    };

    const handleScroll = () => {
      const sections = navigation.value.map(nav => nav.id);
      const scrollPosition = window.scrollY + 100;

      for (const sectionId of sections) {
        const section = document.getElementById(sectionId);
        if (section) {
          const sectionTop = section.offsetTop;
          const sectionHeight = section.clientHeight;
          
          if (scrollPosition >= sectionTop && scrollPosition < sectionTop + sectionHeight) {
            activeSection.value = sectionId;
            break;
          }
        }
      }
    };

    // ========== DATA FETCHING ==========
    
    const fetchAllData = async () => {
      try {
        loading.value = true;
        error.value = null;

        // Fetch all data in parallel
        const apiEndpoints = [
          { key: 'hero', url: '/api/hero' },
          { key: 'about', url: '/api/about' },
          { key: 'skills', url: '/api/skills' },
          { key: 'statistics', url: '/api/statistics' },
          { key: 'resume', url: '/api/resume-sections' },
          { key: 'portfolio', url: '/api/portfolio-items' },
          { key: 'services', url: '/api/services' },
          { key: 'testimonials', url: '/api/testimonials' },
          { key: 'contact', url: '/api/contact-info' },
          { key: 'settings', url: '/api/site-settings' }
        ];

        // Create promises for all API calls
        const requests = apiEndpoints.map(endpoint => 
          axios.get(endpoint.url)
            .then(response => ({ key: endpoint.key, data: response.data }))
            .catch(err => {
              console.warn(`Warning: Could not fetch ${endpoint.key}:`, err.message);
              return { key: endpoint.key, data: null, error: err };
            })
        );

        // Wait for all requests to complete
        const results = await Promise.all(requests);

        // Process results
        results.forEach(result => {
          switch (result.key) {
            case 'hero':
              heroData.value = result.data || heroData.value;
              break;
            case 'about':
              aboutData.value = result.data || aboutData.value;
              break;
            case 'skills':
              skills.value = Array.isArray(result.data) ? result.data : [];
              break;
            case 'statistics':
              statsData.value = Array.isArray(result.data) ? result.data : [];
              break;
            case 'resume':
              resumeSections.value = Array.isArray(result.data) ? result.data : [];
              break;
            case 'portfolio':
              portfolioItems.value = Array.isArray(result.data) ? result.data : [];
              break;
            case 'services':
              services.value = Array.isArray(result.data) ? result.data : [];
              break;
            case 'testimonials':
              testimonials.value = Array.isArray(result.data) ? result.data : [];
              break;
            case 'contact':
              contactInfo.value = Array.isArray(result.data) ? result.data : [];
              break;
            case 'settings':
              if (result.data) {
                siteSettings.value = result.data;
                // Set descriptions from site settings
                if (result.data.site_description) {
                  portfolioDescription.value = result.data.site_description;
                  servicesDescription.value = result.data.site_description;
                  testimonialsDescription.value = result.data.site_description;
                  contactDescription.value = result.data.site_description;
                  skillsData.description = result.data.site_description;
                  resumeData.description = result.data.site_description;
                }
              }
              break;
          }
        });

        // Initialize animations after data is loaded
        initializeAnimations();

      } catch (err) {
        console.error('Error fetching portfolio data:', err);
        error.value = 'Failed to load portfolio data. Please check your API endpoints.';
      } finally {
        loading.value = false;
      }
    };

    // ========== ANIMATION INITIALIZATION ==========
    
    const initializeAnimations = () => {
      // Initialize AOS (Animate On Scroll)
      if (window.AOS) {
        aosInstance = window.AOS.init({
          duration: 1000,
          once: true,
          offset: 100,
          easing: 'ease-in-out'
        });
      }

      // Initialize PureCounter for statistics
      if (PureCounter) {
        new PureCounter({
          selector: '.purecounter',
          start: 0,
          end: 0,
          duration: 2,
          delay: 10,
          once: true,
          repeat: false,
          decimals: 0,
          legacy: true,
          filesizing: false,
          currency: false,
          formater: 'us-US',
          currencySymbol: '$'
        });
      }

      // Initialize typing effect for hero section
      if (heroData.value.roles && Array.isArray(heroData.value.roles) && heroData.value.roles.length > 1) {
        if (roleInterval) clearInterval(roleInterval);
        
        const updateRole = () => {
          currentRole.value = (currentRole.value + 1) % heroData.value.roles.length;
        };
        
        // Update role every 3 seconds
        roleInterval = setInterval(updateRole, 3000);
      }

      // Initialize Swiper for testimonials
      if (window.Swiper && testimonials.value.length > 0) {
        try {
          swiperInstance = new window.Swiper('.init-swiper', {
            loop: true,
            speed: 600,
            autoplay: {
              delay: 5000,
              disableOnInteraction: false,
            },
            slidesPerView: 1,
            spaceBetween: 30,
            pagination: {
              el: '.swiper-pagination',
              type: 'bullets',
              clickable: true,
            },
            breakpoints: {
              320: {
                slidesPerView: 1,
                spaceBetween: 20
              },
              768: {
                slidesPerView: 1,
                spaceBetween: 30
              },
              1024: {
                slidesPerView: 1,
                spaceBetween: 40
              }
            }
          });
        } catch (err) {
          console.error('Error initializing Swiper:', err);
        }
      }

      // Initialize GLightbox for portfolio
      if (window.GLightbox && portfolioItems.value.length > 0) {
        try {
          glightboxInstance = window.GLightbox({
            selector: '.glightbox',
            touchNavigation: true,
            loop: true,
            autoplayVideos: true
          });
        } catch (err) {
          console.error('Error initializing GLightbox:', err);
        }
      }

      // Initialize Isotope for portfolio filtering
      if (window.Isotope && portfolioItems.value.length > 0) {
        try {
          // Wait for DOM to be fully loaded
          setTimeout(() => {
            const container = document.querySelector('.isotope-container');
            if (container) {
              isotopeInstance = new window.Isotope(container, {
                itemSelector: '.portfolio-item',
                layoutMode: 'masonry',
                masonry: {
                  columnWidth: '.portfolio-item',
                  gutter: 20
                }
              });

              // Handle filter clicks
              const filterButtons = document.querySelectorAll('.portfolio-filters li');
              filterButtons.forEach(button => {
                button.addEventListener('click', function() {
                  // Remove active class from all buttons
                  filterButtons.forEach(btn => btn.classList.remove('filter-active'));
                  // Add active class to clicked button
                  this.classList.add('filter-active');
                  // Filter items
                  const filterValue = this.getAttribute('data-filter');
                  isotopeInstance.arrange({ filter: filterValue });
                });
              });
            }
          }, 500);
        } catch (err) {
          console.error('Error initializing Isotope:', err);
        }
      }

      // Initialize scroll reveal animations
      initializeScrollReveal();
    };

    const initializeScrollReveal = () => {
      // Simple scroll reveal for elements
      const observerOptions = {
        root: null,
        rootMargin: '0px',
        threshold: 0.1
      };

      scrollObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
          if (entry.isIntersecting) {
            entry.target.classList.add('animated');
          }
        });
      }, observerOptions);

      // Observe all sections
      document.querySelectorAll('section').forEach(section => {
        scrollObserver.observe(section);
      });
    };

    // ========== CLEANUP FUNCTION ==========
    
    const cleanupAnimations = () => {
      // Clear intervals
      if (roleInterval) {
        clearInterval(roleInterval);
        roleInterval = null;
      }
      
      // Destroy Swiper
      if (swiperInstance && swiperInstance.destroy) {
        swiperInstance.destroy();
        swiperInstance = null;
      }
      
      // Destroy GLightbox
      if (glightboxInstance && glightboxInstance.destroy) {
        glightboxInstance.destroy();
        glightboxInstance = null;
      }
      
      // Destroy Isotope
      if (isotopeInstance && isotopeInstance.destroy) {
        isotopeInstance.destroy();
        isotopeInstance = null;
      }
      
      // Destroy scroll observer
      if (scrollObserver) {
        scrollObserver.disconnect();
        scrollObserver = null;
      }
      
      // Destroy AOS
      if (aosInstance && window.AOS) {
        window.AOS.refreshHard();
      }
    };

    // ========== LIFECYCLE HOOKS ==========
    
    onMounted(() => {
      // Fetch all data when component mounts
      fetchAllData();
      
      // Add scroll event listener
      window.addEventListener('scroll', handleScroll);
      
      // Add keyboard shortcuts
      document.addEventListener('keydown', (e) => {
        // Escape key closes mobile nav
        if (e.key === 'Escape' && navActive.value) {
          navActive.value = false;
        }
        // Home key scrolls to top
        if (e.key === 'Home') {
          scrollToTop();
          e.preventDefault();
        }
      });
    });

    onUnmounted(() => {
      // Cleanup event listeners
      window.removeEventListener('scroll', handleScroll);
      document.removeEventListener('keydown', null);
      
      // Cleanup animations
      cleanupAnimations();
    });

    // ========== RETURN ALL REACTIVE PROPERTIES ==========
    
    return {
      // State
      navActive,
      activeSection,
      currentRole,
      activeFilter,
      loading,
      error,
      showLoading,
      
      // Form
      contactForm,
      
      // Navigation
      navigation,
      
      // Portfolio Data
      heroData,
      aboutData,
      statsData,
      skills,
      resumeSections,
      portfolioItems,
      services,
      testimonials,
      contactInfo,
      siteSettings,
      
      // Filters and Descriptions
      portfolioFilters,
      portfolioDescription,
      servicesDescription,
      testimonialsDescription,
      contactDescription,
      skillsData,
      resumeData,
      
      // Computed Properties
      leftSkills,
      rightSkills,
      resumeSummary,
      resumeEducation,
      resumeExperience,
      filteredPortfolioItems,
      aboutDetails,
      
      // Methods
      toggleNav,
      scrollToSection,
      scrollToTop,
      setActiveFilter,
      submitContactForm,
      handleScroll,
      fetchAllData,
      initializeAnimations,
      cleanupAnimations
    };
  }
};
</script>

<style scoped>
/* Add any component-specific styles here */
/* Note: Most styles are in the external CSS files */

/* Loading indicator */
.loading-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(255, 255, 255, 0.8);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 9999;
}

/* Component-specific styles for Vue transitions */
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.5s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}

/* Responsive nav menu */
@media (max-width: 1199.98px) {
  .navmenu.active {
    left: 0;
  }
}

/* Portfolio filter active state */
.portfolio-filters li.filter-active {
  color: var(--accent-color);
  border-color: var(--accent-color);
}

/* Progress bar animation */
@keyframes progressBar {
  0% {
    width: 0;
  }
  100% {
    width: var(--progress-width);
  }
}

.progress-bar {
  animation: progressBar 1.5s ease-in-out;
  animation-fill-mode: both;
}

/* Contact form states */
.php-email-form .loading,
.php-email-form .error-message,
.php-email-form .sent-message {
  display: none;
}

.php-email-form .loading:not(:empty),
.php-email-form .error-message:not(:empty),
.php-email-form .sent-message:not(:empty) {
  display: block;
  margin-bottom: 15px;
  padding: 10px;
  border-radius: 4px;
}

.php-email-form .loading {
  background: #f8f9fa;
  color: #495057;
}

.php-email-form .error-message {
  background: #f8d7da;
  color: #721c24;
}

.php-email-form .sent-message {
  background: #d4edda;
  color: #155724;
}

/* Custom scrollbar */
::-webkit-scrollbar {
  width: 10px;
}

::-webkit-scrollbar-track {
  background: #f1f1f1;
}

::-webkit-scrollbar-thumb {
  background: #888;
  border-radius: 5px;
}

::-webkit-scrollbar-thumb:hover {
  background: #555;
}
</style>