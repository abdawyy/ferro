<?php

namespace Database\Seeders;

use App\Models\Lead;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class EgyptianDemoSeeder extends Seeder
{
    public function run(): void
    {
        // ── 1. Categories ─────────────────────────────────────────────────
        $categories = [
            [
                'name'        => ['en' => 'Face Care', 'ar' => 'العناية بالوجه'],
                'description' => ['en' => 'Premium face treatments engineered for performance.', 'ar' => 'علاجات وجه فاخرة مصممة للأداء العالي.'],
                'slug'        => 'face-care',
                'sort_order'  => 1,
                'is_active'   => true,
            ],
            [
                'name'        => ['en' => 'Body & Sport', 'ar' => 'الجسم والرياضة'],
                'description' => ['en' => 'Post-workout recovery and body essentials.', 'ar' => 'ضروريات الجسم والتعافي بعد التمرين.'],
                'slug'        => 'body-sport',
                'sort_order'  => 2,
                'is_active'   => true,
            ],
            [
                'name'        => ['en' => 'Hair & Beard', 'ar' => 'الشعر واللحية'],
                'description' => ['en' => 'Grooming essentials for hair and beard.', 'ar' => 'أساسيات العناية بالشعر واللحية.'],
                'slug'        => 'hair-beard',
                'sort_order'  => 3,
                'is_active'   => true,
            ],
            [
                'name'        => ['en' => 'Fragrance', 'ar' => 'العطور'],
                'description' => ['en' => 'Signature scents for the modern Egyptian man.', 'ar' => 'عطور مميزة للرجل المصري العصري.'],
                'slug'        => 'fragrance',
                'sort_order'  => 4,
                'is_active'   => true,
            ],
        ];

        $createdCategories = [];
        foreach ($categories as $cat) {
            $createdCategories[$cat['slug']] = ProductCategory::firstOrCreate(
                ['slug' => $cat['slug']],
                $cat
            );
        }

        // ── 2. Products ───────────────────────────────────────────────────
        $products = [
            [
                'category_slug'     => 'face-care',
                'sku'               => 'FRR-FC-001',
                'slug'              => 'iron-recovery-serum',
                'name'              => ['en' => 'Iron Recovery Serum', 'ar' => 'سيروم استرداد الحديد'],
                'tagline'           => ['en' => 'Forged for the elite athlete', 'ar' => 'مصنوع للرياضي النخبة'],
                'short_description' => ['en' => 'High-performance serum with Nile-sourced minerals and arnica to reduce post-training inflammation and restore skin resilience.', 'ar' => 'سيروم عالي الأداء بمعادن نهر النيل والأرنيكا لتقليل الالتهاب بعد التدريب واستعادة مرونة البشرة.'],
                'description'       => ['en' => '<p>FERRO Iron Recovery Serum is engineered for the man who pushes limits. Infused with Nile-sourced mineral complex, arnica montana, and encapsulated vitamin C, this serum delivers rapid recovery at the cellular level.</p><p>Developed in collaboration with Egyptian sports dermatologists, the formula neutralises free radicals generated during intense training while rebuilding the skin barrier.</p>', 'ar' => '<p>سيروم استرداد الحديد من فيرو مصمم للرجل الذي يتجاوز الحدود. يحتوي على مركب معادن من النيل والأرنيكا وفيتامين C المغلف لتقديم تعافٍ سريع على المستوى الخلوي.</p>'],
                'ingredients'       => ['en' => 'Aqua, Nile Mineral Complex, Arnica Montana Extract, Ascorbyl Glucoside (Vitamin C 15%), Hyaluronic Acid, Niacinamide, Allantoin, Glycerin, Panthenol', 'ar' => 'الماء، مركب معادن النيل، مستخلص الأرنيكا، جلوكوسيد الأسكوربيل (فيتامين C 15%)، حمض الهيالورونيك، نياسيناميد'],
                'how_to_use'        => ['en' => 'Apply 3-4 drops to cleansed face morning and evening. Gently press into skin. Follow with moisturizer.', 'ar' => 'ضعي 3-4 قطرات على الوجه النظيف صباحاً ومساءً. اضغطي برفق على الجلد. أتبعي بالمرطب.'],
                'benefits'          => ['en' => ['Reduces post-workout redness in 72h', 'Boosts skin resilience by 40%', 'Clinically tested on athletes'], 'ar' => ['يقلل الاحمرار بعد التمرين خلال 72 ساعة', 'يعزز مرونة البشرة بنسبة 40%', 'تم اختباره سريرياً على الرياضيين']],
                'price'             => 349.00,
                'compare_price'     => 420.00,
                'currency'          => 'EGP',
                'status'            => 'active',
                'stock_quantity'    => 87,
                'featured_image'    => 'images/product-1.jpg',
                'gallery_images'    => ['images/product-1.jpg', 'images/product-2.jpg'],
                'is_featured'       => true,
                'is_best_seller'    => true,
                'is_new_arrival'    => false,
                'is_subscribable'   => true,
                'quiz_tags'         => ['athlete', 'recovery', 'oily', 'combo'],
                'weight_grams'      => 45,
                'volume_ml'         => 30,
                'sort_order'        => 1,
            ],
            [
                'category_slug'     => 'face-care',
                'sku'               => 'FRR-FC-002',
                'slug'              => 'pharaoh-moisturizing-shield',
                'name'              => ['en' => 'Pharaoh Moisturizing Shield', 'ar' => 'درع الترطيب الفرعوني'],
                'tagline'           => ['en' => 'Ancient wisdom, modern protection', 'ar' => 'حكمة قديمة، حماية عصرية'],
                'short_description' => ['en' => 'Inspired by ancient Egyptian alchemy — shea butter, lotus extract, and SPF 30 in one powerful daily moisturizer.', 'ar' => 'مستوحى من الكيمياء المصرية القديمة — زبدة الشيا ومستخلص اللوتس وحماية SPF 30 في مرطب يومي واحد.'],
                'description'       => ['en' => '<p>The Pharaoh Moisturizing Shield draws on 5000 years of Egyptian botanical knowledge. Blue lotus, sourced from Luxor river farms, delivers powerful antioxidants while triple-action hyaluronic acid locks in moisture for 24 hours.</p>', 'ar' => '<p>درع الترطيب الفرعوني يستلهم من 5000 سنة من المعرفة النباتية المصرية. اللوتس الأزرق من مزارع الأقصر.</p>'],
                'ingredients'       => ['en' => 'Aqua, Shea Butter, Blue Lotus Extract (Luxor Origin), Hyaluronic Acid (3-weight), Zinc Oxide, Aloe Vera, Jojoba Oil, Vitamin E', 'ar' => 'الماء، زبدة الشيا، مستخلص اللوتس الأزرق (أصل الأقصر)، حمض الهيالورونيك (3 أحجام)'],
                'how_to_use'        => ['en' => 'Apply a pea-sized amount to face and neck every morning after cleansing. The last step in your morning routine.', 'ar' => 'ضعي كمية صغيرة على الوجه والرقبة كل صباح بعد التنظيف.'],
                'benefits'          => ['en' => ['24h deep hydration', 'SPF 30 broad spectrum', 'Reduces fine lines in 4 weeks'], 'ar' => ['ترطيب عميق 24 ساعة', 'حماية SPF 30 واسع الطيف', 'يقلل الخطوط الدقيقة خلال 4 أسابيع']],
                'price'             => 275.00,
                'compare_price'     => null,
                'currency'          => 'EGP',
                'status'            => 'active',
                'stock_quantity'    => 124,
                'featured_image'    => 'images/product-2.jpg',
                'gallery_images'    => ['images/product-2.jpg'],
                'is_featured'       => true,
                'is_best_seller'    => false,
                'is_new_arrival'    => true,
                'is_subscribable'   => true,
                'quiz_tags'         => ['dry', 'aging', 'executive', 'refined'],
                'weight_grams'      => 60,
                'volume_ml'         => 50,
                'sort_order'        => 2,
            ],
            [
                'category_slug'     => 'body-sport',
                'sku'               => 'FRR-BS-001',
                'slug'              => 'nile-muscle-recovery-balm',
                'name'              => ['en' => 'Nile Muscle Recovery Balm', 'ar' => 'بلسم استرداد عضلات النيل'],
                'tagline'           => ['en' => 'Train hard. Recover harder.', 'ar' => 'تدرّب بقوة. تعافَ بقوة أكبر.'],
                'short_description' => ['en' => 'Cooling magnesium-charged recovery balm. Used by Egyptian national athletes. Reduces DOMS and speeds muscle repair overnight.', 'ar' => 'بلسم تعافٍ بارد مشحون بالمغنيسيوم. يستخدمه الرياضيون المصريون الوطنيون. يقلل آلام العضلات ويسرّع إصلاحها.'],
                'description'       => ['en' => '<p>Developed with Egyptian sports medicine specialists, the Nile Muscle Recovery Balm combines pharmaceutical-grade magnesium chloride with cooling menthol and warming capsaicin for dual-action relief. Apply post-training to prime muscle groups.</p>', 'ar' => '<p>طُوِّر مع متخصصي الطب الرياضي المصريين، يجمع بلسم استرداد عضلات النيل بين كلوريد المغنيسيوم الصيدلاني والمنثول المبرد.</p>'],
                'ingredients'       => ['en' => 'Magnesium Chloride (8%), Menthol (2%), Capsaicin Extract, Arnica Oil, Shea Butter, Beeswax, Eucalyptus Oil, Camphor', 'ar' => 'كلوريد المغنيسيوم (8%)، منثول (2%)، مستخلص الكابسايسين، زيت الأرنيكا'],
                'how_to_use'        => ['en' => 'Massage generously into sore muscles within 30 minutes post-workout. Reapply before sleep for overnight recovery.', 'ar' => 'دلّك كمية وافرة في العضلات المتألمة خلال 30 دقيقة بعد التمرين.'],
                'benefits'          => ['en' => ['Reduces DOMS by 60%', 'Activates in 5 minutes', 'No greasy residue'], 'ar' => ['يقلل آلام العضلات 60%', 'يفعّل في 5 دقائق', 'بدون بقايا دهنية']],
                'price'             => 195.00,
                'compare_price'     => 240.00,
                'currency'          => 'EGP',
                'status'            => 'active',
                'stock_quantity'    => 56,
                'featured_image'    => 'images/product-3.jpg',
                'gallery_images'    => ['images/product-3.jpg'],
                'is_featured'       => true,
                'is_best_seller'    => true,
                'is_new_arrival'    => false,
                'is_subscribable'   => false,
                'quiz_tags'         => ['athlete', 'recover', 'outdoor'],
                'weight_grams'      => 120,
                'volume_ml'         => 100,
                'sort_order'        => 3,
            ],
            [
                'category_slug'     => 'hair-beard',
                'sku'               => 'FRR-HB-001',
                'slug'              => 'cairo-beard-oil',
                'name'              => ['en' => 'Cairo Beard Oil', 'ar' => 'زيت لحية القاهرة'],
                'tagline'           => ['en' => "The city's power in every drop", 'ar' => 'قوة المدينة في كل قطرة'],
                'short_description' => ['en' => 'Cold-pressed Egyptian black seed oil blended with oud and sandalwood. Softens, conditions, and gives your beard a sharp, commanding presence.', 'ar' => 'زيت حبة السوداء المصري المعصور على البارد ممزوجاً بالعود والصندل. يليّن ويعالج ويمنح لحيتك حضوراً مميزاً.'],
                'description'       => ['en' => '<p>Cairo Beard Oil is a tribute to Egyptian craftsmanship. Black seed (Nigella Sativa) has been used in Egyptian medicine for 3000 years. Combined with oud wood extract and pure sandalwood, this oil is more than grooming — it is heritage.</p>', 'ar' => '<p>زيت لحية القاهرة تحية للحرفية المصرية. حبة السوداء (النيجيلا ساتيفا) تُستخدم في الطب المصري منذ 3000 سنة.</p>'],
                'ingredients'       => ['en' => 'Nigella Sativa Oil (Egyptian Cold-Press), Oud Wood Extract, Sandalwood Oil, Argan Oil, Jojoba Oil, Vitamin E, Natural Fragrance', 'ar' => 'زيت حبة السوداء المصري (عصر بارد)، مستخلص خشب العود، زيت الصندل، زيت الأرغان'],
                'how_to_use'        => ['en' => 'Warm 4-5 drops between palms, work through beard from roots to tips. Style as desired. Use daily after showering.', 'ar' => 'سخّن 4-5 قطرات بين راحتيك، ضعها في اللحية من الجذور إلى الأطراف.'],
                'benefits'          => ['en' => ['Eliminates beard itch in 3 days', 'Adds natural shine', 'Oud scent lasts 8+ hours'], 'ar' => ['يقضي على حكة اللحية في 3 أيام', 'يضيف لمعاناً طبيعياً', 'رائحة العود تدوم 8+ ساعات']],
                'price'             => 220.00,
                'compare_price'     => null,
                'currency'          => 'EGP',
                'status'            => 'active',
                'stock_quantity'    => 73,
                'featured_image'    => 'images/product-4.jpg',
                'gallery_images'    => ['images/product-4.jpg'],
                'is_featured'       => false,
                'is_best_seller'    => false,
                'is_new_arrival'    => true,
                'is_subscribable'   => true,
                'quiz_tags'         => ['refined', 'executive', 'perform'],
                'weight_grams'      => 35,
                'volume_ml'         => 30,
                'sort_order'        => 4,
            ],
            [
                'category_slug'     => 'fragrance',
                'sku'               => 'FRR-FR-001',
                'slug'              => 'iron-sands-edp',
                'name'              => ['en' => 'Iron Sands EDP', 'ar' => 'رمال الحديد - عطر مركّز'],
                'tagline'           => ['en' => 'The desert. The forge. The man.', 'ar' => 'الصحراء. المسبك. الرجل.'],
                'short_description' => ['en' => 'A bold Eau de Parfum opening with Egyptian oud and black pepper, settling into warm amber and musk. Made for the man who leaves a mark.', 'ar' => 'عطر مركّز جريء يبدأ بالعود المصري والفلفل الأسود، وينتهي بالعنبر الدافئ والمسك.'],
                'description'       => ['en' => '<p>Iron Sands EDP was created with master perfumers from Cairo and Grasse. The scent opens with Egyptian Oud and cracked black pepper, evolves through cardamom and smoky leather, and dries down to a base of warm amber, vetiver, and white musk. A true Egyptian luxury fragrance.</p>', 'ar' => '<p>أُنشئ عطر رمال الحديد مع عطارين متمرسين من القاهرة وغراس. تنفتح العطر بالعود المصري والفلفل الأسود المكسور.</p>'],
                'ingredients'       => ['en' => 'Top: Egyptian Oud, Black Pepper, Bergamot. Heart: Cardamom, Smoky Leather, Rose Absolute. Base: Amber, Vetiver, White Musk, Sandalwood', 'ar' => 'قمة: عود مصري، فلفل أسود، برغموت. قلب: هيل، جلد مدخن. قاعدة: عنبر، فيتيفر، مسك أبيض'],
                'how_to_use'        => ['en' => 'Spray 2-3 times on pulse points: wrists, neck, chest. Do not rub. Allow to dry naturally for maximum sillage.', 'ar' => 'رش 2-3 مرات على نقاط النبض: المعصمين، الرقبة، الصدر.'],
                'benefits'          => ['en' => ['12+ hour longevity', 'Strong projection (sillage)', '100% alcohol-free base option available'], 'ar' => ['ثبات 12+ ساعة', 'انتشار قوي', 'قاعدة خالية من الكحول متاحة']],
                'price'             => 850.00,
                'compare_price'     => 1050.00,
                'currency'          => 'EGP',
                'status'            => 'active',
                'stock_quantity'    => 32,
                'featured_image'    => 'images/product-1.jpg',
                'gallery_images'    => ['images/product-1.jpg'],
                'is_featured'       => true,
                'is_best_seller'    => false,
                'is_new_arrival'    => true,
                'is_subscribable'   => false,
                'quiz_tags'         => ['refined', 'executive', 'elevate'],
                'weight_grams'      => 150,
                'volume_ml'         => 50,
                'sort_order'        => 5,
            ],
            [
                'category_slug'     => 'face-care',
                'sku'               => 'FRR-FC-003',
                'slug'              => 'alexandria-night-repair',
                'name'              => ['en' => 'Alexandria Night Repair', 'ar' => 'إصلاح ليلة الإسكندرية'],
                'tagline'           => ['en' => 'Sleep is your most powerful tool', 'ar' => 'النوم أقوى أدواتك'],
                'short_description' => ['en' => 'Overnight regenerative cream inspired by Alexandria\'s Mediterranean heritage. Retinol 0.3%, ceramides, and Mediterranean olive squalane.', 'ar' => 'كريم ليلي متجدد مستوحى من إرث الإسكندرية المتوسطي. ريتينول 0.3%، سيراميدات، وسكوالان زيتون متوسطي.'],
                'description'       => ['en' => '<p>While you sleep, Alexandria Night Repair works through 4 phases of skin regeneration. Mediterranean olive squalane — sourced directly from Sinai coastal farms — provides the lipid framework for overnight barrier restoration.</p>', 'ar' => '<p>بينما تنام، يعمل إصلاح ليلة الإسكندرية من خلال 4 مراحل لتجديد البشرة.</p>'],
                'ingredients'       => ['en' => 'Squalane (Mediterranean Olive), Retinol 0.3%, Ceramide Complex, Peptide Blend (Matrixyl 3000), Bakuchiol, Niacinamide, Centella Asiatica', 'ar' => 'سكوالان (زيتون متوسطي)، ريتينول 0.3%، مركب السيراميد، مزيج الببتيد'],
                'how_to_use'        => ['en' => 'Apply to cleansed face as the final evening step. Start with 2-3 nights per week, build to nightly use over 4 weeks.', 'ar' => 'ضعي على الوجه النظيف كآخر خطوة مسائية. ابدأ بـ 2-3 ليالٍ في الأسبوع.'],
                'benefits'          => ['en' => ['Visible results in 14 nights', 'Reduces wrinkle depth by 35%', 'Dermatologist tested'], 'ar' => ['نتائج مرئية في 14 ليلة', 'يقلل عمق التجاعيد 35%', 'مختبر من قِبَل أطباء الجلدية']],
                'price'             => 0,
                'compare_price'     => null,
                'currency'          => 'EGP',
                'status'            => 'coming_soon',
                'stock_quantity'    => 0,
                'featured_image'    => 'images/product-2.jpg',
                'gallery_images'    => ['images/product-2.jpg'],
                'is_featured'       => false,
                'is_best_seller'    => false,
                'is_new_arrival'    => false,
                'is_subscribable'   => false,
                'quiz_tags'         => ['aging', 'dry', 'recover', 'refined'],
                'weight_grams'      => 50,
                'volume_ml'         => 30,
                'sort_order'        => 6,
                'available_at'      => now()->addMonths(2),
            ],
        ];

        $createdProducts = [];
        foreach ($products as $productData) {
            $categorySlug = $productData['category_slug'];
            unset($productData['category_slug']);

            $productData['category_id'] = $createdCategories[$categorySlug]->id;
            $productData['track_inventory'] = true;
            $productData['allow_backorder'] = false;
            $productData['low_stock_threshold'] = 10;

            $product = Product::firstOrCreate(
                ['slug' => $productData['slug']],
                $productData
            );
            $createdProducts[$product->slug] = $product;
        }

        // ── 3. Egyptian Users ─────────────────────────────────────────────
        $users = [
            ['name' => 'أحمد محمد الشريف',  'email' => 'ahmed.sharif@gmail.com',   'phone' => '+201001234567'],
            ['name' => 'محمود عبد الرحمن',   'email' => 'mahmoud.ar@hotmail.com',   'phone' => '+201112345678'],
            ['name' => 'كريم طارق النجار',   'email' => 'karim.najjar@yahoo.com',   'phone' => '+201234567890'],
            ['name' => 'عمر سامي الحسيني',   'email' => 'omar.s.husseini@gmail.com','phone' => '+201501234567'],
            ['name' => 'يوسف علاء الدين',    'email' => 'youssef.ala@gmail.com',    'phone' => '+201601234567'],
            ['name' => 'خالد رمضان فهمي',    'email' => 'khaled.fahmi@gmail.com',   'phone' => '+201701234567'],
            ['name' => 'تامر حسن المصري',    'email' => 'tamer.masry@gmail.com',    'phone' => '+201001112233'],
        ];

        $createdUsers = [];
        foreach ($users as $userData) {
            $user = User::firstOrCreate(
                ['email' => $userData['email']],
                [
                    'name'              => $userData['name'],
                    'email'             => $userData['email'],
                    'password'          => Hash::make('Password123!'),
                    'email_verified_at' => now()->subDays(rand(5, 120)),
                    'is_admin'          => false,
                ]
            );
            $createdUsers[] = $user;
        }

        // ── 4. Leads / Waitlist ───────────────────────────────────────────
        $leads = [
            ['email' => 'nour.hassan@gmail.com',    'first_name' => 'نور',    'last_name' => 'حسن',     'phone' => '+201001111111', 'source' => 'waitlist',  'country_code' => 'EG', 'preferred_language' => 'ar', 'on_waitlist' => true,  'engagement_score' => 85],
            ['email' => 'ibrahim.ali@gmail.com',    'first_name' => 'إبراهيم','last_name' => 'علي',     'phone' => '+201002222222', 'source' => 'quiz',      'country_code' => 'EG', 'preferred_language' => 'ar', 'on_waitlist' => false, 'engagement_score' => 72],
            ['email' => 'hana.samir@gmail.com',     'first_name' => 'هناء',   'last_name' => 'سمير',    'phone' => '+201003333333', 'source' => 'waitlist',  'country_code' => 'EG', 'preferred_language' => 'ar', 'on_waitlist' => true,  'engagement_score' => 90],
            ['email' => 'ziad.mostafa@icloud.com',  'first_name' => 'زياد',   'last_name' => 'مصطفى',   'phone' => '+971501234567', 'source' => 'newsletter','country_code' => 'AE', 'preferred_language' => 'ar', 'on_waitlist' => true,  'engagement_score' => 65],
            ['email' => 'ramy.adel@gmail.com',      'first_name' => 'رامي',   'last_name' => 'عادل',    'phone' => '+201004444444', 'source' => 'quiz',      'country_code' => 'EG', 'preferred_language' => 'ar', 'on_waitlist' => false, 'engagement_score' => 55],
            ['email' => 'mark.girgis@gmail.com',    'first_name' => 'مارك',   'last_name' => 'جرجس',    'phone' => '+201005555555', 'source' => 'waitlist',  'country_code' => 'EG', 'preferred_language' => 'en', 'on_waitlist' => true,  'engagement_score' => 78],
            ['email' => 'dina.khalil@yahoo.com',    'first_name' => 'دينا',   'last_name' => 'خليل',    'phone' => '+201006666666', 'source' => 'newsletter','country_code' => 'EG', 'preferred_language' => 'ar', 'on_waitlist' => false, 'engagement_score' => 40],
            ['email' => 'sherif.nasser@gmail.com',  'first_name' => 'شريف',   'last_name' => 'ناصر',    'phone' => '+201007777777', 'source' => 'quiz',      'country_code' => 'EG', 'preferred_language' => 'ar', 'on_waitlist' => true,  'engagement_score' => 95],
            ['email' => 'amr.diab.fan@gmail.com',   'first_name' => 'عمرو',   'last_name' => 'عبد الله','phone' => '+201008888888', 'source' => 'waitlist',  'country_code' => 'EG', 'preferred_language' => 'ar', 'on_waitlist' => true,  'engagement_score' => 88],
            ['email' => 'hassan.metwalli@gmail.com','first_name' => 'حسن',    'last_name' => 'متولي',   'phone' => '+201009999999', 'source' => 'referral',  'country_code' => 'EG', 'preferred_language' => 'ar', 'on_waitlist' => false, 'engagement_score' => 60],
        ];

        $waitlistProduct = $createdProducts['alexandria-night-repair'] ?? null;

        foreach ($leads as $leadData) {
            $leadData['marketing_consent'] = true;
            $leadData['gdpr_consent']      = true;
            $leadData['consented_at']      = now()->subDays(rand(1, 60));
            $leadData['last_engaged_at']   = now()->subDays(rand(0, 30));
            $leadData['status']            = 'new';
            $leadData['priority']          = $leadData['engagement_score'] >= 80 ? 'vip' : ($leadData['engagement_score'] >= 60 ? 'high' : 'standard');

            if ($leadData['on_waitlist'] && $waitlistProduct) {
                $leadData['waitlist_product_id'] = $waitlistProduct->id;
            }

            Lead::firstOrCreate(['email' => $leadData['email']], $leadData);
        }

        // ── 5. Orders ─────────────────────────────────────────────────────
        $egyptianCities = [
            ['city' => 'القاهرة',       'city_en' => 'Cairo',        'governorate' => 'Cairo',          'zip' => '11511'],
            ['city' => 'الإسكندرية',    'city_en' => 'Alexandria',   'governorate' => 'Alexandria',     'zip' => '21500'],
            ['city' => 'الجيزة',        'city_en' => 'Giza',         'governorate' => 'Giza',           'zip' => '12556'],
            ['city' => 'الشروق',        'city_en' => 'Al Shorouk',   'governorate' => 'Cairo',          'zip' => '11828'],
            ['city' => 'المعادي',       'city_en' => 'Maadi',        'governorate' => 'Cairo',          'zip' => '11431'],
            ['city' => 'هليوبوليس',     'city_en' => 'Heliopolis',   'governorate' => 'Cairo',          'zip' => '11361'],
            ['city' => 'المنصورة',      'city_en' => 'Mansoura',     'governorate' => 'Dakahlia',       'zip' => '35511'],
        ];

        $orderScenarios = [
            ['status' => 'delivered',       'payment_status' => 'paid',    'days_ago' => 45],
            ['status' => 'delivered',       'payment_status' => 'paid',    'days_ago' => 30],
            ['status' => 'shipped',         'payment_status' => 'paid',    'days_ago' => 5],
            ['status' => 'processing',      'payment_status' => 'paid',    'days_ago' => 2],
            ['status' => 'confirmed',       'payment_status' => 'paid',    'days_ago' => 1],
            ['status' => 'pending_payment', 'payment_status' => 'unpaid',  'days_ago' => 0],
            ['status' => 'cancelled',       'payment_status' => 'refunded','days_ago' => 20],
        ];

        $productList   = array_values($createdProducts);
        $activeProducts = array_filter($productList, fn($p) => $p->status === 'active');
        $activeProducts = array_values($activeProducts);

        foreach ($orderScenarios as $i => $scenario) {
            $user    = $createdUsers[$i % count($createdUsers)];
            $city    = $egyptianCities[$i % count($egyptianCities)];
            $address = [
                'first_name'  => explode(' ', $user->name)[0],
                'last_name'   => explode(' ', $user->name)[1] ?? '',
                'address_line_1' => ($i + 1) . ' شارع التحرير، شقة ' . ($i * 3 + 5),
                'city'        => $city['city'],
                'state'       => $city['governorate'],
                'postal_code' => $city['zip'],
                'country'     => 'EG',
                'phone'       => '+2010' . str_pad($i + 1, 8, '0', STR_PAD_LEFT),
            ];

            // Pick 1-2 products for each order
            $orderProducts = array_slice($activeProducts, $i % count($activeProducts), min(2, count($activeProducts)));
            $subtotal      = collect($orderProducts)->sum('price');
            $shipping      = $subtotal > 500 ? 0 : 49;
            $tax           = round($subtotal * 0.14, 2); // Egypt VAT 14%
            $total         = $subtotal + $shipping + $tax;

            $orderNumber = 'FRO-2026-' . str_pad($i + 1001, 6, '0', STR_PAD_LEFT);

            if (Order::where('order_number', $orderNumber)->exists()) continue;

            $order = Order::create([
                'order_number'     => $orderNumber,
                'user_id'          => $user->id,
                'status'           => $scenario['status'],
                'payment_status'   => $scenario['payment_status'],
                'subtotal'         => $subtotal,
                'discount_amount'  => 0,
                'shipping_amount'  => $shipping,
                'tax_amount'       => $tax,
                'tax_rate'         => 0.14,
                'total'            => $total,
                'currency'         => 'EGP',
                'exchange_rate'    => 1,
                'billing_address'  => $address,
                'shipping_address' => $address,
                'shipping_method'  => $i % 2 === 0 ? 'Aramex Egypt Standard' : 'Egypt Post Express',
                'tracking_number'  => $scenario['status'] === 'shipped' || $scenario['status'] === 'delivered' ? 'ARX' . rand(100000000, 999999999) : null,
                'carrier'          => $i % 2 === 0 ? 'Aramex' : 'Egypt Post',
                'payment_method'   => $i % 3 === 0 ? 'fawry' : ($i % 3 === 1 ? 'visa' : 'cash_on_delivery'),
                'payment_transaction_id' => 'TXN-EG-' . Str::upper(Str::random(10)),
                'paid_at'          => in_array($scenario['payment_status'], ['paid']) ? now()->subDays($scenario['days_ago']) : null,
                'shipped_at'       => in_array($scenario['status'], ['shipped', 'delivered']) ? now()->subDays($scenario['days_ago'] - 2) : null,
                'delivered_at'     => $scenario['status'] === 'delivered' ? now()->subDays($scenario['days_ago'] - 5) : null,
                'language'         => 'ar',
                'invoice_number'   => 'INV-2026-' . str_pad($i + 1001, 6, '0', STR_PAD_LEFT),
                'customer_notes'   => $i % 4 === 0 ? 'من فضلك أتصل قبل التوصيل' : null,
                'created_at'       => now()->subDays($scenario['days_ago']),
                'updated_at'       => now()->subDays($scenario['days_ago']),
            ]);

            // Order Items
            foreach ($orderProducts as $product) {
                OrderItem::create([
                    'order_id'          => $order->id,
                    'product_id'        => $product->id,
                    'product_name_en'   => $product->getTranslation('name', 'en', false),
                    'product_name_ar'   => $product->getTranslation('name', 'ar', false),
                    'product_sku'       => $product->sku,
                    'quantity'          => 1,
                    'unit_price'        => $product->price,
                    'discount_amount'   => 0,
                    'tax_rate'          => 0.14,
                    'tax_amount'        => round($product->price * 0.14, 4),
                    'line_total'        => $product->price,
                    'image_path'        => $product->featured_image,
                ]);
            }
        }

        $this->command->info('✅ Egyptian demo data seeded:');
        $this->command->info('   • ' . ProductCategory::count() . ' categories');
        $this->command->info('   • ' . Product::count() . ' products');
        $this->command->info('   • ' . User::where('is_admin', false)->count() . ' customers');
        $this->command->info('   • ' . Lead::count() . ' leads');
        $this->command->info('   • ' . Order::count() . ' orders');
    }
}
