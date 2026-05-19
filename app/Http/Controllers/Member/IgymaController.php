<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;

class IgymaController extends Controller
{
    private const SYSTEM_PROMPT = <<<'EOT'
You are IGyma 🏋️, the official AI fitness assistant inside the I-Gym platform.

You ONLY answer questions related to:
- sports
- gym workouts
- bodybuilding
- weight loss
- muscle gain
- strength training
- cardio
- fitness
- stretching
- warmups
- mobility
- sports performance
- workout recovery
- hydration
- gym nutrition basics
- exercise techniques
- gym routines
- healthy fitness habits
- gym motivation
- beginner workout guidance
- fitness planning
- sports-related recommendations

You MUST refuse any unrelated topic.

If the user asks about programming, hacking, coding, politics, religion, relationships, finance, crypto, illegal activities, adult content, violence, drugs, weapons, school exams, or general knowledge unrelated to sports, reply exactly:

"I'm IGyma 🏋️, your fitness assistant inside I-Gym. I can only help with sports, workouts, gym training, fitness goals, recovery, and healthy athletic habits."

Never provide medical diagnosis.
For injuries, severe pain, dizziness, chest pain, or health risks, reply:
"For medical or injury-related concerns, please consult a qualified healthcare professional or sports specialist."

Keep answers:
- short
- clear
- practical
- beginner-friendly
- motivating
- safe
EOT;

    public function index(): View
    {
        return view('member.igyma');
    }

    public function chat(Request $request)
    {
        $validated = $request->validate([
            'message' => 'required|string|max:1000',
        ], [
            'message.required' => __('messages.igyma_message_required'),
            'message.string' => __('messages.igyma_message_string'),
            'message.max' => __('messages.igyma_message_too_long'),
        ]);

        try {
            $locale = app()->getLocale();
            $response = $this->callGroqAPI($validated['message'], $locale);
            
            return response()->json([
                'success' => true,
                'reply' => $response,
            ]);
        } catch (\Exception $e) {
            \Log::error('IGyma chat error', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
            ]);

            return response()->json([
                'success' => false,
                'error' => __('messages.igyma_unavailable'),
            ], 500);
        }
    }

    private function callGroqAPI(string $userMessage, string $locale = 'en'): string
    {
        $groqKey = config('services.groq.key');
        $groqModel = config('services.groq.model');

        if (!$groqKey) {
            throw new \Exception('Groq API key not configured');
        }

        $languageInstruction = match ($locale) {
            'fr' => 'IMPORTANT : Réponds toujours en français, quelle que soit la langue de la question.',
            'es' => 'IMPORTANTE: Responde siempre en español, independientemente del idioma de la pregunta.',
            'ar' => 'مهم: قم بالرد دائمًا باللغة العربية، بغض النظر عن لغة السؤال.',
            default => 'IMPORTANT: Always respond in English, regardless of the language of the question.',
        };

        $response = Http::timeout(15)
            ->withHeader('Authorization', "Bearer {$groqKey}")
            ->post('https://api.groq.com/openai/v1/chat/completions', [
                'model' => $groqModel,
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => self::SYSTEM_PROMPT . "\n\n{$languageInstruction}",
                    ],
                    [
                        'role' => 'user',
                        'content' => $userMessage,
                    ],
                ],
                'temperature' => 0.7,
                'max_tokens' => 500,
            ]);

        if (!$response->successful()) {
            \Log::warning('Groq API error', [
                'status' => $response->status(),
                'user_id' => auth()->id(),
            ]);
            throw new \Exception('Groq API request failed');
        }

        $data = $response->json();
        return $data['choices'][0]['message']['content'] ?? 'No response from IGyma';
    }
}
