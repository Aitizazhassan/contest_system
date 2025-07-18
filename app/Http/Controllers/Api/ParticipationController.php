<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Contest;
use App\Models\Participation;
use App\Models\Question;
use App\Models\Response;
use Illuminate\Support\Facades\DB;
use App\Support\ApiResponse;

class ParticipationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'contest_id' => 'required|exists:contests,id',
        ]);
        $contest = Contest::findOrFail($request->contest_id);
        $user = $request->user();

        // ensure not already participated
        $existing = Participation::where('user_id', $user->id)->where('contest_id', $contest->id)->first();
        if ($existing) {
            return response()->json($existing);
        }

        $participation = Participation::create([
            'user_id' => $user->id,
            'contest_id' => $contest->id,
        ]);

        return ApiResponse::success($participation, 'Joined', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $contestId)
    {
        $user = $request->user();
        
        $participation = Participation::firstOrCreate(
            ['user_id' => $user->id, 'contest_id' => $contestId],
            ['started_at' => now()] // This will be set only on creation
        );

        $data = $request->validate([
            'responses' => 'array|required', // [question_id => answer_ids[]]
        ]);

        DB::transaction(function () use ($participation, $data) {
            $score = 0;
            foreach ($data['responses'] as $questionId => $answerIds) {
                $question = Question::with('answers')->findOrFail($questionId);
                $correctAnswers = $question->answers->where('is_correct', true)->pluck('id')->sort()->values();
                $submitted = collect($answerIds)->sort()->values();
                $isCorrect = $correctAnswers->all() == $submitted->all();
                if ($isCorrect) {
                    $score += 1; // simple scoring: 1 per correct question
                }

                Response::create([
                    'participation_id' => $participation->id,
                    'question_id' => $questionId,
                    'answer_id' => $question->type === 'boolean' ? $submitted->first() : null,
                    'is_correct' => $isCorrect,
                ]);
            }
            $participation->update([
                'score' => $score,
                'finished_at' => now(),
            ]);
        });

        return ApiResponse::success(null, 'Submitted');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
