<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Noteku') }} - Premium Workspace for Deep Work</title>
    <meta name="description" content="Organize your notes efficiently with Noteku. Smart search, categories, tags, and more for deep work focus.">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&display=swap" rel="stylesheet">

    <!-- Styles / Scripts -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <style>
            /* Reset & Base Styles */
            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }
            
            body {
                font-family: 'Inter', sans-serif;
                background: #0D1117;
                min-height: 100vh;
                color: #e0e0e0;
            }
            
            /* Custom Scrollbar */
            ::-webkit-scrollbar { width: 6px; height: 6px; }
            ::-webkit-scrollbar-track { background: #1A1D24; border-radius: 10px; }
            ::-webkit-scrollbar-thumb { background: #23A9BD; border-radius: 10px; }
            ::-webkit-scrollbar-thumb:hover { background: #1D8FA0; }
            
            /* Container */
            .container {
                max-width: 1280px;
                margin: 0 auto;
                padding: 0 1rem;
            }
            
            @media (min-width: 640px) {
                .container { padding: 0 1.5rem; }
            }
            @media (min-width: 1024px) {
                .container { padding: 0 2rem; }
            }
            
            /* Navigation */
            .navbar {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                z-index: 50;
                background: rgba(10, 12, 15, 0.9);
                backdrop-filter: blur(12px);
                border-bottom: 1px solid rgba(35, 169, 189, 0.2);
            }
            
            .navbar-inner {
                display: flex;
                align-items: center;
                justify-content: space-between;
                height: 64px;
                max-width: 1280px;
                margin: 0 auto;
                padding: 0 1rem;
            }
            
            @media (min-width: 640px) {
                .navbar-inner { padding: 0 1.5rem; }
            }
            @media (min-width: 1024px) {
                .navbar-inner { padding: 0 2rem; }
            }
            
            .logo {
                font-size: 1.25rem;
                font-weight: 700;
                background: linear-gradient(135deg, #23A9BD 0%, #0D4E59 100%);
                -webkit-background-clip: text;
                background-clip: text;
                color: transparent;
                text-decoration: none;
            }
            
            .nav-links {
                display: none;
                gap: 2rem;
            }
            
            @media (min-width: 768px) {
                .nav-links { display: flex; }
            }
            
            .nav-link {
                font-size: 0.875rem;
                color: #94A3B8;
                text-decoration: none;
                transition: color 0.3s;
            }
            
            .nav-link:hover { color: #23A9BD; }
            
            /* Buttons */
            .btn-primary {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                padding: 0.5rem 1rem;
                font-size: 0.875rem;
                font-weight: 500;
                color: white;
                background: #23A9BD;
                border: none;
                border-radius: 0.5rem;
                text-decoration: none;
                transition: all 0.3s;
                cursor: pointer;
            }
            
            .btn-primary:hover {
                background: #1D8FA0;
                transform: translateY(-2px);
            }
            
            .btn-outline {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                padding: 0.5rem 1rem;
                font-size: 0.875rem;
                font-weight: 500;
                color: #23A9BD;
                background: transparent;
                border: 1px solid rgba(35, 169, 189, 0.3);
                border-radius: 0.5rem;
                text-decoration: none;
                transition: all 0.3s;
            }
            
            .btn-outline:hover {
                background: rgba(35, 169, 189, 0.1);
                border-color: rgba(35, 169, 189, 0.5);
            }
            
            .btn-large {
                padding: 0.75rem 1.5rem;
                font-size: 0.875rem;
            }
            
            /* Hero Section */
            .hero {
                position: relative;
                min-height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
                padding-top: 4rem;
                overflow: hidden;
            }
            
            .hero-bg-1 {
                position: absolute;
                top: -10rem;
                right: -10rem;
                width: 20rem;
                height: 20rem;
                background: #23A9BD;
                border-radius: 9999px;
                opacity: 0.1;
                filter: blur(64px);
                animation: pulse 3s ease-in-out infinite;
            }
            
            .hero-bg-2 {
                position: absolute;
                bottom: -10rem;
                left: -10rem;
                width: 20rem;
                height: 20rem;
                background: #0D4E59;
                border-radius: 9999px;
                opacity: 0.1;
                filter: blur(64px);
                animation: pulse 3s ease-in-out infinite 1.5s;
            }
            
            @keyframes pulse {
                0%, 100% { opacity: 0.1; }
                50% { opacity: 0.2; }
            }
            
            .hero-content {
                display: flex;
                flex-direction: column;
                align-items: center;
                gap: 3rem;
                max-width: 1280px;
                margin: 0 auto;
                padding: 5rem 1rem;
                position: relative;
                z-index: 10;
            }
            
            @media (min-width: 1024px) {
                .hero-content {
                    flex-direction: row;
                    text-align: left;
                }
            }
            
            .hero-left {
                flex: 1;
                text-align: center;
            }
            
            @media (min-width: 1024px) {
                .hero-left { text-align: left; }
            }
            
            .hero-badge {
                display: inline-block;
                padding: 0.25rem 0.75rem;
                font-size: 0.75rem;
                background: rgba(35, 169, 189, 0.1);
                border: 1px solid rgba(35, 169, 189, 0.2);
                border-radius: 9999px;
                color: #23A9BD;
                margin-bottom: 1.5rem;
            }
            
            .hero-title {
                font-size: 2.5rem;
                font-weight: 700;
                margin-bottom: 1.5rem;
            }
            
            @media (min-width: 640px) {
                .hero-title { font-size: 3rem; }
            }
            @media (min-width: 1024px) {
                .hero-title { font-size: 3.75rem; }
            }
            
            .gradient-text {
                background: linear-gradient(135deg, #23A9BD 0%, #0D4E59 100%);
                -webkit-background-clip: text;
                background-clip: text;
                color: transparent;
            }
            
            .hero-description {
                font-size: 1.125rem;
                color: #94A3B8;
                margin-bottom: 2rem;
                max-width: 32rem;
            }
            
            @media (min-width: 1024px) {
                .hero-description { margin-left: 0; margin-right: 0; }
            }
            
            .hero-buttons {
                display: flex;
                flex-direction: column;
                gap: 1rem;
                justify-content: center;
            }
            
            @media (min-width: 640px) {
                .hero-buttons { flex-direction: row; }
            }
            @media (min-width: 1024px) {
                .hero-buttons { justify-content: flex-start; }
            }
            
            .stats {
                display: flex;
                flex-wrap: wrap;
                gap: 2rem;
                justify-content: center;
                margin-top: 3rem;
            }
            
            @media (min-width: 1024px) {
                .stats { justify-content: flex-start; }
            }
            
            .stat-value {
                font-size: 1.5rem;
                font-weight: 700;
                color: white;
            }
            
            .stat-label {
                font-size: 0.75rem;
                color: #94A3B8;
            }
            
            /* Glass Card */
            .glass-card {
                background: rgba(13, 78, 89, 0.05);
                backdrop-filter: blur(12px);
                border: 1px solid rgba(35, 169, 189, 0.15);
                border-radius: 1rem;
                transition: all 0.3s;
            }
            
            .glass-card:hover {
                border-color: rgba(35, 169, 189, 0.35);
            }
            
            /* Features Section */
            .features-section {
                padding: 5rem 0;
            }
            
            .section-title {
                text-align: center;
                margin-bottom: 3rem;
            }
            
            .section-title h2 {
                font-size: 1.875rem;
                font-weight: 700;
                margin-bottom: 1rem;
            }
            
            @media (min-width: 640px) {
                .section-title h2 { font-size: 2.25rem; }
            }
            
            .section-title p {
                color: #94A3B8;
                max-width: 42rem;
                margin: 0 auto;
            }
            
            .features-grid {
                display: grid;
                grid-template-columns: 1fr;
                gap: 1.5rem;
                max-width: 1280px;
                margin: 0 auto;
                padding: 0 1rem;
            }
            
            @media (min-width: 768px) {
                .features-grid { grid-template-columns: repeat(2, 1fr); }
            }
            @media (min-width: 1024px) {
                .features-grid { grid-template-columns: repeat(3, 1fr); }
            }
            
            .feature-card {
                padding: 1.5rem;
                transition: all 0.3s;
                animation: fadeInUp 0.6s ease-out forwards;
                opacity: 0;
                transform: translateY(30px);
            }
            
            .feature-card:hover {
                transform: translateY(-4px);
                border-color: rgba(35, 169, 189, 0.4);
                background: rgba(13, 78, 89, 0.08);
            }
            
            .feature-icon {
                width: 3rem;
                height: 3rem;
                background: rgba(35, 169, 189, 0.1);
                border-radius: 0.5rem;
                display: flex;
                align-items: center;
                justify-content: center;
                margin-bottom: 1rem;
            }
            
            .feature-icon svg {
                width: 1.5rem;
                height: 1.5rem;
                color: #23A9BD;
            }
            
            .feature-title {
                font-size: 1.125rem;
                font-weight: 600;
                color: white;
                margin-bottom: 0.5rem;
            }
            
            .feature-description {
                font-size: 0.875rem;
                color: #94A3B8;
            }
            
            /* CTA Section */
            .cta-section {
                padding: 5rem 0;
            }
            
            .cta-card {
                max-width: 64rem;
                margin: 0 auto;
                padding: 2rem;
                text-align: center;
            }
            
            @media (min-width: 768px) {
                .cta-card { padding: 3rem; }
            }
            
            .cta-title {
                font-size: 1.875rem;
                font-weight: 700;
                margin-bottom: 1rem;
            }
            
            @media (min-width: 768px) {
                .cta-title { font-size: 2.25rem; }
            }
            
            .cta-description {
                color: #94A3B8;
                margin-bottom: 2rem;
                max-width: 42rem;
                margin-left: auto;
                margin-right: auto;
            }
            
            /* Footer */
            .footer {
                padding: 3rem 0;
                border-top: 1px solid rgba(35, 169, 189, 0.1);
            }
            
            .footer-inner {
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: space-between;
                gap: 1.5rem;
                max-width: 1280px;
                margin: 0 auto;
                padding: 0 1rem;
            }
            
            @media (min-width: 768px) {
                .footer-inner { flex-direction: row; }
            }
            
            .footer-logo {
                font-size: 1.125rem;
                font-weight: 700;
                background: linear-gradient(135deg, #23A9BD 0%, #0D4E59 100%);
                -webkit-background-clip: text;
                background-clip: text;
                color: transparent;
                margin-bottom: 0.5rem;
            }
            
            .footer-copyright {
                font-size: 0.75rem;
                color: #94A3B8;
            }
            
            .footer-links {
                display: flex;
                gap: 2rem;
            }
            
            .footer-link {
                font-size: 0.75rem;
                color: #94A3B8;
                text-decoration: none;
                transition: color 0.3s;
            }
            
            .footer-link:hover { color: #23A9BD; }
            
            /* Animations */
            @keyframes fadeInUp {
                from {
                    opacity: 0;
                    transform: translateY(30px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
            
            @keyframes fadeInLeft {
                from {
                    opacity: 0;
                    transform: translateX(-30px);
                }
                to {
                    opacity: 1;
                    transform: translateX(0);
                }
            }
            
            @keyframes fadeInRight {
                from {
                    opacity: 0;
                    transform: translateX(30px);
                }
                to {
                    opacity: 1;
                    transform: translateX(0);
                }
            }
            
            .animate-left {
                animation: fadeInLeft 0.6s ease-out forwards;
            }
            
            .animate-right {
                animation: fadeInRight 0.6s ease-out forwards;
            }
            
            .delay-100 { animation-delay: 0.1s; }
            .delay-200 { animation-delay: 0.2s; }
            .delay-300 { animation-delay: 0.3s; }
            .delay-400 { animation-delay: 0.4s; }
            .delay-500 { animation-delay: 0.5s; }
            
            /* Utility */
            .text-center { text-align: center; }
            .mx-auto { margin-left: auto; margin-right: auto; }
            .w-full { width: 100%; }
            .inline-flex { display: inline-flex; }
            .items-center { align-items: center; }
            .gap-2 { gap: 0.5rem; }
            .ml-2 { margin-left: 0.5rem; }
            .mt-12 { margin-top: 3rem; }
            .mb-4 { margin-bottom: 1rem; }
            .mb-6 { margin-bottom: 1.5rem; }
            .mb-8 { margin-bottom: 2rem; }
            
            /* Responsive */
            @media (max-width: 768px) {
                .hero-title { font-size: 2rem; }
                .section-title h2 { font-size: 1.75rem; }
                .cta-title { font-size: 1.75rem; }
            }
        </style>
    @endif
</head>

<body>

    <!-- Navigation -->
    <nav class="navbar">
        <div class="navbar-inner">
            <a href="{{ url('/') }}" class="logo">Noteku</a>
            <div class="nav-links">
                <a href="#features" class="nav-link">Features</a>
                <a href="#pricing" class="nav-link">Pricing</a>
                <a href="#testimonials" class="nav-link">Testimonials</a>
            </div>
            <div style="display: flex; align-items: center; gap: 0.75rem;">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="btn-primary">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="btn-outline">Log in</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="btn-primary">Sign up</a>
                        @endif
                    @endauth
                @endif
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-bg-1"></div>
        <div class="hero-bg-2"></div>
        <div class="hero-content">
            <div class="hero-left animate-left">
                <div class="hero-badge">🚀 Organize Your Notes Efficiently</div>
                <h1 class="hero-title">
                    <span class="gradient-text">Distribute the power</span><br>
                    <span style="color: #ffffff;">of your handwritten notes</span>
                </h1>
                <p class="hero-description">
                    with our new, intuitive app. Designed for deep work and ultimate productivity.
                </p>
                <div class="hero-buttons">
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="btn-primary btn-large">
                            Get Started
                            <svg class="ml-2" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                            </svg>
                        </a>
                    @endif
                </div>
                <div class="stats">
                    <div>
                        <div class="stat-value">10K+</div>
                        <div class="stat-label">Active Users</div>
                    </div>
                    <div>
                        <div class="stat-value">50K+</div>
                        <div class="stat-label">Notes Created</div>
                    </div>
                    <div>
                        <div class="stat-value">99%</div>
                        <div class="stat-label">Satisfaction</div>
                    </div>
                </div>
            </div>
            <div class="animate-right" style="flex: 1;">
                <div class="glass-card" style="padding: 1.5rem; transform: rotate(2deg);">
                    <img src="https://i.pinimg.com/1200x/5c/54/a4/5c54a402c2877e206e547544747f7f6f.jpg" alt="App Screenshot" class="w-full rounded-lg shadow-lg">
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="features-section">
        <div class="section-title">
            <h2><span class="gradient-text">Everything You Need</span></h2>
            <p>Everything you need to get started with professional note-taking and deep work.</p>
        </div>
        <div class="features-grid">
            <div class="glass-card feature-card delay-100">
                <div class="feature-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <h3 class="feature-title">Smart Search</h3>
                <p class="feature-description">Find exactly what you're looking for with our intelligent search that understands context.</p>
            </div>
            <div class="glass-card feature-card delay-200">
                <div class="feature-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l5 5a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-5-5A2 2 0 013 13V5a2 2 0 012-2z"/>
                    </svg>
                </div>
                <h3 class="feature-title">Categories & Tags</h3>
                <p class="feature-description">Organize your notes with custom categories and tags for easy retrieval.</p>
            </div>
            <div class="glass-card feature-card delay-300">
                <div class="feature-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/>
                    </svg>
                </div>
                <h3 class="feature-title">Pin Important</h3>
                <p class="feature-description">Pin the most important notes to the top of your list for quick access.</p>
            </div>
            <div class="glass-card feature-card delay-400">
                <div class="feature-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                    </svg>
                </div>
                <h3 class="feature-title">Archive & Trash</h3>
                <p class="feature-description">Keep your workspace clean with archive and trash management.</p>
            </div>
            <div class="glass-card feature-card delay-500">
                <div class="feature-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
                <h3 class="feature-title">Team Collaboration</h3>
                <p class="feature-description">Work together with your team in real-time on shared notes.</p>
            </div>
            <div class="glass-card feature-card delay-500">
                <div class="feature-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                    </svg>
                </div>
                <h3 class="feature-title">Dark Mode Ready</h3>
                <p class="feature-description">Enjoy deep focus with our carefully crafted dark theme.</p>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section id="pricing" class="cta-section">
        <div class="glass-card cta-card">
            <h2 class="cta-title"><span class="gradient-text">Ready for Deep Work?</span></h2>
            <p class="cta-description">All features of Noteku for Web, Desktop, and Mobile are available with your account. Start organizing your thoughts today.</p>
            @if (Route::has('register'))
                <a href="{{ route('register') }}" class="btn-primary btn-large">
                    Start Your New Account Now
                    <svg class="ml-2" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                </a>
            @endif
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-inner">
            <div>
                <h3 class="footer-logo">NOTES</h3>
                <p class="footer-copyright">© {{ date('Y') }} Noteku. All rights reserved.</p>
            </div>
            <div class="footer-links">
                <a href="#" class="footer-link">Privacy Policy</a>
                <a href="#" class="footer-link">Terms of Service</a>
                <a href="#" class="footer-link">Contact</a>
                <a href="#" class="footer-link">Twitter</a>
            </div>
        </div>
    </footer>

    <script>
        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            });
        });
        
        // Intersection Observer for scroll animations
        const observerOptions = { threshold: 0.1, rootMargin: '0px 0px -50px 0px' };
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);
        
        document.querySelectorAll('.feature-card').forEach(el => {
            observer.observe(el);
        });
    </script>
</body>
</html>