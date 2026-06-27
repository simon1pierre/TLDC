<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
@php
  $siteName = $siteSettings?->translated('site_name') ?: __('messages.site.name');
  $siteTagline = $siteSettings?->translated('site_tagline') ?: __('messages.site.tagline');
  $siteDescription = $siteSettings?->translated('site_description')
    ?: __('messages.site.description');
  $siteTitle = trim($siteName . ' | ' . $siteTagline, ' |');
  $logoPath = $siteSettings?->logo ? asset('storage/' . $siteSettings->logo) : asset('logo/New/THE LAST DAYS COVENANTS Logo.png');
  $faviconPath = $siteSettings?->favicon ? asset('storage/' . $siteSettings->favicon) : asset('logo/favicon-32x32.png');
  $faviconPathSmall = $siteSettings?->favicon ? asset('storage/' . $siteSettings->favicon) : asset('logo/favicon-16x16.png');
  $contactEmail = $siteSettings?->contact_email ?: 'contact@thelastdayscovenants.org';
  $contactAddress = $siteSettings?->contact_address ?: 'Global Online Ministry';
  $footerText = $siteSettings?->translated('footer_text')
    ?: __('messages.site.footer_text');
  $normalizeUrl = function (?string $value, string $fallback) {
    if (empty($value)) {
      return $fallback;
    }
    if (!preg_match('~^https?://~i', $value)) {
      // return 'https://'.$value;
    }
    return $value;
  };
  $facebookUrl = $normalizeUrl($siteSettings?->facebook_url, 'https://www.facebook.com/');
  $instagramUrl = $normalizeUrl($siteSettings?->instagram_url, 'https://www.instagram.com/');
  $youtubeUrl = $normalizeUrl($siteSettings?->youtube_channel, 'https://www.youtube.com/');
  $twitterUrl = $normalizeUrl($siteSettings?->twitter_url, 'https://x.com/');
  $tiktokUrl = $normalizeUrl($siteSettings?->tiktok_url, 'https://www.tiktok.com/');
  $whatsappUrl = $normalizeUrl($siteSettings?->whatsapp_url, 'https://www.whatsapp.com/');
  $telegramUrl = $normalizeUrl($siteSettings?->telegram_url, 'https://telegram.org/');
  $currentLocale = app()->getLocale();
  $isHome = request()->routeIs('home');
  $homeUrl = route('home');
  $resourcesLink = route('resources');
  $sermonsLink = $isHome ? '#sermons' : $homeUrl . '#sermons';
  $homeAboutLink = $isHome ? '#about' : $homeUrl . '#about';
  $themeColor = $siteSettings?->primary_color ?: '#00283c';
  $tawkEnabled = (bool) ($siteSettings?->live_chat_enabled ?? false);
  $tawkProperty = $siteSettings?->tawk_property_id;
  $tawkWidget = $siteSettings?->tawk_widget_id;
@endphp
<head>
  <script>if(localStorage.getItem('dark')==='true'||(!localStorage.getItem('dark')&&window.matchMedia('(prefers-color-scheme:dark)').matches))document.documentElement.classList.add('dark')</script>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{ $siteTitle }}</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="description" content="{{ $siteDescription }}">
  <meta name="application-name" content="{{ $siteName }}">
  <meta name="theme-color" content="{{ $themeColor }}">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="default">
  <link rel="manifest" href="{{ asset('manifest.json') }}">
  <link rel="canonical" href="https://thelastdayscovenants.org">
 <!-- Favicon -->
<link rel="icon" type="image/png" sizes="18x18" href="{{ $faviconPathSmall }}">
<link rel="icon" type="image/png" sizes="32x32" href="{{ $faviconPath }}">
<!-- Apple Touch Icon -->
<link rel="apple-touch-icon" sizes="180x180" href="{{ $faviconPath }}">
<link rel="apple-touch-icon" sizes="192x192" href="{{ asset('pwa/icon-192.png') }}">
<link rel="apple-touch-icon" sizes="512x512" href="{{ asset('pwa/icon-512.png') }}">

  <!-- Open Graph / Facebook -->
  <meta property="og:type" content="website">
  <meta property="og:url" content="https://thelastdayscovenants.org">
  <meta property="og:title" content="{{ $siteTitle }}">
  <meta property="og:description" content="{{ $siteDescription }}">
  <meta property="og:image" content="https://thelastdayscovenants.org/og-image.jpg">


  <!-- Twitter -->
  <meta property="twitter:card" content="summary_large_image">
  <meta property="twitter:url" content="https://thelastdayscovenants.org">
  <meta property="twitter:title" content="{{ $siteTitle }}">
  <meta property="twitter:description" content="{{ $siteDescription }}">
  <meta property="twitter:image" content="https://thelastdayscovenants.org/twitter-image.jpg">


  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Lato:wght@300;400;700&display=swap" rel="stylesheet">


  <!-- Tailwind & Lucide -->
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  <script src="https://unpkg.com/lucide@latest"></script>



  <style>
    body {
      background-color: #f8fafc;
      color: #1e293b;
    }

    .paper-lines {
      --paper-h: rgba(0, 40, 60, 0.08);
      --paper-v: rgba(0, 40, 60, 0.035);
      background-image:
        repeating-linear-gradient(
          0deg,
          transparent,
          transparent 27px,
          var(--paper-h) 27px,
          var(--paper-h) 28px
        ),
        repeating-linear-gradient(
          90deg,
          transparent,
          transparent 27px,
          var(--paper-v) 27px,
          var(--paper-v) 28px
        );
      background-size: 28px 28px;
    }

    @media (prefers-color-scheme: dark) {
      .paper-lines {
        --paper-h: rgba(148, 163, 184, 0.055);
        --paper-v: rgba(148, 163, 184, 0.025);
      }
    }

    .hero-paper::before {
      content: '';
      position: absolute;
      inset: 0;
      pointer-events: none;
      background-image:
        repeating-linear-gradient(
          0deg,
          transparent,
          transparent 27px,
          rgba(255, 255, 255, 0.035) 27px,
          rgba(255, 255, 255, 0.035) 28px
        ),
        repeating-linear-gradient(
          90deg,
          transparent,
          transparent 27px,
          rgba(255, 255, 255, 0.02) 27px,
          rgba(255, 255, 255, 0.02) 28px
        );
      background-size: 28px 28px;
    }


    /* ===== ENGAGEMENT ANIMATIONS ===== */


    /* Fade In Animation */
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


    @keyframes fadeInDown {
      from {
        opacity: 0;
        transform: translateY(-30px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }


    @keyframes fadeIn {
      from { opacity: 0; }
      to { opacity: 1; }
    }


    @keyframes slideInLeft {
      from {
        opacity: 0;
        transform: translateX(-50px);
      }
      to {
        opacity: 1;
        transform: translateX(0);
      }
    }


    @keyframes slideInRight {
      from {
        opacity: 0;
        transform: translateX(50px);
      }
      to {
        opacity: 1;
        transform: translateX(0);
      }
    }


    @keyframes scaleIn {
      from {
        opacity: 0;
        transform: scale(0.95);
      }
      to {
        opacity: 1;
        transform: scale(1);
      }
    }


    @keyframes bounce-light {
      0%, 100% { transform: translateY(0); }
      50% { transform: translateY(-10px); }
    }


    /* ===== EVENT-DRIVEN CONTINUOUS ANIMATIONS ===== */


    /* Continuous Glow Pulse */
    @keyframes glow-pulse {
      0%, 100% {
        box-shadow: 0 0 20px rgba(15, 43, 94, 0.3);
      }
      50% {
        box-shadow: 0 0 40px rgba(15, 43, 94, 0.6);
      }
    }


    /* Shimmer Effect */
    @keyframes shimmer {
      0% {
        background-position: -1000px 0;
      }
      100% {
        background-position: 1000px 0;
      }
    }


    /* Continuous Float */
    @keyframes float-continuous {
      0%, 100% { transform: translateY(0px) rotate(0deg); }
      25% { transform: translateY(-8px) rotate(1deg); }
      50% { transform: translateY(0px) rotate(0deg); }
      75% { transform: translateY(-5px) rotate(-1deg); }
    }


    /* Ripple Click Effect */
    @keyframes ripple {
      0% {
        transform: scale(0);
        opacity: 1;
      }
      100% {
        transform: scale(4);
        opacity: 0;
      }
    }


    /* Subtle Rotate on Hover */
    @keyframes subtle-rotate {
      0%, 100% { transform: rotateY(0deg) rotateX(0deg); }
      50% { transform: rotateY(5deg) rotateX(-3deg); }
    }


    /* Continuous Background Pulse */
    @keyframes bg-pulse {
      0%, 100% { background-color: rgba(15, 43, 94, 0.02); }
      50% { background-color: rgba(15, 43, 94, 0.08); }
    }


    /* Text Wave Animation */
    @keyframes wave {
      0%, 100% { transform: translateY(0px); }
      50% { transform: translateY(-8px); }
    }


    /* Icon Spin on Hover */
    @keyframes spin-smooth {
      from { transform: rotate(0deg); }
      to { transform: rotate(360deg); }
    }


    /* ===== ANIMATION UTILITY CLASSES ===== */
    .animate-fade-in-up {
      animation: fadeInUp 0.8s ease-out;
    }


    .animate-fade-in-down {
      animation: fadeInDown 0.8s ease-out;
    }


    .animate-fade-in {
      animation: fadeIn 0.6s ease-out;
    }


    .animate-slide-in-left {
      animation: slideInLeft 0.8s ease-out;
    }


    .animate-slide-in-right {
      animation: slideInRight 0.8s ease-out;
    }


    .animate-scale-in {
      animation: scaleIn 0.6s ease-out;
    }


    .animate-bounce-light {
      animation: bounce-light 2s ease-in-out infinite;
    }


    /* Stagger animations for multiple elements */
    .animate-stagger > * {
      animation: fadeInUp 0.8s ease-out;
    }


    .animate-stagger > *:nth-child(1) { animation-delay: 0.1s; }
    .animate-stagger > *:nth-child(2) { animation-delay: 0.2s; }
    .animate-stagger > *:nth-child(3) { animation-delay: 0.3s; }
    .animate-stagger > *:nth-child(4) { animation-delay: 0.4s; }
    .animate-stagger > *:nth-child(5) { animation-delay: 0.5s; }


    /* Scroll-triggered animation state */
    .scroll-animate {
      opacity: 0;
    }


    .scroll-animate.is-visible {
      opacity: 1;
      animation: fadeInUp 0.8s ease-out forwards;
    }


    /* ===== EVENT-DRIVEN ANIMATION CLASSES ===== */


    /* Continuous Glow Pulse (Always Active) */
    .animate-glow-continuous {
      animation: glow-pulse 3s ease-in-out infinite;
    }


    /* Floating Animation (Continuous) */
    .animate-float-continuous {
      animation: float-continuous 4s ease-in-out infinite;
    }


    /* Floating Animation - Slow */
    .animate-float-slow {
      animation: float-continuous 5.5s ease-in-out infinite;
    }


    /* Background Pulse on Hover */
    .hover-bg-pulse:hover {
      animation: bg-pulse 2s ease-in-out infinite;
    }


    /* Ripple Effect Container */
    .ripple-container {
      position: relative;
      overflow: hidden;
    }


    .ripple {
      position: absolute;
      border-radius: 50%;
      background-color: rgba(255, 255, 255, 0.6);
      transform: scale(0);
      animation: ripple 0.6s ease-out;
      pointer-events: none;
    }


    /* Continuous Rotation on Hover */
    .hover-spin:hover svg,
    .hover-spin:hover i {
      animation: spin-smooth 1s linear infinite;
    }


    /* Card with Continuous Glow */
    .card-glow {
      animation: glow-pulse 3s ease-in-out infinite;
    }


    /* Text Wave for Headers */
    .animate-wave > * {
      display: inline-block;
    }


    .animate-wave > *:nth-child(1) { animation: wave 1.2s ease-in-out infinite; animation-delay: 0s; }
    .animate-wave > *:nth-child(2) { animation: wave 1.2s ease-in-out infinite; animation-delay: 0.1s; }
    .animate-wave > *:nth-child(3) { animation: wave 1.2s ease-in-out infinite; animation-delay: 0.2s; }
    .animate-wave > *:nth-child(4) { animation: wave 1.2s ease-in-out infinite; animation-delay: 0.3s; }


    /* Button Click Pulse Effect */
    .btn-pulse:active {
      animation: scaleIn 0.4s ease-out;
    }


    /* Continuous Hover Effects */
    .hover-glow-intense:hover {
      animation: glow-pulse 1.5s ease-in-out infinite;
    }


    /* Shimmer Overlay for Loading States (Optional) */
    .shimmer-overlay {
      background: linear-gradient(
        90deg,
        transparent,
        rgba(255, 255, 255, 0.2),
        transparent
      );
      background-size: 1000px 100%;
      animation: shimmer 2s infinite;
    }


    /* Hover Effects */
    .hover-lift {
      transition: all 0.3s ease-out;
    }


    .hover-lift:hover {
      transform: translateY(-8px);
      box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
    }


    .hover-scale {
      transition: transform 0.3s ease-out;
    }


    .hover-scale:hover {
      transform: scale(1.05);
    }


    .hover-glow {
      transition: all 0.3s ease-out;
    }


    .hover-glow:hover {
      box-shadow: 0 0 30px rgba(15, 43, 94, 0.3);
    }


    /* Pulse Animation */
    @keyframes pulse-slow {
      0%, 100% { opacity: 1; }
      50% { opacity: 0.7; }
    }


    .animate-pulse-slow {
      animation: pulse-slow 3s ease-in-out infinite;
    }

    .ambient-stage {
      position: fixed;
      inset: 0;
      pointer-events: none;
      z-index: 0;
      overflow: hidden;
    }

    .ambient-orb {
      position: absolute;
      border-radius: 9999px;
      filter: blur(80px);
      opacity: 0.18;
      animation: orb-drift 24s ease-in-out infinite;
      transform: translate3d(0, 0, 0);
    }

    .ambient-orb--one {
      width: 24rem;
      height: 24rem;
      top: 12%;
      left: -6rem;
      background: rgba(15, 43, 94, 0.65);
    }

    .ambient-orb--two {
      width: 20rem;
      height: 20rem;
      top: 45%;
      right: -5rem;
      background: rgba(212, 175, 55, 0.45);
      animation-delay: 3s;
    }

    .ambient-orb--three {
      width: 16rem;
      height: 16rem;
      bottom: 6%;
      left: 25%;
      background: rgba(56, 189, 248, 0.35);
      animation-delay: 6s;
    }

    @keyframes orb-drift {
      0%, 100% { transform: translate3d(0, 0, 0) scale(1); }
      50% { transform: translate3d(0, -18px, 0) scale(1.04); }
    }



    .interactive-card {
      transition:
        transform 320ms cubic-bezier(0.2, 0.7, 0.2, 1),
        box-shadow 320ms ease,
        border-color 320ms ease;
      transform-style: preserve-3d;
    }

    .interactive-card:hover {
      transform: translate3d(0, -8px, 0);
      box-shadow: 0 18px 36px rgba(0, 40, 60, 0.13);
      border-color: rgba(0, 40, 60, 0.2);
    }

    [data-tap-reveal].is-active .tap-overlay,
    [data-tap-reveal]:focus-within .tap-overlay {
      opacity: 1 !important;
      transform: translateY(0) !important;
    }

    @keyframes pulse-slow {
      0%, 100% { opacity: 0.15; transform: scale(1); }
      50% { opacity: 0.3; transform: scale(1.05); }
    }

    @keyframes pulse-slower {
      0%, 100% { opacity: 0.08; transform: scale(1); }
      50% { opacity: 0.18; transform: scale(1.08); }
    }

    @keyframes scroll-bounce {
      0%, 100% { transform: translateY(0); opacity: 0.6; }
      50% { transform: translateY(6px); opacity: 1; }
    }

    @keyframes mesh-shift {
      0% { transform: translate(0, 0) scale(1); }
      33% { transform: translate(2%, -1%) scale(1.02); }
      66% { transform: translate(-1%, 2%) scale(0.98); }
      100% { transform: translate(0, 0) scale(1); }
    }

    @keyframes count-up {
      from { opacity: 0; transform: translateY(10px); }
      to { opacity: 1; transform: translateY(0); }
    }

    @keyframes gold-shimmer {
      0% { background-position: -200% center; }
      100% { background-position: 200% center; }
    }

    .animate-pulse-slow { animation: pulse-slow 6s ease-in-out infinite; }
    .animate-pulse-slower { animation: pulse-slower 8s ease-in-out infinite; }
    .animate-scroll-bounce { animation: scroll-bounce 2s ease-in-out infinite; }
    .animate-mesh { animation: mesh-shift 12s ease-in-out infinite; }
    .animate-mesh-reverse { animation: mesh-shift 15s ease-in-out infinite reverse; }
    .animate-count-up { animation: count-up 0.6s ease-out forwards; }
    .animate-gold-shimmer { background-size: 200% auto; animation: gold-shimmer 3s linear infinite; }

    .stat-item { opacity: 0; }
    .stat-item.is-visible { animation: count-up 0.6s ease-out forwards; }
    .stat-item:nth-child(2) { animation-delay: 0.15s; }
    .stat-item:nth-child(3) { animation-delay: 0.3s; }

    .video-play-btn {
      transition: all 0.3s cubic-bezier(0.2, 0.7, 0.2, 1);
    }
    .video-play-btn:hover {
      transform: scale(1.1);
      box-shadow: 0 0 30px rgba(220, 200, 160, 0.4);
    }

    .sticky-cta {
      transition: transform 0.3s ease;
    }
    .sticky-cta.hidden-cta {
      transform: translateY(100%);
    }
  </style>
</head>
<body class="font-sans antialiased flex flex-col min-h-screen relative">
  <div id="routeProgress" class="route-progress" aria-hidden="true"></div>
  <div id="toastWrap" class="toast-wrap" aria-live="polite" aria-atomic="true"></div>
  <div class="ambient-stage" aria-hidden="true">
    <div class="ambient-orb ambient-orb--one"></div>
    <div class="ambient-orb ambient-orb--two"></div>
    <div class="ambient-orb ambient-orb--three"></div>
  </div>


  <!-- Header -->
  <header class="glass-nav sticky top-0 z-50 transition-all duration-300 shadow-sm">
    <div class="container mx-auto px-3 sm:px-4 py-3 flex items-center justify-between">
      <!-- Logo & Branding -->
      <a href="/" class="flex items-center gap-2 sm:gap-2 group shrink-0">
        <img
          src="{{ $logoPath }}"
          alt="{{ $siteName }} Logo"
          class="h-9 w-auto sm:h-10 transition-transform duration-300 group-hover:scale-105"
        />
          <div class="hidden sm:block">
            <div class="font-serif text-sm sm:text-base font-bold text-brand-blue dark:text-brand-gold leading-tight">THE LAST DAYS COVENANTS</div>
          </div>
      </a>


      <!-- Desktop Navigation -->
      <nav class="hidden lg:flex items-center gap-0.5">
        <a href="{{ $homeUrl }}" class="px-2.5 py-2 text-slate-700 dark:text-slate-300 hover:text-brand-blue dark:hover:text-brand-gold font-medium transition-all duration-200 border-b-2 border-transparent hover:border-brand-blue inline-flex items-center gap-1.5 whitespace-nowrap text-sm"><span class="w-5 h-5 rounded-full bg-brand-blue inline-flex items-center justify-center shrink-0"><i data-lucide="home" class="w-3 h-3 text-white"></i></span> {{ __('messages.nav.home') }}</a>
        <a href="{{ $resourcesLink }}" class="px-2.5 py-2 text-slate-700 dark:text-slate-300 hover:text-brand-blue dark:hover:text-brand-gold font-medium transition-all duration-200 border-b-2 border-transparent hover:border-brand-blue inline-flex items-center gap-1.5 whitespace-nowrap text-sm"><span class="w-5 h-5 rounded-full bg-brand-blue inline-flex items-center justify-center shrink-0"><i data-lucide="book-open" class="w-3 h-3 text-white"></i></span> {{ __('messages.nav.resources') }}</a>
        <a href="{{ $sermonsLink }}" class="px-2.5 py-2 text-slate-700 dark:text-slate-300 hover:text-brand-blue dark:hover:text-brand-gold font-medium transition-all duration-200 border-b-2 border-transparent hover:border-brand-blue inline-flex items-center gap-1.5 whitespace-nowrap text-sm"><span class="w-5 h-5 rounded-full bg-brand-blue inline-flex items-center justify-center shrink-0"><i data-lucide="play-circle" class="w-3 h-3 text-white"></i></span> {{ __('messages.nav.sermons') }}</a>
        <a href="{{ route('devotionals.index') }}" class="px-2.5 py-2 text-slate-700 dark:text-slate-300 hover:text-brand-blue dark:hover:text-brand-gold font-medium transition-all duration-200 border-b-2 border-transparent hover:border-brand-blue inline-flex items-center gap-1.5 whitespace-nowrap text-sm"><span class="w-5 h-5 rounded-full bg-brand-blue inline-flex items-center justify-center shrink-0"><i data-lucide="sunrise" class="w-3 h-3 text-white"></i></span> {{ __('messages.nav.devotionals') }}</a>
        <a href="{{ route('about') }}" class="px-2.5 py-2 text-slate-700 dark:text-slate-300 hover:text-brand-blue dark:hover:text-brand-gold font-medium transition-all duration-200 border-b-2 border-transparent hover:border-brand-blue inline-flex items-center gap-1.5 whitespace-nowrap text-sm"><span class="w-5 h-5 rounded-full bg-brand-blue inline-flex items-center justify-center shrink-0"><i data-lucide="info" class="w-3 h-3 text-white"></i></span> {{ __('messages.nav.about') }}</a>
        <a href="{{ route('contact') }}" class="px-2.5 py-2 text-slate-700 dark:text-slate-300 hover:text-brand-blue dark:hover:text-brand-gold font-medium transition-all duration-200 border-b-2 border-transparent hover:border-brand-blue inline-flex items-center gap-1.5 whitespace-nowrap text-sm"><span class="w-5 h-5 rounded-full bg-brand-blue inline-flex items-center justify-center shrink-0"><i data-lucide="mail" class="w-3 h-3 text-white"></i></span> {{ __('messages.nav.contact') }}</a>
      </nav>


      <!-- CTA, Dark Mode Toggle, Language Switcher & Mobile Menu Toggle -->
      <div class="flex items-center gap-1.5 sm:gap-2">
        <button id="dark-mode-toggle" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 dark:bg-slate-700 dark:hover:bg-slate-600 inline-flex items-center justify-center shrink-0 transition-colors duration-200" aria-label="Toggle dark mode"></button>
        <div class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full border border-slate-200 bg-white dark:bg-slate-800 dark:border-slate-600">
          <span class="w-5 h-5 rounded-full bg-brand-blue inline-flex items-center justify-center shrink-0">
            <i data-lucide="globe" class="w-3 h-3 text-white"></i>
          </span>
          <select
            onchange="window.location.href=this.value"
            class="text-xs sm:text-sm text-slate-700 dark:text-slate-200 bg-transparent border-none outline-none focus:outline-none p-0 m-0 cursor-pointer"
            aria-label="Language"
          >
            <option value="{{ route('locale.switch', 'rw') }}" {{ $currentLocale === 'rw' ? 'selected' : '' }}>Kinyarwanda</option>
            <option value="{{ route('locale.switch', 'en') }}" {{ $currentLocale === 'en' ? 'selected' : '' }}>English</option>
            <option value="{{ route('locale.switch', 'fr') }}" {{ $currentLocale === 'fr' ? 'selected' : '' }}>Français</option>
          </select>
        </div>
        <button
          id="mobile-menu-toggle"
          class="lg:hidden inline-flex flex-col items-center justify-center w-10 h-10 rounded-lg text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-brand-blue"
          aria-label="Toggle navigation menu"
        >
          <!-- Hamburger Menu Icon (3 lines) -->
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
          </svg>
        </button>
      </div>
    </div>


    <!-- Mobile Navigation Menu (Hidden by default) -->
    <nav id="mobile-menu" class="hidden lg:hidden bg-surface dark:bg-slate-800 border-t border-slate-200 dark:border-slate-700">
      <div class="container mx-auto px-4 sm:px-6 py-3 space-y-2">
        <a href="{{ $homeUrl }}" class="block px-4 py-2 text-slate-700 dark:text-slate-300 hover:text-brand-blue dark:hover:text-brand-gold hover:bg-slate-50 dark:hover:bg-slate-700 rounded-lg font-medium transition-colors duration-200 inline-flex items-center gap-2"><span class="w-6 h-6 rounded-full bg-brand-blue inline-flex items-center justify-center shrink-0"><i data-lucide="home" class="w-3.5 h-3.5 text-white"></i></span> {{ __('messages.nav.home') }}</a>
        <a href="{{ $resourcesLink }}" class="block px-4 py-2 text-slate-700 dark:text-slate-300 hover:text-brand-blue dark:hover:text-brand-gold hover:bg-slate-50 dark:hover:bg-slate-700 rounded-lg font-medium transition-colors duration-200 inline-flex items-center gap-2"><span class="w-6 h-6 rounded-full bg-brand-blue inline-flex items-center justify-center shrink-0"><i data-lucide="book-open" class="w-3.5 h-3.5 text-white"></i></span> {{ __('messages.nav.resources') }}</a>
        <a href="{{ $sermonsLink }}" class="block px-4 py-2 text-slate-700 dark:text-slate-300 hover:text-brand-blue dark:hover:text-brand-gold hover:bg-slate-50 dark:hover:bg-slate-700 rounded-lg font-medium transition-colors duration-200 inline-flex items-center gap-2"><span class="w-6 h-6 rounded-full bg-brand-blue inline-flex items-center justify-center shrink-0"><i data-lucide="play-circle" class="w-3.5 h-3.5 text-white"></i></span> {{ __('messages.nav.sermons') }}</a>
        <a href="{{ route('devotionals.index') }}" class="block px-4 py-2 text-slate-700 dark:text-slate-300 hover:text-brand-blue dark:hover:text-brand-gold hover:bg-slate-50 dark:hover:bg-slate-700 rounded-lg font-medium transition-colors duration-200 inline-flex items-center gap-2"><span class="w-6 h-6 rounded-full bg-brand-blue inline-flex items-center justify-center shrink-0"><i data-lucide="sunrise" class="w-3.5 h-3.5 text-white"></i></span> {{ __('messages.nav.devotionals') }}</a>
        <a href="{{ route('about') }}" class="block px-4 py-2 text-slate-700 dark:text-slate-300 hover:text-brand-blue dark:hover:text-brand-gold hover:bg-slate-50 dark:hover:bg-slate-700 rounded-lg font-medium transition-colors duration-200 inline-flex items-center gap-2"><span class="w-6 h-6 rounded-full bg-brand-blue inline-flex items-center justify-center shrink-0"><i data-lucide="info" class="w-3.5 h-3.5 text-white"></i></span> {{ __('messages.nav.about') }}</a>
        <a href="{{ route('contact') }}" class="block px-4 py-2 text-slate-700 dark:text-slate-300 hover:text-brand-blue dark:hover:text-brand-gold hover:bg-slate-50 dark:hover:bg-slate-700 rounded-lg font-medium transition-colors duration-200 inline-flex items-center gap-2"><span class="w-6 h-6 rounded-full bg-brand-blue inline-flex items-center justify-center shrink-0"><i data-lucide="mail" class="w-3.5 h-3.5 text-white"></i></span> {{ __('messages.nav.contact') }}</a>
        <div class="mt-3 pt-3 border-t border-slate-200 dark:border-slate-700">
          <label class="block text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400 mb-2">{{ __('messages.nav.language') }}</label>
          <div class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700">
            <span class="w-5 h-5 rounded-full bg-brand-blue inline-flex items-center justify-center shrink-0">
              <i data-lucide="globe" class="w-3 h-3 text-white"></i>
            </span>
            <select
              onchange="window.location.href=this.value"
              class="w-full text-sm text-slate-700 dark:text-slate-200 bg-transparent border-none outline-none focus:outline-none p-0 m-0 cursor-pointer"
              aria-label="Language"
            >
              <option value="{{ route('locale.switch', 'rw') }}" {{ $currentLocale === 'rw' ? 'selected' : '' }}>Kinyarwanda</option>
              <option value="{{ route('locale.switch', 'en') }}" {{ $currentLocale === 'en' ? 'selected' : '' }}>English</option>
              <option value="{{ route('locale.switch', 'fr') }}" {{ $currentLocale === 'fr' ? 'selected' : '' }}>Français</option>
            </select>
          </div>
        </div>
      </div>
    </nav>

    @if (!empty($activeBanners) && count($activeBanners) > 0)
    <div id="headerBanner" class="header-banner">
      <div class="header-banner-inner">
        @foreach ($activeBanners as $index => $banner)
          @php
            $bgColor = $banner->background_color ?: '#00283c';
            $textColor = $banner->text_color ?: '#ffffff';
            $translatedContent = $banner->translated('content');
          @endphp
          <div class="header-banner-slide @if ($banner->link) has-link @endif {{ $index === 0 ? 'active' : '' }}"
               data-banner-id="{{ $banner->id }}"
               data-banner-key="banner_dismiss_{{ $banner->id }}"
               style="background-color: {{ $bgColor }}; color: {{ $textColor }}; {{ $index !== 0 ? 'display: none;' : '' }}">
            @if ($banner->link)
              <a href="{{ $banner->link }}" class="header-banner-link" style="color: {{ $textColor }};" target="_blank" rel="noopener">{{ $translatedContent }}</a>
            @else
              <span class="header-banner-text">{{ $translatedContent }}</span>
            @endif
            <button class="header-banner-close" aria-label="Dismiss announcement">&times;</button>
          </div>
        @endforeach
      </div>
    </div>
    @endif
  </header>


  <!-- Mobile Menu Toggle Script -->
  <script>
    const toggle = document.getElementById('mobile-menu-toggle');
    const menu = document.getElementById('mobile-menu');
   
    toggle.addEventListener('click', () => {
      menu.classList.toggle('hidden');
    });


    // Close menu when a link is clicked
    menu.querySelectorAll('a').forEach(link => {
      link.addEventListener('click', () => {
        menu.classList.add('hidden');
      });
    });
  </script>


  <!-- Main Content -->
    @yield('contents')
  <!-- Footer -->
  <footer class="bg-blue-950 text-slate-300 py-12 border-t border-blue-950">
    <div class="container mx-auto px-4 sm:px-6">
      <div class="grid grid-cols-1 md:grid-cols-4 gap-10 mb-10">
        <!-- Brand Column -->
        <div class="col-span-1 md:col-span-1">
          <div class="flex items-center gap-2 mb-4 text-white">
            @if($siteName)
            <img src="{{$faviconPath}}" alt="">
            @else
            <i data-lucide="flame" class="w-6 h-6 text-brand-gold"></i>
            @endif
            <h4 class="text-white font-serif font-semibold mb-4">{{ $siteName }}</h4>
          </div>
          <p class="text-sm leading-relaxed text-blue-100 opacity-80">
            {{ $footerText }}
          </p>
        </div>


        <!-- Links 1 -->
        <div>
          <h4 class="text-white font-serif font-semibold mb-4">{{ __('messages.footer.ministry') }}</h4>
          <ul class="space-y-2 text-sm">
            <li><a href="{{ route('about') }}" class="hover:text-brand-gold transition-colors">{{ __('messages.footer.about_us') }}</a></li>
            <li><a href="{{ route('about') }}#faith" class="hover:text-brand-gold transition-colors">{{ __('messages.footer.faith_statement') }}</a></li>
            <li><a href="{{ route('about') }}#leadership" class="hover:text-brand-gold transition-colors">{{ __('messages.footer.leadership') }}</a></li>
            <li><a href="{{ route('contact') }}" class="hover:text-brand-gold transition-colors">{{ __('messages.footer.contact') }}</a></li>
          </ul>
        </div>


        <!-- Links 2 -->
        <div>
          <h4 class="text-white font-serif font-semibold mb-4">{{ __('messages.footer.resources') }}</h4>
          <ul class="space-y-2 text-sm">
            <li><a href="{{ route('videos.index') }}" class="hover:text-brand-gold transition-colors">{{ __('messages.footer.latest_sermons') }}</a></li>
            <li><a href="{{ route('audios.index') }}" class="hover:text-brand-gold transition-colors">{{ __('messages.footer.audio_teachings') }}</a></li>
            <li><a href="{{ route('books.index') }}" class="hover:text-brand-gold transition-colors">{{ __('messages.footer.ebooks') }}</a></li>
            <li><a href="{{ route('devotionals.index') }}" class="hover:text-brand-gold transition-colors">{{ __('messages.footer.devotionals') }}</a></li>
            <li><a href="{{ route('resources') }}" class="hover:text-brand-gold transition-colors">{{ __('messages.footer.devotionals') }}</a></li>
          </ul>
        </div>


        <!-- Contact / Social -->
        <div>
          <h4 class="text-white font-serif font-semibold mb-4">{{ __('messages.footer.connect') }}</h4>
          <ul class="space-y-3 text-sm">
            <li class="flex items-center gap-2">
              <i data-lucide="mail" class="w-4 h-4 text-brand-gold"></i>
              <span>{{ $contactEmail }}</span>
            </li>
            <li class="flex items-center gap-2">
              <i data-lucide="map-pin" class="w-4 h-4 text-brand-gold"></i>
              <span>{{ $contactAddress }}</span>
            </li>
            <li class="flex flex-wrap gap-3 mt-2">
              <a href="{{ $youtubeUrl }}" target="_blank" rel="noopener" class="w-9 h-9 rounded-full bg-red-600/90 text-white flex items-center justify-center hover:bg-red-700 transition-colors"><svg viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg></a>
              <a href="{{ $facebookUrl }}" target="_blank" rel="noopener" class="w-9 h-9 rounded-full bg-blue-600/90 text-white flex items-center justify-center hover:bg-blue-700 transition-colors"><svg viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg></a>
              <a href="{{ $instagramUrl }}" target="_blank" rel="noopener" class="w-9 h-9 rounded-full bg-pink-600/90 text-white flex items-center justify-center hover:bg-pink-700 transition-colors"><svg viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg></a>
              <a href="{{ $twitterUrl }}" target="_blank" rel="noopener" class="w-9 h-9 rounded-full bg-slate-900/90 text-white flex items-center justify-center hover:bg-slate-900 transition-colors"><svg viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg></a>
              <a href="https://wa.me/+25{{$whatsappUrl}}" target="_blank" rel="noopener" class="w-9 h-9 rounded-full bg-emerald-500/90 text-white flex items-center justify-center hover:bg-emerald-600 transition-colors"><i data-lucide="message-circle" class="w-4 h-4"></i></a>
            </li>
          </ul>
        </div>
      </div>


      <div class="border-t border-blue-800 pt-8 flex flex-col md:flex-row justify-between items-center text-xs text-blue-200 opacity-60">
        <p>&copy; {{ date('Y') }} {{ $siteName }}. {{ __('messages.footer.rights') }}</p>
        <p class="mt-2 md:mt-0">{{ __('messages.footer.made_with_care') }}</p>
      </div>
    </div>
  </footer>
  <div class="fixed inset-0 z-[2] pointer-events-none overflow-hidden" aria-hidden="true">
    <video autoplay muted loop playsinline class="w-full h-full object-cover opacity-[0.2] mix-blend-soft-light">
      <source src="/headerbackground.mp4" type="video/mp4">
    </video>
  </div>
  <!-- Scroll Animation Trigger Script -->
  <script>
    (() => {
      const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
      const revealElements = [];

      const pushReveal = (element, delayMs = 0, direction = 'up') => {
        if (!element || element.dataset.revealPrepared === '1') return;
        element.dataset.revealPrepared = '1';
        element.setAttribute('data-reveal', direction);
        element.style.setProperty('--reveal-delay', `${delayMs}ms`);
        revealElements.push(element);
      };

      document.querySelectorAll('.scroll-animate, [data-reveal]').forEach((element, index) => {
        pushReveal(element, Math.min((index % 6) * 70, 350));
      });

      document.querySelectorAll('main section').forEach((section) => {
        section.querySelectorAll('h1, h2').forEach((element, index) => {
          pushReveal(element, index * 60, index % 2 === 0 ? 'left' : 'right');
        });
        section.querySelectorAll('.bg-surface-card.rounded-2xl, .bg-surface-card.rounded-xl, article.rounded-2xl').forEach((element, index) => {
          pushReveal(element, Math.min((index % 6) * 80, 400), 'up');
          element.classList.add('interactive-card');
        });
      });

      if (!reducedMotion) {
        const revealObserver = new IntersectionObserver((entries) => {
          entries.forEach((entry) => {
            if (!entry.isIntersecting) return;
            entry.target.classList.add('is-visible');
            revealObserver.unobserve(entry.target);
          });
        }, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });

        revealElements.forEach((element) => revealObserver.observe(element));
      } else {
        revealElements.forEach((element) => element.classList.add('is-visible'));
      }

      // Ripple micro-interaction for CTA/button surfaces.
      document.querySelectorAll('.ripple-container, button, a[class*="rounded"]').forEach((element) => {
        element.addEventListener('click', (event) => {
          if (reducedMotion) return;
          const rect = element.getBoundingClientRect();
          const ripple = document.createElement('span');
          const size = Math.max(rect.width, rect.height);
          ripple.className = 'ripple';
          ripple.style.width = `${size}px`;
          ripple.style.height = `${size}px`;
          ripple.style.left = `${event.clientX - rect.left - size / 2}px`;
          ripple.style.top = `${event.clientY - rect.top - size / 2}px`;
          element.classList.add('ripple-container');
          element.appendChild(ripple);
          setTimeout(() => ripple.remove(), 620);
        }, { passive: true });
      });

      // Lucide icon initialization
      if (typeof lucide !== 'undefined') {
        lucide.createIcons();
      }
    })();
  </script>
  @if ($tawkEnabled && $tawkProperty && $tawkWidget)
    <script>
      var Tawk_API = Tawk_API || {}, Tawk_LoadStart = new Date();
      (function(){
        var s1 = document.createElement("script"), s0 = document.getElementsByTagName("script")[0];
        s1.async = true;
        s1.src = "https://embed.tawk.to/{{ $tawkProperty }}/{{ $tawkWidget }}";
        s1.charset = "UTF-8";
        s1.setAttribute("crossorigin", "*");
        s0.parentNode.insertBefore(s1, s0);
      })();
  </script>
  @endif

  <!-- Dark Mode Toggle Script -->
  <script>
    (() => {
      const btn = document.getElementById('dark-mode-toggle');
      if (!btn) return;

      const moonSvg = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4 text-slate-600"><path d="M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z"/></svg>';
      const sunSvg = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4 text-amber-300"><circle cx="12" cy="12" r="4"/><path d="M12 2v2"/><path d="M12 20v2"/><path d="m4.93 4.93 1.41 1.41"/><path d="m17.66 17.66 1.41 1.41"/><path d="M2 12h2"/><path d="M20 12h2"/><path d="m6.34 17.66-1.41 1.41"/><path d="m19.07 4.93-1.41 1.41"/></svg>';

      const applyDark = (isDark) => {
        document.documentElement.classList.toggle('dark', isDark);
        document.body.style.backgroundColor = isDark ? '#0f172a' : '';
        document.body.style.color = isDark ? '#e2e8f0' : '';
        btn.innerHTML = isDark ? sunSvg : moonSvg;
      };

      applyDark(document.documentElement.classList.contains('dark'));

      btn.addEventListener('click', () => {
        const isDark = !document.documentElement.classList.contains('dark');
        localStorage.setItem('dark', isDark ? 'true' : 'false');
        applyDark(isDark);
      });
    })();
  </script>

  <script>
    (() => {
      const endpoint = @json(route('content.audience.track'));
      const routeName = @json(optional(request()->route())->getName());
      const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
      const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
      const sessionIdKey = 'bgm_audience_session_id';
      const visitorIdKey = 'bgm_audience_visitor_id';
      const sessionStartedKey = 'bgm_audience_session_started';
      const scrollTrackedKey = 'bgm_audience_scroll_steps';
      let engagedSeconds = 0;
      let sessionEnded = false;

      const makeId = (prefix) => `${prefix}_${Math.random().toString(36).slice(2)}_${Date.now()}`;
      const getOrCreateStorage = (storage, key, prefix) => {
        let value = storage.getItem(key);
        if (!value) {
          value = makeId(prefix);
          storage.setItem(key, value);
        }
        return value;
      };

      const visitorId = getOrCreateStorage(localStorage, visitorIdKey, 'v');
      const sessionId = getOrCreateStorage(sessionStorage, sessionIdKey, 's');
      const url = new URL(window.location.href);
      const metrics = () => {
        const width = window.innerWidth || 0;
        let deviceType = 'unknown';
        if (width < 768) deviceType = 'mobile';
        else if (width < 1024) deviceType = 'tablet';
        else deviceType = 'desktop';

        return {
          visitor_id: visitorId,
          session_id: sessionId,
          route_name: routeName,
          page_url: window.location.href,
          referrer: document.referrer || null,
          utm_source: url.searchParams.get('utm_source'),
          utm_medium: url.searchParams.get('utm_medium'),
          utm_campaign: url.searchParams.get('utm_campaign'),
          utm_term: url.searchParams.get('utm_term'),
          utm_content: url.searchParams.get('utm_content'),
          screen_width: window.screen?.width || null,
          screen_height: window.screen?.height || null,
          timezone: Intl.DateTimeFormat().resolvedOptions().timeZone,
          language: navigator.language || null,
          platform: navigator.platform || null,
          device_type: deviceType,
        };
      };

      const send = (eventType, extra = {}, beacon = false) => {
        const payload = { event_type: eventType, ...metrics(), ...extra };
        if (beacon && navigator.sendBeacon) {
          const form = new FormData();
          form.append('_token', csrf);
          Object.entries(payload).forEach(([key, value]) => {
            if (value !== null && typeof value !== 'undefined') {
              form.append(key, String(value));
            }
          });
          navigator.sendBeacon(endpoint, form);
          return;
        }

        fetch(endpoint, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrf,
          },
          body: JSON.stringify(payload),
          keepalive: true,
        }).catch(() => {});
      };

      const markSessionStart = () => {
        if (sessionStorage.getItem(sessionStartedKey) === '1') return;
        send('session_start');
        sessionStorage.setItem(sessionStartedKey, '1');
      };

      const trackPageView = () => send('page_view');

      const trackScrollDepth = () => {
        const doc = document.documentElement;
        const scrollable = Math.max(doc.scrollHeight - window.innerHeight, 1);
        const progress = Math.round((window.scrollY / scrollable) * 100);
        const steps = [25, 50, 75, 100];
        const seen = new Set((sessionStorage.getItem(scrollTrackedKey) || '').split(',').filter(Boolean));

        steps.forEach((step) => {
          if (progress < step || seen.has(String(step))) return;
          send('scroll_depth', { scroll_depth: step });
          seen.add(String(step));
        });

        sessionStorage.setItem(scrollTrackedKey, Array.from(seen).join(','));
      };

      const endSession = () => {
        if (sessionEnded) return;
        sessionEnded = true;
        send('session_end', { engaged_seconds: engagedSeconds }, true);
      };

      if (!reducedMotion) {
        window.addEventListener('scroll', trackScrollDepth, { passive: true });
      }

      document.addEventListener('click', (event) => {
        const target = event.target.closest('a, button');
        if (!target) return;
        const className = String(target.className || '');
        const looksLikeCta = target.hasAttribute('data-cta') || /bg-|btn|rounded-full|rounded-lg/.test(className);
        if (!looksLikeCta) return;
        const label = (target.getAttribute('data-cta') || target.textContent || '').trim().slice(0, 180);
        const href = target.getAttribute('href');
        send('cta_click', {
          cta_label: label || 'unknown',
          cta_target: href || window.location.href,
        });
      }, { passive: true });

      setInterval(() => {
        if (document.visibilityState === 'visible') {
          engagedSeconds += 5;
        }
      }, 5000);

      document.addEventListener('visibilitychange', () => {
        if (document.visibilityState === 'hidden') {
          endSession();
        }
      });

      window.addEventListener('beforeunload', endSession);
      window.addEventListener('pagehide', endSession);

      markSessionStart();
      trackPageView();
    })();
  </script>
  <script>
    (() => {
      const progress = document.getElementById('routeProgress');
      const toastWrap = document.getElementById('toastWrap');

      const startProgress = () => {
        if (!progress) return;
        progress.style.width = '18%';
        requestAnimationFrame(() => {
          progress.style.width = '78%';
        });
      };

      const finishProgress = () => {
        if (!progress) return;
        progress.style.width = '100%';
        setTimeout(() => { progress.style.width = '0%'; }, 280);
      };

      const showToast = (message, type = 'info', timeout = 3800) => {
        if (!toastWrap || !message) return;
        const node = document.createElement('div');
        node.className = `toast-item ${type}`;
        node.textContent = String(message);
        toastWrap.appendChild(node);

        setTimeout(() => {
          node.style.opacity = '0';
          node.style.transform = 'translateY(-6px)';
          setTimeout(() => node.remove(), 220);
        }, timeout);
      };

      window.appToast = showToast;

      document.querySelectorAll('a[href]').forEach((link) => {
        link.addEventListener('click', (event) => {
          if (event.defaultPrevented) return;
          const href = link.getAttribute('href') || '';
          const target = link.getAttribute('target');
          const isHash = href.startsWith('#');
          const isJs = href.startsWith('javascript:');
          const isExternal = /^https?:\/\//i.test(href) && !href.startsWith(window.location.origin);

          if (target === '_blank' || isHash || isJs || isExternal) return;
          startProgress();
        }, { passive: true });
      });

      const tapCards = Array.from(document.querySelectorAll('[data-tap-reveal]'));
      const clearActive = () => tapCards.forEach((card) => card.classList.remove('is-active'));

      tapCards.forEach((card) => {
        card.addEventListener('touchstart', (event) => {
          const isActive = card.classList.contains('is-active');
          clearActive();
          if (!isActive) {
            card.classList.add('is-active');
            event.stopPropagation();
          }
        }, { passive: true });
      });

      document.addEventListener('touchstart', clearActive, { passive: true });

      const mediaSelector = 'img, iframe, audio, video';
      document.querySelectorAll(mediaSelector).forEach((media) => {
        const markDone = () => media.classList.remove('media-skeleton');
        media.classList.add('media-skeleton');

        if (media.tagName === 'IMG') {
          if (media.complete) {
            markDone();
          } else {
            media.addEventListener('load', markDone, { once: true });
            media.addEventListener('error', markDone, { once: true });
          }
          return;
        }

        media.addEventListener('loadeddata', markDone, { once: true });
        media.addEventListener('canplay', markDone, { once: true });
        media.addEventListener('load', markDone, { once: true });
        media.addEventListener('error', markDone, { once: true });
      });

      window.addEventListener('load', finishProgress);

      @if (session('status'))
        showToast(@json(session('status')), 'success');
      @endif

      @if (session('error'))
        showToast(@json(session('error')), 'error');
      @endif

      @if ($errors->any())
        showToast(@json($errors->first()), 'error');
      @endif
    })();
  </script>
  <script>
    (() => {
      const getHeaderOffset = () => {
        const header = document.querySelector('header.glass-nav');
        return (header?.offsetHeight || 0) + 12;
      };

      const scrollToHash = (hash, smooth = true) => {
        if (!hash || hash === '#') return;
        const id = hash.replace('#', '');
        const target = document.getElementById(id);
        if (!target) return;

        const top = target.getBoundingClientRect().top + window.pageYOffset - getHeaderOffset();
        window.scrollTo({ top: Math.max(top, 0), behavior: smooth ? 'smooth' : 'auto' });
      };

      window.addEventListener('load', () => {
        if (window.location.hash) {
          scrollToHash(window.location.hash, false);
        }
      });

      window.addEventListener('hashchange', () => {
        scrollToHash(window.location.hash, true);
      });
    })();
  </script>

</body>
</html>








