<?php

namespace App\Console\Commands;

use App\Http\Services\ExternalApiService;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Console\Command;

class RecordUser extends Command
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
        if ($users['results']) {
            foreach ($users['results'] as $key => $value) {
                if ($value['login']) {
                    $this->userRepository->updateOrCreateUser($value['login']['uuid'], [
                        'Gender'    => $value['gender'],
                        'Name'      => $value['name'],
                        'Location'  => $value['location'],
                        'age'       => $value['dob']['age']
                    ]);
                }
                
            }
        }
    }
}
