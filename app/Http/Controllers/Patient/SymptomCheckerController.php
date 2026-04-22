<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SymptomCheckerController extends Controller
{
    public function index(Request $request)
    {
        $analysis = null;
        $symptoms = '';

        if ($request->isMethod('post')) {
            $validated = $request->validate([
                'symptoms' => 'required|string|min:10|max:2000',
            ]);

            $symptoms = trim($validated['symptoms']);
            $analysis = $this->analyzeSymptoms($symptoms);
        }

        return view('patient.symptom-checker.index', compact('analysis', 'symptoms'));
    }

    protected function analyzeSymptoms(string $input): array
    {
        $text = strtolower($input);

        $conditions = [
            [
                'name' => 'Common Cold or Viral Upper Respiratory Infection',
                'category' => 'General Medicine',
                'summary' => 'Your symptoms may match a mild viral infection affecting the nose and throat.',
                'keywords' => ['cough', 'sore throat', 'runny nose', 'sneezing', 'congestion', 'cold', 'mild fever'],
            ],
            [
                'name' => 'Seasonal Flu',
                'category' => 'General Medicine',
                'summary' => 'This pattern can fit flu-like illness, especially with fever, body ache, and fatigue.',
                'keywords' => ['high fever', 'fever', 'body ache', 'fatigue', 'weakness', 'chills', 'headache', 'cough'],
            ],
            [
                'name' => 'Migraine or Tension Headache',
                'category' => 'Neurology',
                'summary' => 'Head pain, nausea, light sensitivity, or pressure may point toward a headache disorder.',
                'keywords' => ['headache', 'migraine', 'nausea', 'light sensitivity', 'vomiting', 'pressure in head', 'dizziness'],
            ],
            [
                'name' => 'Gastritis or Acid Reflux',
                'category' => 'General Medicine',
                'summary' => 'Burning stomach discomfort, acidity, or nausea can be linked to digestive irritation or reflux.',
                'keywords' => ['acidity', 'heartburn', 'stomach pain', 'nausea', 'bloating', 'indigestion', 'burning chest'],
            ],
            [
                'name' => 'Allergy or Sinus Inflammation',
                'category' => 'General Medicine',
                'summary' => 'Nasal blockage, sneezing, watery eyes, or facial pressure may indicate allergy or sinus issues.',
                'keywords' => ['sneezing', 'itchy eyes', 'watery eyes', 'sinus', 'blocked nose', 'runny nose', 'facial pressure'],
            ],
            [
                'name' => 'Skin Allergy or Dermatitis',
                'category' => 'Dermatology',
                'summary' => 'Rash, itching, redness, or irritation may suggest a skin-related allergy or inflammatory condition.',
                'keywords' => ['rash', 'itching', 'redness', 'skin irritation', 'hives', 'dry skin', 'eczema'],
            ],
            [
                'name' => 'Joint or Muscle Strain',
                'category' => 'Orthopedics',
                'summary' => 'Localized pain, swelling, or movement discomfort can fit strain, sprain, or joint inflammation.',
                'keywords' => ['joint pain', 'knee pain', 'back pain', 'swelling', 'stiffness', 'muscle pain', 'shoulder pain'],
            ],
            [
                'name' => 'Anxiety or Stress-Related Symptoms',
                'category' => 'Psychiatry',
                'summary' => 'Stress can present with palpitations, restlessness, chest tightness, sleep problems, or excessive worry.',
                'keywords' => ['anxiety', 'stress', 'panic', 'palpitations', 'restlessness', 'sleep problem', 'worry'],
            ],
        ];

        $redFlags = [
            'chest pain' => 'Chest pain can be serious. Please seek urgent medical care immediately.',
            'shortness of breath' => 'Shortness of breath needs prompt medical attention, especially if it is new or worsening.',
            'difficulty breathing' => 'Difficulty breathing needs prompt medical attention, especially if it is new or worsening.',
            'fainting' => 'Fainting or near-fainting should be assessed urgently.',
            'seizure' => 'Seizure-like symptoms require urgent evaluation.',
            'blood in vomit' => 'Vomiting blood is an emergency symptom.',
            'blood in stool' => 'Blood in stool should be evaluated urgently.',
            'one-sided weakness' => 'One-sided weakness can be a neurological emergency.',
        ];

        $matchedConditions = collect($conditions)
            ->map(function (array $condition) use ($text) {
                $score = 0;
                $matchedKeywords = [];

                foreach ($condition['keywords'] as $keyword) {
                    if (str_contains($text, $keyword)) {
                        $score++;
                        $matchedKeywords[] = $keyword;
                    }
                }

                $condition['score'] = $score;
                $condition['matched_keywords'] = $matchedKeywords;

                return $condition;
            })
            ->filter(fn (array $condition) => $condition['score'] > 0)
            ->sortByDesc('score')
            ->take(3)
            ->values()
            ->all();

        $alerts = [];
        foreach ($redFlags as $phrase => $message) {
            if (str_contains($text, $phrase)) {
                $alerts[] = $message;
            }
        }

        if (empty($matchedConditions)) {
            $matchedConditions[] = [
                'name' => 'General Medical Review Recommended',
                'category' => 'General Medicine',
                'summary' => 'Your symptom description is not specific enough for a closer match, so a general doctor review is the safest next step.',
                'score' => 0,
                'matched_keywords' => [],
            ];
        }

        return [
            'input' => $input,
            'alerts' => array_values(array_unique($alerts)),
            'conditions' => $matchedConditions,
            'next_step' => empty($alerts)
                ? 'This checker is only a guidance tool, not a diagnosis. If symptoms are persistent, severe, or worsening, book a doctor consultation.'
                : 'Because one or more urgent warning signs were detected, please seek immediate medical attention instead of relying only on this checker.',
        ];
    }
}
