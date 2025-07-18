<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Contest;
use App\Models\Participation;
use App\Models\Prize;
use Illuminate\Support\Carbon;

class AwardPrizes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:award-prizes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Award prizes to contest winners';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking for contests to award prizes...');

        $contests = Contest::where('end_time', '<=', Carbon::now())
                           ->where('is_prize_awarded', false)
                           ->get();

        foreach ($contests as $contest) {
            $winner = Participation::where('contest_id', $contest->id)
                                   ->orderByDesc('score')
                                   ->orderBy('finished_at')
                                   ->first();

            if ($winner && $contest->prize_id) {
                Prize::where('id', $contest->prize_id)->update(['user_id' => $winner->user_id]);
                $contest->update(['is_prize_awarded' => true]);

                $this->info("Prize for contest '{$contest->name}' awarded to user ID {$winner->user_id}.");
            } else {
                // Also mark as awarded even if there's no winner or prize, to avoid re-checking
                $contest->update(['is_prize_awarded' => true]);
                $this->comment("No winner or no prize for contest '{$contest->name}'. Marked as awarded.");
            }
        }

        $this->info('Prize awarding process finished.');
    }
}
