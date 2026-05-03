<?php

namespace App\Support;

/**
 * Skin quiz copy for admin display (kept in sync with resources/views/quiz.blade.php).
 */
final class SkinQuizCatalog
{
    /**
     * @return list<array{key: string, en: string, ar: string, options: array<string, array{en: string, ar: string}>}>
     */
    public static function questions(): array
    {
        return [
            [
                'key' => 'lifestyle',
                'en'  => 'How would you describe your lifestyle?',
                'ar'  => 'كيف تصف نمط حياتك؟',
                'options' => [
                    'athlete'   => ['en' => 'Elite Athlete', 'ar' => 'رياضي نخبة'],
                    'executive' => ['en' => 'Urban Executive', 'ar' => 'رجل الأعمال'],
                    'outdoor'   => ['en' => 'Outdoor Enthusiast', 'ar' => 'محب الطبيعة'],
                    'refined'   => ['en' => 'The Refined Man', 'ar' => 'الرجل الراقي'],
                ],
            ],
            [
                'key' => 'concern',
                'en'  => "What's your primary skin concern?",
                'ar'  => 'ما هو اهتمامك الجلدي الأساسي؟',
                'options' => [
                    'recovery'  => ['en' => 'Post-workout Recovery', 'ar' => 'التعافي بعد التمرين'],
                    'hydration' => ['en' => 'Deep Hydration', 'ar' => 'ترطيب عميق'],
                    'oil'       => ['en' => 'Oil Control', 'ar' => 'التحكم في الدهون'],
                    'aging'     => ['en' => 'Anti-Aging Defense', 'ar' => 'مكافحة الشيخوخة'],
                ],
            ],
            [
                'key' => 'routine',
                'en'  => 'How often do you currently use skincare?',
                'ar'  => 'كم مرة تستخدم العناية بالبشرة حالياً؟',
                'options' => [
                    'none'     => ['en' => 'Never', 'ar' => 'أبداً'],
                    'basic'    => ['en' => 'Just basics', 'ar' => 'الأساسيات فقط'],
                    'routine'  => ['en' => 'Daily routine', 'ar' => 'روتين يومي'],
                    'advanced' => ['en' => 'Advanced', 'ar' => 'متقدم'],
                ],
            ],
            [
                'key' => 'feel',
                'en'  => 'How does your skin typically feel?',
                'ar'  => 'كيف تشعر بشرتك عادةً؟',
                'options' => [
                    'dry'       => ['en' => 'Dry & Tight', 'ar' => 'جافة وشادة'],
                    'oily'      => ['en' => 'Oily & Shiny', 'ar' => 'دهنية ولامعة'],
                    'combo'     => ['en' => 'Combination', 'ar' => 'مختلطة'],
                    'sensitive' => ['en' => 'Sensitive', 'ar' => 'حساسة'],
                ],
            ],
            [
                'key' => 'goal',
                'en'  => "What's your primary performance goal?",
                'ar'  => 'ما هو هدفك الأدائي الأساسي؟',
                'options' => [
                    'protect' => ['en' => 'Protection', 'ar' => 'الحماية'],
                    'recover' => ['en' => 'Recovery', 'ar' => 'الاسترداد'],
                    'perform' => ['en' => 'Performance', 'ar' => 'الأداء'],
                    'elevate' => ['en' => 'Elevate', 'ar' => 'الارتقاء'],
                ],
            ],
        ];
    }
}
