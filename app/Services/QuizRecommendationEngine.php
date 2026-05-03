<?php

namespace App\Services;

use App\Models\Product;

/**
 * FERRO Skincare Quiz Engine (Advanced Feature #2)
 *
 * Quiz Flow (5 questions):
 *   Q1 — Lifestyle: "athlete" | "urban_exec" | "outdoor" | "casual"
 *   Q2 — Skin type: "oily" | "dry" | "combination" | "sensitive"
 *   Q3 — Primary concern: ["recovery", "hydration", "anti_aging", "texture", "protection"]
 *   Q4 — Activity level: "daily_training" | "weekend_warrior" | "sedentary"
 *   Q5 — Skin goal: "performance" | "luxury" | "natural" | "anti_pollution"
 *
 * Output: Skin Profile + ordered product recommendations
 */
class QuizRecommendationEngine
{
    /**
     * Named skin profiles (displayed as hero identity label on results page).
     */
    private const PROFILES = [
        'elite_athlete'      => ['en' => 'The Elite Athlete',      'ar' => 'الرياضي النخبة'],
        'urban_resilience'   => ['en' => 'Urban Resilience',        'ar' => 'صمود المدينة'],
        'natural_warrior'    => ['en' => 'The Natural Warrior',     'ar' => 'المحارب الطبيعي'],
        'refined_gentleman'  => ['en' => 'The Refined Gentleman',   'ar' => 'الرجل الراقي'],
    ];

    public function determineProfile(array $answers): string
    {
        $lifestyle = $answers['q1'] ?? 'casual';
        $goal      = $answers['q5'] ?? 'performance';

        if ($lifestyle === 'athlete' && in_array($goal, ['performance', 'natural'])) {
            return 'elite_athlete';
        }
        if ($lifestyle === 'urban_exec') {
            return 'urban_resilience';
        }
        if ($lifestyle === 'outdoor' || $goal === 'natural') {
            return 'natural_warrior';
        }
        return 'refined_gentleman';
    }

    /**
     * Returns ordered array of Product IDs ranked by quiz-tag overlap.
     */
    public function recommend(array $answers, string $locale = 'en'): array
    {
        $quizTags = $this->answersToTags($answers);

        $products = Product::active()
            ->whereNotNull('quiz_tags')
            ->get()
            ->map(function (Product $product) use ($quizTags) {
                $productTags  = $product->quiz_tags ?? [];
                $overlap      = count(array_intersect($quizTags, $productTags));
                return ['product' => $product, 'score' => $overlap];
            })
            ->filter(fn ($item) => $item['score'] > 0)
            ->sortByDesc('score')
            ->take(3)
            ->pluck('product')
            ->values();

        return [
            'profile'  => $this->determineProfile($answers),
            'products' => $products,
            'tags'     => $quizTags,
        ];
    }

    public function getProfileLabel(string $profile, string $locale): string
    {
        return self::PROFILES[$profile][$locale] ?? $profile;
    }

    private function answersToTags(array $answers): array
    {
        $tags = [];

        // Q1 Lifestyle mapping
        $lifestyleMap = [
            'athlete'    => ['post_workout', 'performance', 'recovery'],
            'urban_exec' => ['anti_pollution', 'anti_aging', 'luxury'],
            'outdoor'    => ['protection', 'natural', 'spf'],
            'casual'     => ['hydration', 'sensitive'],
        ];
        $tags = array_merge($tags, $lifestyleMap[$answers['q1'] ?? 'casual'] ?? []);

        // Q2 Skin type
        $skinMap = ['oily' => ['oil_control', 'mattifying'], 'dry' => ['hydration', 'nourishing'], 'sensitive' => ['sensitive', 'calming']];
        $tags = array_merge($tags, $skinMap[$answers['q2'] ?? ''] ?? []);

        // Q3 Concerns (multi-select)
        if (!empty($answers['q3'])) {
            $tags = array_merge($tags, (array) $answers['q3']);
        }

        return array_unique($tags);
    }

    /**
     * Public storefront quiz (resources/views/quiz.blade.php) — answers indexed 0–4.
     *
     * @param  array<int|string, string>  $answers
     * @return array{
     *   profile: array{label_en: string, label_ar: string, desc_en: string, desc_ar: string},
     *   product_ids: list<int>,
     *   products: \Illuminate\Support\Collection<int, \App\Models\Product>,
     *   tags: list<string>
     * }
     */
    public function analyzeFromUiAnswers(array $answers): array
    {
        $tags = $this->uiAnswersToTags($answers);

        $products = Product::query()
            ->visible()
            ->whereNotNull('quiz_tags')
            ->get()
            ->map(function (Product $product) use ($tags) {
                $productTags = $product->quiz_tags ?? [];
                $overlap     = count(array_intersect($tags, $productTags));

                return ['product' => $product, 'score' => $overlap];
            })
            ->filter(fn ($row) => $row['score'] > 0)
            ->sortByDesc('score')
            ->take(3)
            ->pluck('product')
            ->values();

        $profile = $this->skinProfileFromUiAnswers($answers);

        return [
            'profile'     => $profile,
            'product_ids' => $products->pluck('id')->all(),
            'products'    => $products,
            'tags'        => array_values(array_unique($tags)),
        ];
    }

    /**
     * @param  array<int|string, string>  $answers
     * @return list<string>
     */
    private function uiAnswersToTags(array $answers): array
    {
        $tags = [];
        foreach ($answers as $value) {
            if (! is_string($value) || $value === '') {
                continue;
            }
            $tags[] = $value;
            if ($value === 'oil') {
                $tags[] = 'oily';
            }
            if ($value === 'recovery') {
                $tags[] = 'recover';
            }
        }

        return array_values(array_unique($tags));
    }

    /**
     * @param  array<int|string, string>  $answers
     * @return array{label_en: string, label_ar: string, desc_en: string, desc_ar: string}
     */
    private function skinProfileFromUiAnswers(array $answers): array
    {
        $lifestyle = $answers[0] ?? $answers['0'] ?? 'athlete';
        $concern   = $answers[1] ?? $answers['1'] ?? 'hydration';
        $feel      = $answers[3] ?? $answers['3'] ?? 'combo';

        $matrix = [
            'athlete' => [
                'label_en' => 'The Iron Athlete',
                'label_ar' => 'الرياضي الحديدي',
                'desc_en'  => 'Built for performance. Your skin needs recovery, resilience, and deep cleansing after intense output.',
                'desc_ar'  => 'مصمم للأداء. بشرتك تحتاج استرداداً ومرونة وتنظيفاً عميقاً بعد الجهد المكثف.',
            ],
            'executive' => [
                'label_en' => 'Urban Resilience',
                'label_ar' => 'صمود المدينة',
                'desc_en'  => 'High-stress environments demand barrier support, pollution defense, and a polished look that lasts.',
                'desc_ar'  => 'البيئات عالية الضغط تتطلب دعماً للحاجز والدفاع ضد التلوث ومظهراً أنيقاً يدوم.',
            ],
            'outdoor' => [
                'label_en' => 'The Natural Warrior',
                'label_ar' => 'المحارب الطبيعي',
                'desc_en'  => 'Sun, wind, and sweat — your regimen should protect, restore, and keep skin calm after exposure.',
                'desc_ar'  => 'الشمس والرياح والعرق — روتينك يجب أن يحمي ويستعيد ويهدئ البشرة بعد التعرض.',
            ],
            'refined' => [
                'label_en' => 'The Refined Gentleman',
                'label_ar' => 'الرجل الراقي',
                'desc_en'  => 'Precision and luxury. Focus on texture, tone, and elevated daily rituals that feel effortless.',
                'desc_ar'  => 'الدقة والفخامة. ركز على الملمس واللون وطقوس يومية راقية بسهولة.',
            ],
        ];

        $base = $matrix[$lifestyle] ?? $matrix['athlete'];

        if (in_array($feel, ['dry', 'sensitive'], true)) {
            $base['desc_en'] .= ' Hydration and barrier care are especially important for you.';
            $base['desc_ar'] .= ' الترطيب والعناية بالحاجز مهمان بشكل خاص بالنسبة لك.';
        }
        if ($concern === 'aging') {
            $base['desc_en'] .= ' Anti-aging actives will complement your goals.';
            $base['desc_ar'] .= ' مكونات مكافحة الشيخوخة ستكمل أهدافك.';
        }

        return [
            'label_en' => $base['label_en'],
            'label_ar' => $base['label_ar'],
            'desc_en'  => $base['desc_en'],
            'desc_ar'  => $base['desc_ar'],
        ];
    }
}
