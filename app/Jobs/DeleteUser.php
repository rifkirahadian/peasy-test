<?php

namespace App\Jobs;

use App\Repositories\Interfaces\DailyRecordRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;

class DeleteUser implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    
    private $userId;
    private $userRepository;
    private $dailyRecordRepository;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(
        $userId, 
        UserRepositoryInterface $userRepository, 
        DailyRecordRepositoryInterface $dailyRecordRepository
    ) {
        $this->userId = $userId;
        $this->userRepository = $userRepository;
        $this->dailyRecordRepository = $dailyRecordRepository;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            DB::beginTransaction();
            $user = $this->userRepository->find($this->userId);
            $date = date('Y-m-d', strtotime($user->created_at));

            $this->userRepository->delete($this->userId);

            $avg_age = $this->userRepository->getAverageAgeUsersByGender($date);
            $male_avg_age = collect($avg_age)->firstWhere('Gender', 'male');
            $female_avg_age = collect($avg_age)->firstWhere('Gender', 'female');
            
            $count = $this->userRepository->getUserCountByGender($date);
            $male = collect($count)->firstWhere('Gender', 'male');
            $female = collect($count)->firstWhere('Gender', 'female');
            $male_count = $male ? $male->count : 0;
            $female_count = $female ? $female->count : 0;

            if (date('Y-m-d') === $date) {
                Redis::set('male:count', $male_count);
                Redis::set('female:count', $female_count);
            }

            $this->dailyRecordRepository->create([
                'date'          => $date,
            ],[
                'male_count'    => $male_count,
                'female_count'  => $female_count,
                'male_avg_age'  => $male_avg_age ? $male_avg_age->avg_age : 0,
                'female_avg_age'=> $female_avg_age ? $female_avg_age->avg_age : 0
            ]);
            
            Log::error("delete-user|success");
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            $errorMessage = $e->getMessage();
            Log::error("delete-user|error|$errorMessage");
        }
    }
}
