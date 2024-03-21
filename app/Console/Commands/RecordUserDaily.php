<?php

namespace App\Console\Commands;

use App\Repositories\Interfaces\DailyRecordRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;

class RecordUserDaily extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:record-daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Record user daily';

    private $userRepository;
    private $dailyRecordRepository;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(UserRepositoryInterface $userRepository, DailyRecordRepositoryInterface $dailyRecordRepository)
    {
        parent::__construct();
        $this->userRepository = $userRepository;
        $this->dailyRecordRepository = $dailyRecordRepository;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            $grouped_gender = $this->userRepository->getAverageAgeUsersByGender();
            $male_avg_age = collect($grouped_gender)->firstWhere('Gender', 'male');
            $female_avg_age = collect($grouped_gender)->firstWhere('Gender', 'female');
            $male_count = Redis::get('male:count');
            $female_count = Redis::get('female:count');

            $this->dailyRecordRepository->create([
                'date'          => date('Y-m-d'),
                'male_count'    => $male_count,
                'female_count'  => $female_count,
                'male_avg_age'  => $male_avg_age ? $male_avg_age->avg_age : 0,
                'female_avg_age'=> $female_avg_age ? $female_avg_age->avg_age : 0
            ]);

            Log::info('user:record-daily|success');
        } catch (Exception $e) {
            $errorMessage = $e->getMessage();
            Log::error("user:record-daily|error|$errorMessage");
        }
        
    }
}
