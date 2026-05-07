<?php

/**
 * Default SEO copy for storefront views. Admin overrides are stored in `storefront_seo_pages`.
 * Use :order_number in titles/descriptions where noted — replaced at render time.
 */
return [
    'labels' => [
        'home' => ['en' => 'Homepage', 'ar' => 'الصفحة الرئيسية'],
        'shop' => ['en' => 'Shop (product listing)', 'ar' => 'المتجر'],
        'cart' => ['en' => 'Cart', 'ar' => 'سلة التسوق'],
        'checkout' => ['en' => 'Checkout', 'ar' => 'إتمام الطلب'],
        'order_thanks' => ['en' => 'Order thank-you', 'ar' => 'شكراً بعد الطلب'],
        'account_index' => ['en' => 'My account (dashboard)', 'ar' => 'حسابي'],
        'account_order' => ['en' => 'Order detail', 'ar' => 'تفاصيل الطلب'],
        'orders_track' => ['en' => 'Order tracking (signed link)', 'ar' => 'تتبع الطلب'],
        'auth_login' => ['en' => 'Sign in', 'ar' => 'تسجيل الدخول'],
        'auth_register' => ['en' => 'Register', 'ar' => 'التسجيل'],
        'auth_forgot_password' => ['en' => 'Forgot password', 'ar' => 'نسيت كلمة المرور'],
        'auth_reset_password' => ['en' => 'Reset password', 'ar' => 'إعادة تعيين كلمة المرور'],
        'about' => ['en' => 'About / Our story', 'ar' => 'من نحن'],
        'quiz' => ['en' => 'Skin quiz', 'ar' => 'اختبار البشرة'],
        'contact' => ['en' => 'Contact', 'ar' => 'تواصل'],
    ],
    'pages' => [
        'home' => [
            'meta_title' => [
                'en' => 'FERRO — Forged from Iron, Polished by Luxury | Premium Men\'s Grooming',
                'ar' => 'فيرو — مصنوع من الحديد، مصقول بالرفاهية | عناية الرجل الفاخرة',
            ],
            'meta_description' => [
                'en' => 'Premium nature-powered grooming essentials engineered for the high-performance man and elite athlete. Natural. Powerful. Uncompromising.',
                'ar' => 'منتجات عناية فاخرة للرجال والرياضيين النخبة. طبيعية. قوية. لا تهادن.',
            ],
            'meta_keywords' => [
                'en' => 'mens grooming, luxury skincare, athlete skincare, natural grooming, FERRO',
                'ar' => 'عناية الرجل، العناية بالبشرة، فيرو',
            ],
        ],
        'shop' => [
            'meta_title' => ['en' => 'Shop — FERRO', 'ar' => 'المتجر — فيرو'],
            'meta_description' => [
                'en' => 'Shop premium luxury grooming essentials engineered for the high-performance man.',
                'ar' => 'تسوّق منتجات العناية الفاخرة المصممة للرجل عالي الأداء.',
            ],
            'meta_keywords' => [
                'en' => 'FERRO shop, mens grooming products',
                'ar' => 'متجر فيرو',
            ],
        ],
        'cart' => [
            'meta_title' => ['en' => 'Your Arsenal — FERRO Cart', 'ar' => 'سلة التسوق — فيرو'],
            'meta_description' => [
                'en' => 'Review your FERRO cart and proceed to secure checkout.',
                'ar' => 'راجع سلتك وأكمل الطلب بأمان.',
            ],
            'meta_keywords' => ['en' => 'FERRO cart', 'ar' => 'سلة فيرو'],
        ],
        'checkout' => [
            'meta_title' => ['en' => 'Checkout — FERRO', 'ar' => 'إتمام الطلب — فيرو'],
            'meta_description' => [
                'en' => 'Complete your FERRO order — shipping and payment.',
                'ar' => 'أكمل طلبك من فيرو — العنوان والدفع.',
            ],
            'meta_keywords' => ['en' => 'FERRO checkout', 'ar' => 'دفع فيرو'],
        ],
        'order_thanks' => [
            'meta_title' => ['en' => 'Thank You — FERRO', 'ar' => 'شكراً لطلبك — فيرو'],
            'meta_description' => [
                'en' => 'Your FERRO order was received. Check your email for confirmation.',
                'ar' => 'تم استلام طلبك. تحقق من بريدك للتأكيد.',
            ],
            'meta_keywords' => ['en' => 'FERRO order confirmed', 'ar' => 'تأكيد الطلب'],
        ],
        'account_index' => [
            'meta_title' => ['en' => 'My Account — FERRO', 'ar' => 'حسابي — فيرو'],
            'meta_description' => [
                'en' => 'View your FERRO orders, profile, and subscriptions.',
                'ar' => 'طلباتك وملفك في فيرو.',
            ],
            'meta_keywords' => ['en' => 'FERRO account', 'ar' => 'حساب فيرو'],
        ],
        'account_order' => [
            'meta_title' => [
                'en' => 'Order :order_number — FERRO',
                'ar' => 'تفاصيل الطلب :order_number — فيرو',
            ],
            'meta_description' => [
                'en' => 'Order :order_number — status, items, and invoice on FERRO.',
                'ar' => 'الطلب :order_number — الحالة والمنتجات والفاتورة.',
            ],
            'meta_keywords' => ['en' => 'FERRO order', 'ar' => 'طلب فيرو'],
        ],
        'orders_track' => [
            'meta_title' => [
                'en' => 'Track order :order_number — FERRO',
                'ar' => 'تتبع الطلب :order_number — فيرو',
            ],
            'meta_description' => [
                'en' => 'Track FERRO order :order_number — current status and tracking.',
                'ar' => 'تتبع طلب فيرو :order_number — الحالة ورقم الشحن.',
            ],
            'meta_keywords' => ['en' => 'FERRO order tracking', 'ar' => 'تتبع الطلب'],
        ],
        'auth_login' => [
            'meta_title' => ['en' => 'Sign In — FERRO', 'ar' => 'تسجيل الدخول — فيرو'],
            'meta_description' => [
                'en' => 'Sign in to your FERRO account.',
                'ar' => 'سجّل الدخول إلى حساب فيرو.',
            ],
            'meta_keywords' => ['en' => 'FERRO login', 'ar' => 'دخول فيرو'],
        ],
        'auth_register' => [
            'meta_title' => ['en' => 'Create Account — FERRO', 'ar' => 'إنشاء حساب — فيرو'],
            'meta_description' => [
                'en' => 'Create your FERRO customer account.',
                'ar' => 'أنشئ حساباً في متجر فيرو.',
            ],
            'meta_keywords' => ['en' => 'FERRO register', 'ar' => 'تسجيل فيرو'],
        ],
        'auth_forgot_password' => [
            'meta_title' => ['en' => 'Forgot Password — FERRO', 'ar' => 'استعادة كلمة المرور — فيرو'],
            'meta_description' => [
                'en' => 'Reset your FERRO account password.',
                'ar' => 'إعادة تعيين كلمة مرور حسابك.',
            ],
            'meta_keywords' => ['en' => 'FERRO password reset', 'ar' => 'استعادة كلمة المرور'],
        ],
        'auth_reset_password' => [
            'meta_title' => ['en' => 'Set New Password — FERRO', 'ar' => 'تعيين كلمة مرور جديدة — فيرو'],
            'meta_description' => [
                'en' => 'Choose a new password for your FERRO account.',
                'ar' => 'اختر كلمة مرور جديدة لحسابك.',
            ],
            'meta_keywords' => ['en' => 'FERRO new password', 'ar' => 'كلمة مرور جديدة'],
        ],
        'about' => [
            'meta_title' => [
                'en' => 'Our Story — FERRO | Forged from Iron, Polished by Luxury',
                'ar' => 'قصتنا — فيرو | مصنوع من الحديد، مصقول بالرفاهية',
            ],
            'meta_description' => [
                'en' => 'FERRO was born to fill a void in the market for the high-performance man. Discover our mission and values.',
                'ar' => 'فيرو وُلد لسد فراغ في سوق العناية للرجل عالي الأداء. اكتشف مهمتنا وقيمنا.',
            ],
            'meta_keywords' => ['en' => 'FERRO brand story', 'ar' => 'قصة فيرو'],
        ],
        'quiz' => [
            'meta_title' => [
                'en' => 'Skin Quiz — Discover Your Perfect FERRO Routine',
                'ar' => 'اختبار البشرة — اكتشف روتين فيرو المثالي لك',
            ],
            'meta_description' => [
                'en' => 'Answer 5 questions and receive personalized FERRO product recommendations.',
                'ar' => 'أجب على ٥ أسئلة واحصل على توصيات فيرو المخصصة لنوع بشرتك.',
            ],
            'meta_keywords' => ['en' => 'FERRO skin quiz', 'ar' => 'اختبار البشرة'],
        ],
        'contact' => [
            'meta_title' => ['en' => 'Contact Us — FERRO', 'ar' => 'تواصل معنا — فيرو'],
            'meta_description' => [
                'en' => 'Get in touch with the FERRO team for product inquiries, order support, or partnerships.',
                'ar' => 'تواصل مع فريق فيرو لأي استفسارات حول منتجاتنا أو طلباتك.',
            ],
            'meta_keywords' => ['en' => 'FERRO contact', 'ar' => 'اتصل بفيرو'],
        ],
    ],
];
