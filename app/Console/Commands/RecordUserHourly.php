<?php

namespace App\Console\Commands;

use App\Http\Services\ExternalApiService;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class RecordUserHourly extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:record';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Record user hourly';

    protected $externalApiService;
    private $userRepository;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(ExternalApiService $externalApiService, UserRepositoryInterface $userRepository)
    {
        parent::__construct();
        $this->externalApiService = $externalApiService;
        $this->userRepository = $userRepository;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $users = $this->externalApiService->getRandomUser();
        try {
            DB::beginTransaction();
            foreach ($users['results'] as $key => $value) {
                $this->userRepository->updateOrCreateUser($value);
            }

            $grouped_gender = $this->userRepository->getUserByGender();
            $male_count = 0;
            $female_count = 0;
            foreach ($grouped_gender as $value) {
                if ($value->Gender === 'male') {
                    $male_count = $value->count;
                }

                if ($value->Gender === 'female') {
                    $female_count = $value->count;
                }
            }

            Redis::set('male:count', $male_count);
            Redis::set('female:count', $female_count);

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
