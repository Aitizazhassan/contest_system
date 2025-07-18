<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\{Contest, Question, Answer};
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Support\ApiResponse;

class AdminQuestionController extends Controller
{
    public function store(Request $request, Contest $contest)
    {
        $validated = $request->validate([
            'text' => 'required|string',
            'type' => ['required', Rule::in(['single','multi','boolean'])],
            'answers' => 'array|required|min:1',
            'answers.*.text' => 'required|string',
            'answers.*.is_correct' => 'boolean',
        ]);

        // validation rule: for boolean & single need exactly one correct
        $correctCount = collect($validated['answers'])->where('is_correct', true)->count();
        if (($validated['type'] === 'boolean' || $validated['type'] === 'single') && $correctCount !== 1) {
            return ApiResponse::error('Exactly one correct answer required', 422);
        }
        if ($validated['type'] === 'multi' && $correctCount < 1) {
            return ApiResponse::error('At least one correct answer required', 422);
        }

        $question = $contest->questions()->create([
            'text' => $validated['text'],
            'type' => $validated['type'],
        ]);

        foreach ($validated['answers'] as $ans) {
            $question->answers()->create([
                'text' => $ans['text'],
                'is_correct' => $ans['is_correct'] ?? false,
            ]);
        }

        return ApiResponse::success($question->load('answers'), 'Question created', 201);
    }

    public function update(Request $request, Question $question)
    {
        $validated = $request->validate([
            'text' => 'string',
            'type' => [Rule::in(['single','multi','boolean'])],
            'answers' => 'array',
            'answers.*.id' => 'integer|exists:answers,id',
            'answers.*.text' => 'required|string',
            'answers.*.is_correct' => 'boolean',
        ]);

        $question->update($validated);

        if (isset($validated['answers'])) {
            // simplistic: delete existing then recreate
            $question->answers()->delete();
            foreach ($validated['answers'] as $ans) {
                $question->answers()->create([
                    'text' => $ans['text'],
                    'is_correct' => $ans['is_correct'] ?? false,
                ]);
            }
        }

        return ApiResponse::success($question->load('answers'), 'Question updated');
    }

    public function destroy(Question $question)
    {
        $question->delete();
        return ApiResponse::success(null, 'Deleted');
    }
}
