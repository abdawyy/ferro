<?php $isAr = app()->getLocale() === 'ar'; ?>


<?php
    $seoTitle       = $isAr ? 'فيرو — مصنوع من الحديد، مصقول بالرفاهية | عناية الرجل الفاخرة' : 'FERRO — Forged from Iron, Polished by Luxury | Premium Men\'s Grooming';
    $seoDescription = $isAr ? 'منتجات عناية فاخرة للرجال والرياضيين النخبة. طبيعية. قوية. لا تهادن.' : 'Premium nature-powered grooming essentials engineered for the high-performance man and elite athlete. Natural. Powerful. Uncompromising.';
?>
<?php $__env->startSection('seo_title', $seoTitle); ?>
<?php $__env->startSection('seo_description', $seoDescription); ?>
<?php $__env->startSection('og_title', 'FERRO — Forged from Iron, Polished by Luxury'); ?>
<?php $__env->startSection('og_description', 'Premium natural grooming engineered for the elite athlete.'); ?>
<?php $__env->startSection('og_type', 'website'); ?>

<?php $__env->startSection('schema_org'); ?>
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "WebSite",
    "name": "FERRO",
    "url": "<?php echo e(url('/')); ?>",
    "potentialAction": {
        "@type": "SearchAction",
        "target": "<?php echo e(url('/shop')); ?>?q={search_term_string}",
        "query-input": "required name=search_term_string"
    }
}
</script>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>


<section
    class="relative min-h-screen flex items-center overflow-hidden"
    aria-labelledby="hero-headline"
>
    
    <div class="absolute inset-0 z-0" id="hero-bg">
        <img
            src="<?php echo e(asset('images/hero-bg.webp')); ?>"
            alt=""
            class="w-full h-full object-cover object-center scale-110"
            aria-hidden="true"
            loading="eager"
            fetchpriority="high"
        >
        
        <div class="absolute inset-0 bg-gradient-to-b from-ferro-black/30 via-ferro-black/60 to-ferro-black"></div>
        
        <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_30%_40%,rgba(232,80,10,0.12)_0%,transparent_60%)]"></div>
    </div>

    
    <div class="absolute inset-0 z-0 opacity-[0.03]"
         style="background-image: linear-gradient(rgba(245,242,238,1) 1px, transparent 1px), linear-gradient(90deg, rgba(245,242,238,1) 1px, transparent 1px); background-size: 60px 60px;"
         aria-hidden="true"></div>

    
    <div class="container-ferro relative z-10 pt-[72px]">
        <div class="max-w-3xl <?php echo e($isAr ? 'text-right' : 'text-left'); ?>">

            
            <div class="flex items-center gap-3 mb-8 <?php echo e($isAr ? 'justify-end flex-row-reverse' : ''); ?>">
                <div class="w-8 h-px bg-ferro-orange"></div>
                <span class="eyebrow mb-0">
                    <?php echo e($isAr ? 'الجيل القادم من العناية الفاخرة' : 'Next-Generation Luxury Grooming'); ?>

                </span>
            </div>

            
            <h1
                id="hero-headline"
                class="font-display text-display-2xl text-ferro-white mb-6 leading-none animate-fade-up fill-both"
            >
                <?php if($isAr): ?>
                    <span class="block">مصنوع</span>
                    <span class="block text-gradient-orange">من الحديد</span>
                    <span class="block">مصقول بالرفاهية</span>
                <?php else: ?>
                    <span class="block">Forged</span>
                    <span class="block text-gradient-orange">from Iron.</span>
                    <span class="block">Polished by Luxury.</span>
                <?php endif; ?>
            </h1>

            
            <p class="text-ferro-silver text-body-lg max-w-xl mb-10 animate-fade-up fill-both delay-200">
                <?php echo e($isAr
                    ? 'في عالم يملؤه الغياب، جاء فيرو ليسد الفراغ. عناية مدعومة بالطبيعة، مصممة للرجل الذي لا يتوقف.'
                    : 'In a world of absence, FERRO was born to fill the void. Nature-powered. Built for the man who never stops.'); ?>

            </p>

            
            <div class="flex flex-wrap items-center gap-4 animate-fade-up fill-both delay-400 <?php echo e($isAr ? 'justify-end' : ''); ?>">
                <a href="<?php echo e(route('products.index')); ?>" class="btn-primary clip-luxury-md">
                    <?php echo e($isAr ? 'استكشف المتجر' : 'Explore the Arsenal'); ?>

                    <svg class="w-4 h-4 <?php echo e($isAr ? 'rotate-180' : ''); ?>" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                    </svg>
                </a>
                <a href="<?php echo e(route('quiz')); ?>" class="btn-secondary clip-luxury-md">
                    <?php echo e($isAr ? 'اكتشف نوع بشرتك' : 'Find Your Formula'); ?>

                </a>
            </div>

            
            <div class="mt-10 animate-fade-up fill-both delay-500">
                <button
                    onclick="document.getElementById('waitlist-section').scrollIntoView({behavior:'smooth'})"
                    class="flex items-center gap-2 text-ferro-orange text-body-sm font-medium group <?php echo e($isAr ? 'flex-row-reverse' : ''); ?>"
                >
                    <span class="w-2 h-2 rounded-full bg-ferro-orange animate-pulse"></span>
                    <?php echo e($isAr ? 'القائمة مفتوحة — سجّل الآن للوصول المبكر' : 'Waitlist Open — Register for Early Access'); ?>

                    <svg class="w-4 h-4 group-hover:translate-y-1 transition-transform <?php echo e($isAr ? 'rotate-90' : '-rotate-90'); ?>" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/>
                    </svg>
                </button>
            </div>
        </div>

        
        <div class="absolute bottom-10 <?php echo e($isAr ? 'left-12' : 'right-12'); ?> hidden lg:flex flex-col items-center gap-3 animate-fade-in fill-both delay-600">
            <span class="text-ferro-ash text-label tracking-[0.2em] uppercase"
                  style="writing-mode: vertical-rl; text-orientation: mixed;">
                <?php echo e($isAr ? 'مرر للأسفل' : 'Scroll'); ?>

            </span>
            <div class="w-px h-16 bg-gradient-to-b from-ferro-carbon to-transparent"></div>
        </div>
    </div>
</section>


<section class="py-12 bg-ferro-obsidian border-y border-ferro-carbon" aria-label="Brand statistics">
    <div class="container-ferro">
        <div class="grid grid-cols-3 gap-6 reveal-stagger">
            <?php $__currentLoopData = [
                ['number' => $stats['natural_ingredients'], 'suffix' => '+', 'label' => $isAr ? 'مكوّن طبيعي' : 'Natural Ingredients'],
                ['number' => $stats['elite_athletes'],      'suffix' => '+', 'label' => $isAr ? 'رياضي نخبة' : 'Elite Athletes'],
                ['number' => $stats['countries'],           'suffix' => '',  'label' => $isAr ? 'دولة حول العالم' : 'Countries Worldwide'],
            ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="text-center <?php echo e($isAr ? 'text-right' : 'text-center'); ?>">
                    <div class="font-display text-display-xl text-ferro-white mb-1">
                        <span class="text-gradient-orange"><?php echo e($stat['number']); ?></span><?php echo e($stat['suffix']); ?>

                    </div>
                    <div class="text-ferro-ash text-label tracking-widest uppercase"><?php echo e($stat['label']); ?></div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</section>


<section class="section-pad" aria-labelledby="featured-heading">
    <div class="container-ferro">

        
        <div class="flex items-end justify-between mb-12 reveal">
            <div>
                <span class="eyebrow"><?php echo e($isAr ? 'ترسانة فيرو' : 'The Arsenal'); ?></span>
                <h2 id="featured-heading" class="font-display text-display-lg text-ferro-white">
                    <?php echo e($isAr ? 'أدوات لا تهادن' : 'Tools That Don\'t Compromise'); ?>

                </h2>
            </div>
            <a href="<?php echo e(route('products.index')); ?>" class="btn-ghost hidden sm:flex items-center gap-2">
                <?php echo e($isAr ? 'عرض الكل' : 'View All'); ?>

                <svg class="w-4 h-4 <?php echo e($isAr ? 'rotate-180' : ''); ?>" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                </svg>
            </a>
        </div>

        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 reveal-stagger">
            <?php $__empty_1 = true; $__currentLoopData = $featuredProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php echo $__env->make('partials.product-card', ['product' => $product], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                
                <?php $__currentLoopData = range(1, 4); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php echo $__env->make('partials.product-card-skeleton', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>
        </div>
    </div>
</section>


<section class="section-pad bg-ferro-obsidian" aria-labelledby="story-heading">
    <div class="container-ferro">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 lg:gap-24 items-center">

            
            <div class="relative reveal <?php echo e($isAr ? 'order-2' : 'order-1'); ?>">
                <div class="relative aspect-[4/5] overflow-hidden" style="border-radius: 2px;">
                    <img
                        src="<?php echo e(asset('images/brand-story.webp')); ?>"
                        alt="<?php echo e($isAr ? 'صورة تجسّد قوة فيرو' : 'FERRO — Power and refinement'); ?>"
                        class="w-full h-full object-cover"
                        loading="lazy"
                        width="600" height="750"
                    >
                    <div class="absolute inset-0 bg-gradient-to-t from-ferro-black/60 to-transparent"></div>
                </div>
                
                <div class="absolute -bottom-6 <?php echo e($isAr ? '-start-6' : '-end-6'); ?> w-32 h-32 bg-ferro-orange/10 border border-ferro-orange/20"
                     style="border-radius: 2px;" aria-hidden="true"></div>
                <div class="absolute -top-4 <?php echo e($isAr ? '-end-4' : '-start-4'); ?> w-20 h-20 bg-ferro-carbon border border-ferro-carbon"
                     style="border-radius: 2px;" aria-hidden="true">
                    <div class="w-full h-full flex items-center justify-center">
                        <svg class="w-8 h-8 text-ferro-orange" viewBox="0 0 32 32" fill="currentColor">
                            <path d="M4 4h24v6H12v4h14v6H12v8H4V4z"/>
                        </svg>
                    </div>
                </div>
            </div>

            
            <div class="<?php echo e($isAr ? 'order-1 text-right' : 'order-2'); ?> reveal">
                <span class="eyebrow"><?php echo e($isAr ? 'قصتنا' : 'Our Story'); ?></span>
                <h2 id="story-heading" class="font-display text-display-lg text-ferro-white mb-6">
                    <?php echo e($isAr ? 'وُلدنا لنسد الفراغ' : 'Born to Fill the Void'); ?>

                </h2>
                <div class="space-y-4 text-ferro-silver text-body-sm leading-relaxed">
                    <?php if($isAr): ?>
                        <p>في عالم تملؤه منتجات العناية للمرأة، كان الرجل عالي الأداء يُترك خلف الركب. فيرو وُلد ليغيّر هذا.</p>
                        <p>مستوحى من الكلمة اللاتينية للحديد، يعكس اسمنا قوة ومرونة الرياضي العصري. ندمج قوة المكونات الطبيعية مع رقي دار أزياء فاخرة.</p>
                        <p>لا نصنع مستحضرات تجميل فحسب؛ نحن نصنع أدوات أساسية للرجال الذين يتخطون حدودهم.</p>
                    <?php else: ?>
                        <p>Derived from the Latin word for Iron, our name reflects the strength and resilience of the modern athlete. We realized the high-performance man was left behind.</p>
                        <p>FERRO changes that by merging the raw power of natural ingredients with the refined sophistication of a luxury house.</p>
                        <p>We don't just create skincare. We forge essential tools for men who push their limits — from the intensity of the gym to the demands of a high-end lifestyle.</p>
                    <?php endif; ?>
                </div>
                <div class="mt-8 flex flex-wrap gap-4 <?php echo e($isAr ? 'justify-end' : ''); ?>">
                    <a href="<?php echo e(route('about')); ?>" class="btn-secondary clip-luxury-sm">
                        <?php echo e($isAr ? 'اقرأ القصة كاملة' : 'Read Full Story'); ?>

                    </a>
                </div>
            </div>
        </div>
    </div>
</section>


<?php if($comingSoonProducts->count()): ?>
<section class="section-pad" aria-labelledby="coming-soon-heading">
    <div class="container-ferro">
        <div class="text-center mb-12 reveal">
            <span class="eyebrow"><?php echo e($isAr ? 'قريباً' : 'Coming Soon'); ?></span>
            <h2 id="coming-soon-heading" class="font-display text-display-lg text-ferro-white">
                <?php echo e($isAr ? 'الترسانة تتوسع' : 'The Arsenal Expands'); ?>

            </h2>
            <p class="text-ferro-silver text-body-sm mt-3 max-w-md mx-auto">
                <?php echo e($isAr ? 'منتجات جديدة في الطريق. كن أول من يحصل عليها.' : 'New weapons are being forged. Be the first to claim them.'); ?>

            </p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 reveal-stagger">
            <?php $__currentLoopData = $comingSoonProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php echo $__env->make('partials.product-card', ['product' => $product, 'showComingSoon' => true], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</section>
<?php endif; ?>


<section
    id="waitlist-section"
    class="relative section-pad overflow-hidden"
    aria-labelledby="waitlist-heading"
>
    
    <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_50%_50%,rgba(232,80,10,0.08)_0%,transparent_70%)]" aria-hidden="true"></div>
    <div class="absolute inset-0 border-y border-ferro-carbon/30" aria-hidden="true"></div>

    <div class="container-ferro relative z-10">
        <div class="max-w-2xl mx-auto text-center reveal">

            
            <div class="flex items-center justify-center gap-4 mb-8">
                <div class="w-16 h-px bg-ferro-carbon"></div>
                <svg class="w-6 h-6 text-ferro-orange" viewBox="0 0 32 32" fill="currentColor" aria-hidden="true">
                    <path d="M4 4h24v6H12v4h14v6H12v8H4V4z"/>
                </svg>
                <div class="w-16 h-px bg-ferro-carbon"></div>
            </div>

            <span class="eyebrow"><?php echo e($isAr ? 'وصول حصري' : 'Exclusive Access'); ?></span>
            <h2 id="waitlist-heading" class="font-display text-display-xl text-ferro-white mb-4">
                <?php echo e($isAr ? 'كن أول من يحمل فيرو' : 'Be First to Carry FERRO'); ?>

            </h2>
            <p class="text-ferro-silver text-body-lg mb-8">
                <?php echo e($isAr
                    ? 'انضم إلى قائمة الانتظار للحصول على أسعار تأسيسية حصرية وشحن مجاني على طلبك الأول.'
                    : 'Join the waitlist for exclusive founding pricing and free shipping on your first order.'); ?>

            </p>

            
            <div class="waitlist-card max-w-lg mx-auto">
                <?php echo $__env->make('partials.waitlist-mini-form', ['formId' => 'hero-cta'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            </div>

            
            <p class="text-ferro-ash text-xs mt-6">
                <?php echo e($isAr ? 'انضم إلى أكثر من ٢٠٠٠ رجل في القائمة' : 'Join 2,000+ men already on the list'); ?>

            </p>
        </div>
    </div>
</section>


<section class="section-pad bg-ferro-obsidian" aria-labelledby="quiz-heading">
    <div class="container-ferro">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center reveal">
            <div class="<?php echo e($isAr ? 'text-right' : ''); ?>">
                <span class="eyebrow"><?php echo e($isAr ? 'خصّص روتينك' : 'Personalize Your Routine'); ?></span>
                <h2 id="quiz-heading" class="font-display text-display-lg text-ferro-white mb-5">
                    <?php echo e($isAr ? 'ما هو ملف بشرتك؟' : "What's Your Skin Profile?"); ?>

                </h2>
                <p class="text-ferro-silver text-body-sm mb-8">
                    <?php echo e($isAr
                        ? 'أجب على ٥ أسئلة واحصل على روتين فيرو المثالي لنمط حياتك.'
                        : 'Answer 5 questions and receive your perfect FERRO regimen tailored to your lifestyle.'); ?>

                </p>
                <a href="<?php echo e(route('quiz')); ?>" class="btn-primary clip-luxury-md inline-flex">
                    <?php echo e($isAr ? 'ابدأ الاختبار المجاني' : 'Take the Free Quiz'); ?>

                </a>
            </div>
            
            <div class="relative">
                <div class="grid grid-cols-2 gap-4">
                    <?php $__currentLoopData = [
                        ['icon' => '⚡', 'label' => $isAr ? 'الرياضي النخبة' : 'Elite Athlete'],
                        ['icon' => '🏙️', 'label' => $isAr ? 'رجل المدينة' : 'Urban Executive'],
                        ['icon' => '🌿', 'label' => $isAr ? 'الطبيعي النشط' : 'Active Naturalist'],
                        ['icon' => '💎', 'label' => $isAr ? 'المتميز' : 'The Refined'],
                    ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $profile): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="card-glass p-6 text-center hover:border-ferro-orange/40 transition-all duration-300 cursor-pointer group">
                            <div class="text-3xl mb-2"><?php echo e($profile['icon']); ?></div>
                            <div class="text-ferro-silver text-body-sm font-medium group-hover:text-ferro-white transition-colors">
                                <?php echo e($profile['label']); ?>

                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>
    </div>
</section>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>

<script>
// Parallax on hero background
(function() {
    const heroBg = document.getElementById('hero-bg');
    if (!heroBg || window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;
    window.addEventListener('scroll', () => {
        const y = window.scrollY;
        heroBg.style.transform = `translateY(${y * 0.3}px)`;
    }, { passive: true });
})();

// IntersectionObserver for reveal animations
(function() {
    const io = new IntersectionObserver((entries) => {
        entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('is-visible'); } });
    }, { threshold: 0.1, rootMargin: '0px 0px -60px 0px' });
    document.querySelectorAll('.reveal, .reveal-stagger').forEach(el => io.observe(el));
})();
</script>

<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>