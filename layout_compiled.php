<!DOCTYPE html>
<html lang="<?php echo e(app()->getLocale()); ?>" dir="<?php echo e(app()->getLocale() === 'ar' ? 'rtl' : 'ltr'); ?>" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    
    <title><?php echo $__env->yieldContent('seo_title', 'FERRO — Forged from Iron, Polished by Luxury'); ?></title>
    <meta name="description" content="<?php echo $__env->yieldContent('seo_description', 'Premium natural grooming essentials engineered for the high-performance man. Nature-powered. Luxury-refined. Built for resilience.'); ?>">
    <meta name="keywords"    content="<?php echo $__env->yieldContent('seo_keywords', 'mens grooming, luxury skincare, athlete skincare, natural grooming, FERRO'); ?>">
    <link  rel="canonical"   href="<?php echo $__env->yieldContent('canonical', url()->current()); ?>">

    
    <link rel="alternate" hreflang="en" href="<?php echo e(url()->current()); ?>">
    <link rel="alternate" hreflang="ar" href="<?php echo e(url()->current()); ?>">
    <link rel="alternate" hreflang="x-default" href="<?php echo e(url('/')); ?>">

    
    <meta property="og:type"        content="<?php echo $__env->yieldContent('og_type', 'website'); ?>">
    <meta property="og:title"       content="<?php echo $__env->yieldContent('og_title', 'FERRO — Forged from Iron, Polished by Luxury'); ?>">
    <meta property="og:description" content="<?php echo $__env->yieldContent('og_description', 'Premium natural grooming essentials engineered for the high-performance man.'); ?>">
    <meta property="og:image"       content="<?php echo $__env->yieldContent('og_image', asset('images/ferro-og-default.jpg')); ?>">
    <meta property="og:url"         content="<?php echo e(url()->current()); ?>">
    <meta property="og:site_name"   content="FERRO">
    <meta property="og:locale"      content="<?php echo e(app()->getLocale() === 'ar' ? 'ar_AE' : 'en_US'); ?>">
    <meta property="og:locale:alternate" content="<?php echo e(app()->getLocale() === 'ar' ? 'en_US' : 'ar_AE'); ?>">

    
    <meta name="twitter:card"        content="summary_large_image">
    <meta name="twitter:title"       content="<?php echo $__env->yieldContent('og_title', 'FERRO'); ?>">
    <meta name="twitter:description" content="<?php echo $__env->yieldContent('og_description', 'Premium mens grooming.'); ?>">
    <meta name="twitter:image"       content="<?php echo $__env->yieldContent('og_image', asset('images/ferro-og-default.jpg')); ?>">
    <meta name="twitter:site"        content="@ferrogrooming">

    
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "Organization",
        "name": "FERRO",
        "url": "<?php echo e(url('/')); ?>",
        "logo": "<?php echo e(asset('images/ferro-logo.svg')); ?>",
        "description": "Premium nature-powered grooming essentials engineered for the high-performance man.",
        "sameAs": [
            "https://instagram.com/ferrogrooming",
            "https://tiktok.com/@ferrogrooming"
        ],
        "contactPoint": {
            "@type": "ContactPoint",
            "contactType": "customer service",
            "email": "support@ferro.com"
        }
    }
    </script>

    
    <?php echo $__env->yieldContent('schema_org'); ?>

    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preload"    as="image" href="<?php echo e(asset('images/hero-bg.webp')); ?>">

    
    <link rel="icon"             type="image/svg+xml" href="<?php echo e(asset('favicon.svg')); ?>">
    <link rel="apple-touch-icon" href="<?php echo e(asset('images/apple-touch-icon.png')); ?>">
    <meta name="theme-color" content="#0A0A0A">

    
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>

    
    <?php echo $__env->yieldPushContent('head'); ?>
</head>
<body class="bg-ferro-black text-ferro-off-white antialiased">

    
    <?php echo $__env->make('partials.nav', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    
    <main id="main-content">
        <?php echo $__env->yieldContent('content'); ?>
    </main>

  

</body>
</html>
