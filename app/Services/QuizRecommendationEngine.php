<?php

namespace App\Services;

use App\Models\Product;
use App\Models\QuizSession;

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
}
