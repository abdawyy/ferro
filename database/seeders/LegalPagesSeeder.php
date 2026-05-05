<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

class LegalPagesSeeder extends Seeder
{
    public function run(): void
    {
        $pages = [
            [
                'slug' => 'privacy-policy',
                'sort_order' => 1,
                'title' => [
                    'en' => 'Privacy Policy',
                    'ar' => 'سياسة الخصوصية',
                ],
                'meta_title' => [
                    'en' => 'Privacy Policy',
                    'ar' => 'سياسة الخصوصية',
                ],
                'meta_description' => [
                    'en' => 'How FERRO collects, uses, and protects your personal information.',
                    'ar' => 'كيف تجمع فيرو معلوماتك الشخصية وتستخدمها وتحميها.',
                ],
                'content' => [
                    'en' => <<<'HTML'
<h2>Introduction</h2>
<p>This Privacy Policy describes how FERRO (&ldquo;we,&rdquo; &ldquo;us,&rdquo; or &ldquo;our&rdquo;) handles personal information when you visit our website, create an account, place an order, or contact us. Replace this text with your final policy after legal review.</p>

<h2>Information we collect</h2>
<p>We may collect information you provide directly (such as name, email, shipping address, and payment details processed by our payment partners) and technical data (such as device type, browser, and approximate location derived from IP) needed to operate and secure the site.</p>

<h2>How we use information</h2>
<p>We use personal information to fulfill orders, provide customer support, send transactional messages, improve our products and services, comply with legal obligations, and—with your consent where required—for marketing.</p>

<h2>Sharing</h2>
<p>We may share information with service providers who assist with hosting, analytics, payments, and shipping, subject to contracts that require appropriate protection. We may disclose information if required by law or to protect our rights and users.</p>

<h2>Retention &amp; security</h2>
<p>We retain information as long as needed for the purposes above and as required by law. We implement reasonable technical and organizational measures to protect personal data.</p>

<h2>Your rights</h2>
<p>Depending on your location, you may have rights to access, correct, delete, or restrict processing of your personal data, or to object to certain processing. Contact us using the details on our contact page to exercise these rights.</p>

<h2>Contact</h2>
<p>For privacy questions, please reach out via the contact information published on our storefront.</p>
HTML,
                    'ar' => <<<'HTML'
<h2>مقدمة</h2>
<p>تصف سياسة الخصوصية هذه كيف تتعامل فيرو (&laquo;نحن&raquo;) مع المعلومات الشخصية عند زيارتك لموقعنا أو إنشاء حساب أو تقديم طلب أو التواصل معنا. استبدل هذا النص بصياغتك النهائية بعد المراجعة القانونية.</p>

<h2>المعلومات التي نجمعها</h2>
<p>قد نجمع المعلومات التي تقدمها مباشرة (مثل الاسم والبريد وعنوان الشحن وبيانات الدفع التي تعالجها شركاء الدفع) وبيانات تقنية (مثل نوع الجهاز والمتصفح والموقع التقريبي المستنتج من عنوان IP) اللازمة لتشغيل الموقع وحمايته.</p>

<h2>كيفية استخدام المعلومات</h2>
<p>نستخدم المعلومات الشخصية لتنفيذ الطلبات وتقديم دعم العملاء وإرسال الرسائل التشغيلية وتحسين منتجاتنا وخدماتنا والامتثال للالتزامات القانونية و—بموافقتك عند الاقتضاء—للتسويق.</p>

<h2>المشاركة</h2>
<p>قد نشارك المعلومات مع مزودي خدمات يساعدون في الاستضافة والتحليلات والمدفوعات والشحن، وفق عقود تتطلب الحماية المناسبة. قد نكشف عن المعلومات إذا اقتضى القانون ذلك أو لحماية حقوقنا ومستخدمينا.</p>

<h2>الاحتفاظ والأمن</h2>
<p>نحتفظ بالمعلومات ما دامت لازمة للأغراض أعلاه وكما يقتضي القانون. نطبق تدابير تقنية وتنظيمية معقولة لحماية البيانات الشخصية.</p>

<h2>حقوقك</h2>
<p>بحسب موقعك، قد يكون لديك حقوق في الوصول أو التصحيح أو الحذف أو تقييد المعالجة أو الاعتراض على بعض المعالجات. تواصل معنا عبر معلومات الاتصال المنشورة في المتجر لممارسة هذه الحقوق.</p>

<h2>التواصل</h2>
<p>لأسئلة الخصوصية، يرجى التواصل عبر معلومات الاتصال المنشورة في واجهة المتجر.</p>
HTML,
                ],
            ],
            [
                'slug' => 'terms-of-service',
                'sort_order' => 2,
                'title' => [
                    'en' => 'Terms of Service',
                    'ar' => 'الشروط والأحكام',
                ],
                'meta_title' => [
                    'en' => 'Terms of Service',
                    'ar' => 'الشروط والأحكام',
                ],
                'meta_description' => [
                    'en' => 'Terms governing use of the FERRO website and purchases.',
                    'ar' => 'الشروط الحاكمة لاستخدام موقع فيرو والمشتريات.',
                ],
                'content' => [
                    'en' => <<<'HTML'
<h2>Agreement</h2>
<p>By accessing or using the FERRO website and services, you agree to these Terms of Service. If you do not agree, please do not use the site. Replace this draft with counsel-approved terms for your jurisdiction.</p>

<h2>Eligibility &amp; account</h2>
<p>You must be legally able to enter a contract in your region. You are responsible for maintaining the confidentiality of your account credentials and for activity under your account.</p>

<h2>Products &amp; pricing</h2>
<p>We strive to display accurate product information and pricing. We may correct errors, refuse or cancel orders affected by manifest errors, and update availability without notice where permitted by law.</p>

<h2>Orders &amp; payment</h2>
<p>Placing an order constitutes an offer to purchase. We accept orders when confirmed (for example, by order confirmation email). Payment is processed through our payment partners; you agree to their terms where applicable.</p>

<h2>Shipping &amp; risk</h2>
<p>Shipping timelines and carriers are described at checkout or in order communications. Risk of loss may pass to you upon delivery to the carrier or upon delivery to you, as stated in our shipping policy or applicable law.</p>

<h2>Intellectual property</h2>
<p>All content on this site (including trademarks, logos, text, and images) is owned by FERRO or its licensors. You may not copy or exploit it without permission, except as allowed for personal, non-commercial browsing.</p>

<h2>Disclaimer &amp; limitation</h2>
<p>To the fullest extent permitted by law, the site and products are provided &ldquo;as is.&rdquo; We limit liability as permitted by applicable law. Some jurisdictions do not allow certain limitations; in those cases our liability is limited to the maximum extent allowed.</p>

<h2>Governing law</h2>
<p>These terms are governed by the laws of the jurisdiction you designate after legal review, without regard to conflict-of-law rules.</p>

<h2>Changes</h2>
<p>We may update these terms from time to time. Continued use after changes constitutes acceptance of the revised terms where permitted by law.</p>

<h2>Contact</h2>
<p>Questions about these terms can be directed to the contact information on our storefront.</p>
HTML,
                    'ar' => <<<'HTML'
<h2>الموافقة</h2>
<p>باستخدامك لموقع فيرو وخدماته فإنك توافق على شروط الخدمة هذه. إذا لم توافق، يرجى عدم استخدام الموقع. استبدل هذه المسودة بصياغة معتمدة قانونياً لولايتك القضائية.</p>

<h2>الأهلية والحساب</h2>
<p>يجب أن تكون مؤهلاً قانونياً لإبرام عقد في منطقتك. أنت مسؤول عن سرية بيانات اعتماد حسابك وعن النشاط تحت حسابك.</p>

<h2>المنتجات والأسعار</h2>
<p>نسعى لعرض معلومات المنتجات والأسعار بدقة. قد نصحح الأخطاء أو نرفض أو نلغي الطلبات المتأثرة بأخطاء ظاهرة ونحدث التوفر دون إشعار حيث يسمح القانون.</p>

<h2>الطلبات والدفع</h2>
<p>تقديم الطلب يمثل عرض شراء. نقبل الطلبات عند التأكيد (مثلاً عبر بريد تأكيد الطلب). تتم معالجة الدفع عبر شركائنا في الدفع وتوافق على شروطهم حيث ينطبق ذلك.</p>

<h2>الشحن والمخاطر</h2>
<p>تُوضح مدة الشحن والناقلون عند الدفع أو في اتصالات الطلب. قد تنتقل مخاطر الفقدان إليك عند التسليم للناقل أو عند التسليم إليك، كما هو مذكور في سياسة الشحن أو القانون المعمول به.</p>

<h2>الملكية الفكرية</h2>
<p>جميع محتويات الموقع (بما في ذلك العلامات والشعارات والنصوص والصور) مملوكة لفيرو أو مرخصيها. لا يجوز نسخها أو استغلالها دون إذن، باستثناء التصفح الشخصي غير التجاري.</p>

<h2>إخلاء المسؤولية والحد</h2>
<p>في أقصى حد يسمح به القانون، يُقدَّم الموقع والمنتجات &laquo;كما هي&raquo;. نحدّ من المسؤولية كما يسمح القانون. بعض الولايات لا تسمح بحدود معينة؛ في تلك الحالات تُقيَّد مسؤوليتنا بأقصى حد مسموح.</p>

<h2>القانون الحاكم</h2>
<p>تحكم هذه الشروط قوانين الولاية القضائية التي تحددها بعد المراجعة القانونية، دون مراعاة تعارض القوانين.</p>

<h2>التغييرات</h2>
<p>قد نحدّث هذه الشروط من وقت لآخر. الاستمرار بعد التغييرات يُعد قبولاً للشروط المعدلة حيث يسمح القانون.</p>

<h2>التواصل</h2>
<p>للأسئلة حول هذه الشروط، راجع معلومات الاتصال في واجهة المتجر.</p>
HTML,
                ],
            ],
            [
                'slug' => 'return-policy',
                'sort_order' => 3,
                'title' => [
                    'en' => 'Return Policy',
                    'ar' => 'سياسة الإرجاع',
                ],
                'meta_title' => [
                    'en' => 'Return Policy',
                    'ar' => 'سياسة الإرجاع',
                ],
                'meta_description' => [
                    'en' => 'Returns, exchanges, and refunds for FERRO orders.',
                    'ar' => 'الإرجاع والاستبدال والاسترداد لطلبات فيرو.',
                ],
                'content' => [
                    'en' => <<<'HTML'
<h2>Overview</h2>
<p>We want you to be satisfied with your purchase. This Return Policy is a generic template—replace it with rules that match your operations, payment partners, and local consumer laws.</p>

<h2>Eligibility</h2>
<p>Items must typically be unopened, unused, and in original packaging where hygiene or safety requires it. Certain products (for example, opened consumables) may not be returnable unless defective or as required by law.</p>

<h2>Time window</h2>
<p>Return requests should be initiated within a stated number of days from delivery (define the period in your final policy). Late requests may be declined except where law requires otherwise.</p>

<h2>How to start a return</h2>
<p>Contact our support team with your order number and reason for the return. We will provide instructions, including any return authorization or label where applicable.</p>

<h2>Refunds</h2>
<p>Approved refunds are typically issued to the original payment method within a reasonable processing window after we receive and inspect the returned items. Shipping charges may be non-refundable unless the return is due to our error or a defective product.</p>

<h2>Exchanges</h2>
<p>Exchanges may be offered subject to stock availability. If an exact exchange is not available, a refund or store credit may be offered according to your final policy.</p>

<h2>Damaged or incorrect items</h2>
<p>If you receive a damaged or wrong item, contact us promptly with photos and your order details. We will work to replace the item or refund you as appropriate.</p>

<h2>Non-returnable items</h2>
<p>Clearly list categories that cannot be returned (e.g., final sale, gift cards, personalized items) consistent with applicable regulations.</p>

<h2>Contact</h2>
<p>For return assistance, use the contact options published on our storefront.</p>
HTML,
                    'ar' => <<<'HTML'
<h2>نظرة عامة</h2>
<p>نسعى لرضاك عن مشترياتك. سياسة الإرجاع هذه قالب عام—استبدلها بقواعد تتوافق مع عملياتك وشركاء الدفع وقوانين المستهلك المحلية.</p>

<h2>الأهلية</h2>
<p>عادةً يجب أن تكون المنتجات غير مفتوحة وغير مستخدمة وفي عبوتها الأصلية حيث تتطلب النظافة أو السلامة ذلك. قد لا تُرد بعض المنتجات (مثل المستهلكات المفتوحة) إلا إذا كانت معيبة أو كما يقتضي القانون.</p>

<h2>الإطار الزمني</h2>
<p>يُفضّل بدء طلبات الإرجاع خلال عدد محدد من الأيام من التسليم (حدد المدة في سياساتك النهائية). قد تُرفض الطلبات المتأخرة إلا حيث يفرض القانون خلاف ذلك.</p>

<h2>كيفية بدء الإرجاع</h2>
<p>تواصل مع فريق الدعم مع رقم طلبك وسبب الإرجاع. سنزودك بالتعليمات بما في ذلك أي تفويض إرجاع أو ملصق شحن حيث ينطبق.</p>

<h2>الاسترداد</h2>
<p>تُصدر المبالغ المستردة المعتمدة عادةً إلى وسيلة الدفع الأصلية خلال فترة معالجة معقولة بعد استلامنا وفحصنا للعناصر المرتجعة. قد لا تُسترد رسوم الشحن إلا إذا كان الإرجاع بسبب خطأ منا أو منتج معيب.</p>

<h2>الاستبدال</h2>
<p>قد يُعرض الاستبدال وفق توفر المخزون. إذا لم يتوفر استبدال مطابق، قد يُعرض استرداد أو رصيد متجر وفق سياساتك النهائية.</p>

<h2>عناصر تالفة أو خاطئة</h2>
<p>إذا استلمت عنصراً تالفاً أو خاطئاً، تواصل معنا فوراً مع صور وتفاصيل طلبك. سنعمل على استبدال العنصر أو استرداد المبلغ حسب الاقتضاء.</p>

<h2>عناصر غير قابلة للإرجاع</h2>
<p>اذكر بوضوح الفئات التي لا يمكن إرجاعها (مثل البيع النهائي وبطاقات الهدايا والمنتجات المخصصة) بما يتوافق مع الأنظمة المعمول بها.</p>

<h2>التواصل</h2>
<p>للمساعدة في الإرجاع، استخدم خيارات التواصل المنشورة في واجهة المتجر.</p>
HTML,
                ],
            ],
        ];

        foreach ($pages as $data) {
            Page::updateOrCreate(
                ['slug' => $data['slug']],
                [
                    'title' => $data['title'],
                    'content' => $data['content'],
                    'meta_title' => $data['meta_title'],
                    'meta_description' => $data['meta_description'],
                    'is_published' => true,
                    'sort_order' => $data['sort_order'],
                    'template' => 'default',
                ]
            );
        }
    }
}
