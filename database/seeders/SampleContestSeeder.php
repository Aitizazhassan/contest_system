<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\{Contest, Question, Answer, Prize};

class SampleContestSeeder extends Seeder
{
    public function run(): void
    {
        for ($i = 1; $i <= 5; $i++) {
            $contest = Contest::create([
                'name' => "Contest #{$i}",
                'description' => "This is sample contest number {$i}",
                'access_level' => ($i % 2 == 0) ? 'vip' : 'normal', // mix it up
                'start_time' => now()->subDay($i),
                'end_time' => now()->addDays($i),
            ]);

            $prize = Prize::create([
                'contest_id' => $contest->id,
                'name' => "Prize for Contest #{$i}",
                'description' => "A special prize for winner of contest #{$i}",
            ]);
            
            $contest->update(['prize_id' => $prize->id]);

            // --- Question 1 (Single) ---
            $q1 = Question::create([
                'contest_id' => $contest->id,
                'text' => "What is the capital of France? (Contest {$i})",
                'type' => 'single',
            ]);
            Answer::insert([
                ['question_id' => $q1->id, 'text' => 'Berlin', 'is_correct' => false],
                ['question_id' => $q1->id, 'text' => 'Paris', 'is_correct' => true],
                ['question_id' => $q1->id, 'text' => 'Madrid', 'is_correct' => false],
            ]);

            // --- Question 2 (Multi) ---
            $q2 = Question::create([
                'contest_id' => $contest->id,
                'text' => "Which of the following are planets? (Contest {$i})",
                'type' => 'multi',
            ]);
            Answer::insert([
                ['question_id' => $q2->id, 'text' => 'Earth', 'is_correct' => true],
                ['question_id' => $q2->id, 'text' => 'Moon', 'is_correct' => false],
                ['question_id' => $q2->id, 'text' => 'Mars', 'is_correct' => true],
            ]);

            // --- Question 3 (Boolean) ---
            $q3 = Question::create([
                'contest_id' => $contest->id,
                'text' => "Is the sky blue? (Contest {$i})",
                'type' => 'boolean',
            ]);
            Answer::insert([
                ['question_id' => $q3->id, 'text' => 'True', 'is_correct' => true],
                ['question_id' => $q3->id, 'text' => 'False', 'is_correct' => false],
            ]);

            // --- Question 4 (Single) ---
            $q4 = Question::create([
                'contest_id' => $contest->id,
                'text' => "What is 10 * 10? (Contest {$i})",
                'type' => 'single',
            ]);
            Answer::insert([
                ['question_id' => $q4->id, 'text' => '100', 'is_correct' => true],
                ['question_id' => $q4->id, 'text' => '1000', 'is_correct' => false],
                ['question_id' => $q4->id, 'text' => '10', 'is_correct' => false],
            ]);

            // --- Question 5 (Multi) ---
            $q5 = Question::create([
                'contest_id' => $contest->id,
                'text' => "Which of these are fruits? (Contest {$i})",
                'type' => 'multi',
            ]);
            Answer::insert([
                ['question_id' => $q5->id, 'text' => 'Apple', 'is_correct' => true],
                ['question_id' => $q5->id, 'text' => 'Carrot', 'is_correct' => false],
                ['question_id' => $q5->id, 'text' => 'Banana', 'is_correct' => true],
            ]);
        }
    }
} 